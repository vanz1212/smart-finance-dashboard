import io

import pandas as pd
import plotly.express as px
import streamlit as st
import statsmodels.api as sm
import statsmodels.formula.api as smf

# Atur konfigurasi halaman Streamlit agar tampilan modern dan responsif.
st.set_page_config(
    page_title="SMART FINANCE ANALYTICS DASHBOARD",
    page_icon="📊",
    layout="wide",
)

# Judul aplikasi dan deskripsi singkat.
st.title("SMART FINANCE ANALYTICS DASHBOARD")
st.markdown(
    "Aplikasi analisis data ekonomi dan keuangan untuk mahasiswa dengan pengalaman mirip Stata tetapi lebih modern dan mudah digunakan."
)

# Bagian sidebar untuk navigasi halaman.
menu = st.sidebar.radio(
    "Menu",
    [
        "Dashboard Utama",
        "Import Data",
        "Data Viewer",
        "Statistik Deskriptif",
        "Visualisasi Data",
        "Analisis Ekonomi",
        "Export",
    ],
)

# Fungsi utilitas untuk memuat data dari file CSV atau Excel.
def load_data(uploaded_file):
    if uploaded_file is None:
        return None
    file_name = uploaded_file.name.lower()
    if file_name.endswith(".csv"):
        return pd.read_csv(uploaded_file)
    if file_name.endswith(".xls") or file_name.endswith(".xlsx"):
        return pd.read_excel(uploaded_file)
    st.error("Format file tidak didukung. Gunakan CSV atau Excel.")
    return None


# Fungsi untuk menampilkan ringkasan statistik cepat.
def summary_statistics(df):
    st.subheader("Statistik Ringkas")
    if df is None or df.empty:
        st.info("Unggah data terlebih dahulu untuk melihat statistik ringkas.")
        return
    numeric_df = df.select_dtypes(include=["number"])
    if numeric_df.empty:
        st.warning("Tidak ada kolom numerik dalam data untuk statistik ringkas.")
        return

    col1, col2, col3, col4 = st.columns(4)
    col1.metric("Jumlah Baris", len(df))
    col2.metric("Jumlah Variabel", len(df.columns))
    col3.metric("Kolom Numerik", len(numeric_df.columns))
    col4.metric("Missing Values", int(df.isna().sum().sum()))

    st.dataframe(numeric_df.describe().T[["mean", "std", "min", "max"]].round(2))


# Fungsi untuk menghasilkan tabel statistik bergaya Stata.
def stata_style_table(df):
    numeric_df = df.select_dtypes(include=["number"])
    stats = []
    for col in numeric_df.columns:
        series = numeric_df[col].dropna()
        stats.append(
            {
                "Variable": col,
                "Obs": series.count(),
                "Mean": series.mean(),
                "Std Dev": series.std(),
                "Min": series.min(),
                "Max": series.max(),
            }
        )
    stata_df = pd.DataFrame(stats)
    return stata_df


# Fungsi untuk mengubah dataframe ke format CSV untuk diunduh.
def convert_df_to_csv(df):
    return df.to_csv(index=False).encode("utf-8")


# Fungsi untuk mengubah dataframe ke format Excel untuk diunduh.
def convert_df_to_excel(df):
    output = io.BytesIO()
    with pd.ExcelWriter(output, engine="openpyxl") as writer:
        df.to_excel(writer, index=False, sheet_name="Data")
        writer.save()
    return output.getvalue()


# Fungsi untuk membuat file PDF sederhana dari tabel statistik.
def convert_stats_to_pdf(stat_df, title="Laporan Statistik"):
    try:
        from reportlab.lib.pagesizes import letter
        from reportlab.lib.units import inch
        from reportlab.pdfgen import canvas
    except ImportError:
        return None

    buffer = io.BytesIO()
    c = canvas.Canvas(buffer, pagesize=letter)
    width, height = letter
    c.setFont("Helvetica-Bold", 16)
    c.drawString(1 * inch, height - 1 * inch, title)
    c.setFont("Helvetica", 11)

    x = 1 * inch
    y = height - 1.5 * inch
    columns = list(stat_df.columns)
    row_height = 18

    # Header kolom
    for idx, col_name in enumerate(columns):
        c.drawString(x + idx * 1.4 * inch, y, str(col_name))
    y -= row_height

    # Baris data
    for _, row in stat_df.iterrows():
        for idx, col_name in enumerate(columns):
            c.drawString(x + idx * 1.4 * inch, y, str(round(row[col_name], 2)) if isinstance(row[col_name], float) else str(row[col_name]))
        y -= row_height
        if y < 1 * inch:
            c.showPage()
            y = height - 1 * inch
    c.save()
    pdf = buffer.getvalue()
    buffer.close()
    return pdf


