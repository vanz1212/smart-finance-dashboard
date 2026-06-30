@extends('layouts.app')

@section('title', __('targets.create_title'))
@section('body-class', 'module-page')

@section('content')
    <style>
        .form-workspace {
            margin: -24px;
            min-height: calc(100vh - 1px);
            padding: 80px 24px 56px;
            color: #f8fafc;
            background:
                linear-gradient(180deg, rgba(5, 12, 15, 0.76), rgba(5, 12, 15, 0.97));
        }

        .workspace-inner {
            width: min(900px, 100%);
            margin: 0 auto;
        }

        .form-panel {
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 14px;
            background: linear-gradient(180deg, rgba(13, 47, 51, 0.78), rgba(6, 24, 32, 0.84));
            box-shadow: 0 28px 80px rgba(0, 0, 0, 0.34);
            backdrop-filter: blur(16px);
            padding: 32px;
        }

        .form-header {
            margin-bottom: 28px;
        }

        .form-header h1 {
            margin: 0;
            font-size: 2rem;
        }

        .form-header p {
            margin: 8px 0 0;
            color: rgba(248, 250, 252, 0.66);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: rgba(248, 250, 252, 0.72);
            font-size: 0.84rem;
            font-weight: 800;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.06);
            color: #ffffff;
            font: inherit;
            font-size: 0.9rem;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: 3px solid rgba(20, 184, 166, 0.18);
            border-color: #14b8a6;
        }

        /* Enhanced Select UI */
        .form-group select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='rgba(255,255,255,0.7)' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 14px center;
            background-size: 16px;
            padding-right: 40px;
            cursor: pointer;
        }

        .form-group select option {
            background-color: var(--bg-panel, #071b20);
            color: #ffffff;
            padding: 12px;
        }

        [data-theme="light"] .form-group select {
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='rgba(0,0,0,0.5)' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            color: var(--text-main);
        }

        [data-theme="light"] .form-group select option {
            background-color: #ffffff;
            color: #0f172a;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .btn-submit {
            flex: 1;
            padding: 14px 24px;
            border: none;
            border-radius: 999px;
            background: #f3c969;
            color: #052e2b;
            font-weight: 900;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-submit:hover {
            background: #f0c041;
            transform: translateY(-2px);
        }

        .btn-cancel {
            padding: 14px 24px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.06);
            color: rgba(248, 250, 252, 0.78);
            text-decoration: none;
            font-weight: 900;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-cancel:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .form-info {
            padding: 14px;
            background: rgba(20, 184, 166, 0.1);
            border: 1px solid rgba(20, 184, 166, 0.2);
            border-radius: 8px;
            color: rgba(20, 184, 166, 0.9);
            font-size: 0.85rem;
            margin-bottom: 20px;
        }

        .money-field {
            position: relative;
        }

        .money-prefix {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(248, 250, 252, 0.74);
            font-size: 0.88rem;
            font-weight: 800;
            pointer-events: none;
        }

        .money-field input {
            padding-left: 42px;
        }

        html, body {
            width: 100%;
            min-height: 100%;
            margin: 0;
            overflow-x: hidden;
            background:
                radial-gradient(circle at 50% 0%, rgba(99, 102, 241, .18), transparent 28%),
                linear-gradient(180deg, rgba(11, 17, 32, .9), rgba(11, 17, 32, .98)) !important;
            color: #f8fafc;
        }

        [data-theme="light"],
        [data-theme="light"] body {
            background: var(--bg-primary) !important;
            color: var(--text-main) !important;
        }

        body::before {
            opacity: .16 !important;
        }

        .page-shell, .container {
            width: 100% !important;
            max-width: none !important;
            margin: 0 !important;
            box-sizing: border-box;
        }

        .page-shell, .container {
            padding-left: clamp(18px, 4vw, 56px) !important;
            padding-right: clamp(18px, 4vw, 56px) !important;
        }

        header, .topbar, .navbar, nav {
            max-width: none !important;
            width: 100% !important;
            box-sizing: border-box;
        }
    </style>

    <main class="form-workspace">
        <div class="workspace-inner">
            <div class="form-panel">
                <div class="form-header">
                    <h1>Target Finansial Baru</h1>
                    <p>Tetapkan target finansial dengan nominal, tenggat waktu, dan sistem tracking otomatis.</p>
                </div>

                <form action="{{ route('targets.store') }}" method="POST">
                    @csrf

                    <div class="form-info">
                        💡 Sistem akan otomatis menghitung rekomendasi setoran bulanan berdasarkan target nominal dan tenggat waktu.
                    </div>

                    <div class="form-group">
                        <label for="name">Nama Target <span style="color: #fb7185;">*</span></label>
                        <input type="text" id="name" name="name" placeholder="contoh: Liburan ke Bali, Beli Motor" value="{{ old('name') }}" required>
                        @error('name')
                            <span style="color: #fb7185; font-size: 0.8rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi (Opsional)</label>
                        <textarea id="description" name="description" placeholder="Jelaskan tujuan atau detail target ini...">{{ old('description') }}</textarea>
                        @error('description')
                            <span style="color: #fb7185; font-size: 0.8rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="category">Kategori <span style="color: #fb7185;">*</span></label>
                            <select id="category" name="category" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $key => $label)
                                    <option value="{{ $key }}" {{ old('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <span style="color: #fb7185; font-size: 0.8rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="priority">Prioritas</label>
                            <select id="priority" name="priority">
                                <option value="">Pilih Prioritas</option>
                                <option value="1" {{ old('priority') === '1' ? 'selected' : '' }}>🔴 Tinggi</option>
                                <option value="2" {{ old('priority') === '2' ? 'selected' : '' }}>🟡 Sedang</option>
                                <option value="3" {{ old('priority') === '3' ? 'selected' : '' }}>🟢 Rendah</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="target_amount">Target Nominal <span style="color: #fb7185;">*</span></label>
                            <div class="money-field">
                                <span class="money-prefix">Rp</span>
                                <input type="text" id="target_amount" name="target_amount" placeholder="0" data-rupiah-input value="{{ old('target_amount') }}" required>
                            </div>
                            @error('target_amount')
                                <span style="color: #fb7185; font-size: 0.8rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="current_amount">Saldo Awal (Opsional)</label>
                            <div class="money-field">
                                <span class="money-prefix">Rp</span>
                                <input type="text" id="current_amount" name="current_amount" placeholder="0" data-rupiah-input value="{{ old('current_amount') }}">
                            </div>
                            @error('current_amount')
                                <span style="color: #fb7185; font-size: 0.8rem;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="target_date">Tenggat Waktu <span style="color: #fb7185;">*</span></label>
                        <input type="date" id="target_date" name="target_date" value="{{ old('target_date') }}" required>
                        @error('target_date')
                            <span style="color: #fb7185; font-size: 0.8rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-submit">Buat Target</button>
                        <a href="{{ route('targets.index') }}" class="btn-cancel">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var fields = document.querySelectorAll('[data-rupiah-input]');

            function formatRupiah(value) {
                var digits = String(value || '').replace(/[^0-9]/g, '');
                if (!digits) return '';
                return digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            function normalizeField(input) {
                var digits = input.value.replace(/[^0-9]/g, '');
                if (digits) {
                    input.value = 'Rp ' + formatRupiah(digits);
                } else {
                    input.value = '';
                }
            }

            fields.forEach(function (field) {
                normalizeField(field);
                field.addEventListener('input', function () {
                    normalizeField(field);
                });
                field.form && field.form.addEventListener('submit', function () {
                    field.value = field.value.replace(/[^0-9]/g, '');
                });
            });
        });
    </script>
    @include('partials.module-shell-styles')
@endsection
