import json
import math
import sys
from pathlib import Path

import numpy as np
import pandas as pd


def clean_value(value):
    if value is None:
        return None
    try:
        if pd.isna(value):
            return None
    except (TypeError, ValueError):
        pass
    if isinstance(value, (pd.Timestamp, pd.Timedelta)):
        return str(value)
    if isinstance(value, np.generic):
        value = value.item()
    if isinstance(value, float):
        if not math.isfinite(value):
            return None
        return round(value, 6)
    if isinstance(value, (str, int, bool)):
        return value
    return str(value)


def frame_table(frame, limit=50):
    display = frame.head(limit)
    return {
        "columns": [str(column) for column in display.columns],
        "rows": [
            [clean_value(value) for value in row]
            for row in display.itertuples(index=False, name=None)
        ],
        "shown": min(len(frame), limit),
        "total": int(len(frame)),
    }


def load_dataset(path):
    reader = None
    try:
        reader = pd.io.stata.StataReader(path, convert_categoricals=True)
        labels = reader.variable_labels()
        data_label = clean_value(reader.data_label)
        frame = reader.read()
    except ValueError:
        reader = pd.io.stata.StataReader(path, convert_categoricals=False)
        labels = reader.variable_labels()
        data_label = clean_value(reader.data_label)
        frame = reader.read()

    return frame, labels, data_label


def selected_columns(frame, requested):
    requested = requested or []
    unknown = [column for column in requested if column not in frame.columns]
    if unknown:
        raise ValueError("Variabel tidak ditemukan: " + ", ".join(unknown))
    return requested


def variable_rows(frame, labels):
    rows = []
    for column in frame.columns:
        series = frame[column]
        rows.append([
            str(column),
            str(series.dtype),
            labels.get(column, ""),
            int(series.notna().sum()),
            int(series.isna().sum()),
            int(series.nunique(dropna=True)),
        ])
    return rows