# Fungsi untuk menampilkan grafik Plotly dengan kontrol pilihan pengguna.
def render_plot(df):
    st.subheader("Visualisasi Data")
    if df is None or df.empty:
        st.info("Unggah data terlebih dahulu untuk menampilkan visualisasi.")
        return

    numeric_columns = df.select_dtypes(include=["number"]).columns.tolist()
    all_columns = df.columns.tolist()

    chart_type = st.selectbox(
        "Pilih tipe grafik",
        ["Bar Chart", "Pie Chart", "Histogram", "Line Chart", "Scatter Plot"],
    )

    if chart_type == "Pie Chart" and len(all_columns) < 2:
        st.warning("Pie chart membutuhkan minimal 2 kolom, satu kategori dan satu numerik.")

    if chart_type == "Bar Chart":
        x_axis = st.selectbox("Sumbu X", all_columns)
        y_axis = st.selectbox("Sumbu Y", numeric_columns)
        fig = px.bar(df, x=x_axis, y=y_axis, title="Bar Chart", template="plotly_white")
        st.plotly_chart(fig, use_container_width=True)

    elif chart_type == "Pie Chart":
        category = st.selectbox("Kategori", all_columns)
        value = st.selectbox("Nilai", numeric_columns)
        fig = px.pie(df, names=category, values=value, title="Pie Chart", template="plotly_white")
        st.plotly_chart(fig, use_container_width=True)

    elif chart_type == "Histogram":
        hist_column = st.selectbox("Kolom Numerik", numeric_columns)
        fig = px.histogram(df, x=hist_column, title="Histogram", template="plotly_white")
        st.plotly_chart(fig, use_container_width=True)

    elif chart_type == "Line Chart":
        x_axis = st.selectbox("Sumbu X", all_columns)
        y_axis = st.selectbox("Sumbu Y", numeric_columns)
        fig = px.line(df, x=x_axis, y=y_axis, title="Line Chart", template="plotly_white")
        st.plotly_chart(fig, use_container_width=True)

    elif chart_type == "Scatter Plot":
        x_axis = st.selectbox("Sumbu X", numeric_columns)
        y_axis = st.selectbox("Sumbu Y", numeric_columns)
        color = st.selectbox("Warna (opsional)", [None] + all_columns)
        fig = px.scatter(
            df,
            x=x_axis,
            y=y_axis,
            color=color if color != "None" else None,
            title="Scatter Plot",
            template="plotly_white",
        )
        st.plotly_chart(fig, use_container_width=True)


# Fungsi untuk menampilkan analisis ekonomi seperti korelasi dan regresi.
def economic_analysis(df):
    st.subheader("Analisis Ekonomi")
    if df is None or df.empty:
        st.info("Unggah data terlebih dahulu untuk melakukan analisis.")
        return

    numeric_columns = df.select_dtypes(include=["number"]).columns.tolist()
    if len(numeric_columns) < 2:
        st.warning("Data harus memiliki setidaknya dua kolom numerik untuk analisis ekonomi.")
        return

    st.markdown("**Korelasi**")
    corr = df[numeric_columns].corr()
    st.dataframe(corr.style.background_gradient(cmap="Blues"))
    fig_corr = px.imshow(corr, text_auto=True, title="Matriks Korelasi", template="plotly_white")
    st.plotly_chart(fig_corr, use_container_width=True)

    st.markdown("**Regresi Linear**")
    dep_var = st.selectbox("Variabel Dependen", numeric_columns, index=1)
    indep_vars = st.multiselect("Variabel Independen", [c for c in numeric_columns if c != dep_var], default=[c for c in numeric_columns if c != dep_var][:2])
    if indep_vars:
        formula = f"{dep_var} ~ " + " + ".join(indep_vars)
        try:
            model = smf.ols(formula=formula, data=df).fit()
            st.write(f"### Model: {formula}")
            st.write(model.summary())
        except Exception as e:
            st.error(f"Gagal membangun model regresi: {e}")

    st.markdown("**Trend Analysis**")
    if st.checkbox("Gunakan Analisis Tren" ):
        trend_var = st.selectbox("Variabel untuk Trend", numeric_columns, index=0)
        df_trend = df[[trend_var]].copy()
        df_trend["Index"] = range(len(df_trend))
        X = sm.add_constant(df_trend["Index"])
        y = df_trend[trend_var]
        trend_model = sm.OLS(y, X).fit()
        df_trend["Trend"] = trend_model.predict(X)
        fig_trend = px.line(df_trend, x="Index", y=[trend_var, "Trend"], title="Trend Analysis", template="plotly_white")
        st.plotly_chart(fig_trend, use_container_width=True)
        st.write("#### Koefisien Trend")
        st.write(trend_model.params)


# Halaman dashboard utama.
if menu == "Dashboard Utama":
    st.header("Dashboard Utama")
    st.markdown(
        "Dashboard ini memberikan ringkasan data secara cepat, termasuk statistik utama dan gambaran umum dataset."
    )
    uploaded_file = st.file_uploader("Unggah file CSV atau Excel", type=["csv", "xls", "xlsx"], key="dashboard_upload")
    df = load_data(uploaded_file)
    if df is None:
        st.info("Jika tidak ada file, aplikasi akan menggunakan contoh data ekonomi sederhana.")
        df = pd.DataFrame(
            {
                "Tahun": [2018, 2019, 2020, 2021, 2022],
                "GDP": [1100, 1150, 1080, 1200, 1250],
                "Inflasi": [3.2, 3.5, 1.8, 2.5, 2.9],
                "Pengangguran": [5.1, 5.0, 6.0, 5.5, 5.2],
                "Investasi": [250, 270, 260, 285, 300],
            }
        )

    summary_statistics(df)
    st.markdown("---")
    stata_df = stata_style_table(df)
    st.subheader("Output Statistik Bergaya Stata")
    st.dataframe(stata_df.style.format({"Mean": "{:.2f}", "Std Dev": "{:.2f}", "Min": "{:.2f}", "Max": "{:.2f}"}))

# Halaman import data khusus.
elif menu == "Import Data":
    st.header("Import Data")
    st.markdown("Unggah data ekonomi atau keuangan dalam format CSV atau Excel.")
    uploaded_file = st.file_uploader("Pilih file CSV atau Excel", type=["csv", "xls", "xlsx"], key="import_upload")
    df = load_data(uploaded_file)
    if df is not None:
        st.success("File berhasil diunggah.")
        st.write(df.head())
        st.write(f"**Jumlah baris:** {len(df)}, **Jumlah kolom:** {len(df.columns)}")
    else:
        st.info("Unggah file yang valid untuk melihat isi data di sini.")

# Halaman Data Viewer dengan filter, sorting, dan pencarian.
elif menu == "Data Viewer":
    st.header("Data Viewer")
    st.markdown("Lihat tabel data dengan kemampuan filter, sorting, dan pencarian.")
    uploaded_file = st.file_uploader("Unggah file CSV atau Excel", type=["csv", "xls", "xlsx"], key="viewer_upload")
    df = load_data(uploaded_file)
    if df is None:
        st.warning("Unggah data terlebih dahulu untuk menggunakan fitur Data Viewer.")
    else:
        # Pencarian teks di semua kolom.
        search_text = st.text_input("Pencarian data")
        if search_text:
            mask = df.apply(lambda row: row.astype(str).str.contains(search_text, case=False, na=False).any(), axis=1)
            df = df[mask]

        st.write(f"Menampilkan **{len(df)}** baris setelah filter/pencarian.")

        # Filter kolom tertentu oleh nilai.
        filter_cols = st.multiselect("Pilih kolom untuk difilter", df.columns.tolist())
        for col in filter_cols:
            if pd.api.types.is_numeric_dtype(df[col]):
                min_val, max_val = st.sidebar.slider(
                    f"Rentang {col}",
                    float(df[col].min()),
                    float(df[col].max()),
                    (float(df[col].min()), float(df[col].max())),
                )
                df = df[df[col].between(min_val, max_val)]
            else:
                unique_values = df[col].dropna().unique().tolist()
                selected_values = st.sidebar.multiselect(f"Nilai {col}", unique_values, default=unique_values)
                df = df[df[col].isin(selected_values)]

        # Sorting kolom.
        sort_col = st.selectbox("Sort by", [None] + df.columns.tolist())
        if sort_col:
            ascending = st.radio("Urutan", ["Ascending", "Descending"]) == "Ascending"
            df = df.sort_values(sort_col, ascending=ascending)

        st.dataframe(df)