def execute(frame, labels, payload):
    command = payload.get("command", "inspect")
    requested = selected_columns(frame, payload.get("variables", []))
    numeric = list(frame.select_dtypes(include=["number"]).columns)

    if command == "inspect":
        return None

    if command == "describe":
        return {
            "title": "Struktur Dataset",
            "command": ". describe",
            "message": f"{len(frame.columns)} variabel dan {len(frame)} observasi.",
            "table": {
                "columns": ["Variabel", "Tipe", "Label", "Obs", "Missing", "Unik"],
                "rows": variable_rows(frame, labels),
                "shown": len(frame.columns),
                "total": len(frame.columns),
            },
        }

    if command == "summarize":
        columns = requested or numeric
        columns = [column for column in columns if column in numeric]
        if not columns:
            raise ValueError("Pilih minimal satu variabel numerik untuk summarize.")
        stats = frame[columns].describe().transpose().reset_index()
        stats = stats.rename(columns={"index": "variable"})
        return {
            "title": "Statistik Deskriptif",
            "command": ". summarize " + " ".join(columns),
            "message": "Ringkasan count, mean, standar deviasi, minimum, dan maksimum.",
            "table": frame_table(stats),
        }

    if command == "list":
        columns = requested or list(frame.columns)
        return {
            "title": "Data Editor",
            "command": ". list " + " ".join(columns) + " in 1/50",
            "message": "Menampilkan maksimal 50 observasi pertama.",
            "table": frame_table(frame[columns]),
        }

    if command == "missing":
        columns = requested or list(frame.columns)
        rows = []
        for column in columns:
            missing = int(frame[column].isna().sum())
            percentage = (missing / len(frame) * 100) if len(frame) else 0
            rows.append([column, missing, round(percentage, 2), int(frame[column].notna().sum())])
        return {
            "title": "Missing Values",
            "command": ". misstable summarize " + " ".join(columns),
            "message": "Jumlah dan persentase nilai kosong per variabel.",
            "table": {
                "columns": ["Variabel", "Missing", "Persen", "Non-missing"],
                "rows": rows,
                "shown": len(rows),
                "total": len(rows),
            },
        }

    if command == "correlate":
        columns = requested or numeric
        columns = [column for column in columns if column in numeric]
        if len(columns) < 2:
            raise ValueError("Pilih minimal dua variabel numerik untuk correlate.")
        matrix = frame[columns].corr().round(4).reset_index()
        matrix = matrix.rename(columns={"index": "variable"})
        return {
            "title": "Matriks Korelasi",
            "command": ". correlate " + " ".join(columns),
            "message": "Koefisien korelasi Pearson antarvariabel.",
            "table": frame_table(matrix),
        }

    if command == "tabulate":
        if len(requested) != 1:
            raise ValueError("Pilih tepat satu variabel untuk tabulate.")
        column = requested[0]
        counts = frame[column].value_counts(dropna=False).head(50)
        rows = []
        for value, frequency in counts.items():
            label = "<missing>" if pd.isna(value) else clean_value(value)
            percent = (int(frequency) / len(frame) * 100) if len(frame) else 0
            rows.append([label, int(frequency), round(percent, 2)])
        return {
            "title": "Tabel Frekuensi",
            "command": ". tabulate " + column,
            "message": "Menampilkan maksimal 50 kategori dengan frekuensi terbesar.",
            "table": {
                "columns": [column, "Frekuensi", "Persen"],
                "rows": rows,
                "shown": len(rows),
                "total": int(frame[column].nunique(dropna=False)),
            },
        }

    if command == "sort":
        if len(requested) != 1:
            raise ValueError("Pilih tepat satu variabel untuk sort.")
        column = requested[0]
        ascending = payload.get("direction", "asc") != "desc"
        sorted_frame = frame.sort_values(column, ascending=ascending, na_position="last")
        direction = "" if ascending else "gsort -"
        stata_command = f". sort {column}" if ascending else f". gsort -{column}"
        return {
            "title": "Preview Data Terurut",
            "command": stata_command,
            "message": "Preview terurut tanpa mengubah file asli.",
            "table": frame_table(sorted_frame[requested + [c for c in frame.columns if c != column]][:]),
        }

    if command == "regress":
        columns = requested
        if len(columns) < 2 or any(column not in numeric for column in columns):
            raise ValueError("Pilih minimal dua variabel numerik; pilihan pertama menjadi variabel dependen (Y).")
        dependent, predictors = columns[0], columns[1:]
        model_data = frame[columns].apply(pd.to_numeric, errors="coerce").dropna()
        if len(model_data) <= len(predictors) + 1:
            raise ValueError("Observasi valid tidak cukup untuk menjalankan regresi.")
        y = model_data[dependent].to_numpy(dtype=float)
        x = model_data[predictors].to_numpy(dtype=float)
        x = np.column_stack([np.ones(len(x)), x])
        coefficients, _, _, _ = np.linalg.lstsq(x, y, rcond=None)
        predictions = x @ coefficients
        ss_res = float(np.sum((y - predictions) ** 2))
        ss_total = float(np.sum((y - np.mean(y)) ** 2))
        r_squared = 1 - (ss_res / ss_total) if ss_total else 0
        names = ["_cons"] + predictors
        rows = [[name, round(float(coefficient), 6)] for name, coefficient in zip(names, coefficients)]
        return {
            "title": "Regresi Linear OLS",
            "command": ". regress " + " ".join(columns),
            "message": f"Dependent: {dependent} | Obs: {len(model_data)} | R-squared: {r_squared:.4f}",
            "table": {
                "columns": ["Variabel", "Koefisien"],
                "rows": rows,
                "shown": len(rows),
                "total": len(rows),
            },
        }

    raise ValueError("Command tidak didukung.")


def main():
    if len(sys.argv) != 2:
        raise ValueError("Path file .dta wajib diberikan.")
    path = Path(sys.argv[1]).resolve()
    if not path.is_file() or path.suffix.lower() != ".dta":
        raise ValueError("File .dta tidak ditemukan.")
    payload = json.loads(sys.stdin.read() or "{}")
    frame, labels, data_label = load_dataset(path)
    result = execute(frame, labels, payload)
    if result is None:
        result = {
            "summary": {
                "rows": int(len(frame)),
                "columns": int(len(frame.columns)),
                "numeric_columns": int(len(frame.select_dtypes(include=["number"]).columns)),
                "data_label": data_label or "Tanpa label dataset",
            },
            "variables": [
                {
                    "name": str(column),
                    "type": str(frame[column].dtype),
                    "label": labels.get(column, ""),
                    "numeric": bool(pd.api.types.is_numeric_dtype(frame[column])),
                }
                for column in frame.columns
            ],
            "table": frame_table(frame, limit=20),
        }
    print(json.dumps(result, ensure_ascii=False, allow_nan=False))


if __name__ == "__main__":
    try:
        main()
    except Exception as error:
        print(json.dumps({"error": str(error)}, ensure_ascii=False))
        sys.exit(1)