# Statistik deskriptif untuk dataset.
elif menu == "Statistik Deskriptif":
    st.header("Statistik Deskriptif")
    st.markdown("Hitung mean, median, min, max, dan standar deviasi dari data numerik.")
    uploaded_file = st.file_uploader("Unggah data CSV atau Excel", type=["csv", "xls", "xlsx"], key="stats_upload")
    df = load_data(uploaded_file)
    if df is None:
        st.warning("Unggah file untuk menampilkan statistik deskriptif.")
    else:
        numeric_df = df.select_dtypes(include=["number"])
        if numeric_df.empty:
            st.warning("Tidak ada kolom numerik untuk dianalisis.")
        else:
            stats_df = pd.DataFrame(
                {
                    "Variable": numeric_df.columns,
                    "Mean": numeric_df.mean().values,
                    "Median": numeric_df.median().values,
                    "Min": numeric_df.min().values,
                    "Max": numeric_df.max().values,
                    "Std Dev": numeric_df.std().values,
                }
            )
            st.dataframe(stats_df.style.format({"Mean": "{:.2f}", "Median": "{:.2f}", "Min": "{:.2f}", "Max": "{:.2f}", "Std Dev": "{:.2f}"}))

# Visualisasi data interaktif.
elif menu == "Visualisasi Data":
    st.header("Visualisasi Data")
    st.markdown("Buat grafik bar, pie, histogram, line, dan scatter secara interaktif.")
    uploaded_file = st.file_uploader("Unggah data CSV atau Excel", type=["csv", "xls", "xlsx"], key="vis_upload")
    df = load_data(uploaded_file)
    render_plot(df)

# Analisis ekonomi: korelasi, regresi, trend.
elif menu == "Analisis Ekonomi":
    st.header("Analisis Ekonomi")
    st.markdown("Analisis korelasi, regresi linear, dan tren data ekonomi.")
    uploaded_file = st.file_uploader("Unggah data CSV atau Excel", type=["csv", "xls", "xlsx"], key="econ_upload")
    df = load_data(uploaded_file)
    economic_analysis(df)

# Export data ke CSV, Excel, atau PDF.
elif menu == "Export":
    st.header("Export Data")
    st.markdown("Ekspor dataset yang sudah diunggah ke format CSV, Excel, atau PDF.")
    uploaded_file = st.file_uploader("Unggah data CSV atau Excel", type=["csv", "xls", "xlsx"], key="export_upload")
    df = load_data(uploaded_file)
    if df is None:
        st.warning("Unggah data terlebih dahulu untuk mengaktifkan fitur export.")
    else:
        csv_data = convert_df_to_csv(df)
        excel_data = convert_df_to_excel(df)

        st.download_button(
            label="Download CSV",
            data=csv_data,
            file_name="data_export.csv",
            mime="text/csv",
        )
        st.download_button(
            label="Download Excel",
            data=excel_data,
            file_name="data_export.xlsx",
            mime="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        )

        stata_df = stata_style_table(df)
        pdf_data = convert_stats_to_pdf(stata_df, title="Laporan Statistik SMART FINANCE")
        if pdf_data is not None:
            st.download_button(
                label="Download PDF Statistik",
                data=pdf_data,
                file_name="report_statistik.pdf",
                mime="application/pdf",
            )
        else:
            st.info("Untuk export PDF, install library tambahan: reportlab")

# Tambahkan komentar singkat di footer jika diperlukan.
st.markdown("---")
st.caption("SMART FINANCE ANALYTICS DASHBOARD - Dibuat untuk mahasiswa ekonomi dengan antarmuka yang lebih modern dan mudah digunakan.")
