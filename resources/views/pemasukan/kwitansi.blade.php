<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kwitansi Pembayaran #{{ str_pad($pemasukan->id, 5, '0', STR_PAD_LEFT) }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
            color: #1f2937;
            background: #ffffff;
            margin: 0;
            padding: 40px;
            font-size: 14px;
            line-height: 1.5;
        }
        .receipt-card {
            max-width: 680px;
            margin: 0 auto;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #f3f4f6;
            padding-bottom: 24px;
            margin-bottom: 30px;
        }
        .brand h2 {
            margin: 0;
            color: #00288e;
            font-size: 24px;
            font-weight: 800;
        }
        .brand p {
            margin: 4px 0 0;
            color: #6b7280;
            font-size: 12px;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-title h1 {
            margin: 0;
            font-size: 20px;
            color: #111827;
            font-weight: 700;
        }
        .invoice-title p {
            margin: 4px 0 0;
            font-family: monospace;
            color: #4b5563;
        }
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        .details-box h6 {
            margin: 0 0 6px 0;
            text-transform: uppercase;
            font-size: 11px;
            color: #9ca3af;
            letter-spacing: 0.05em;
        }
        .details-box p {
            margin: 0;
            font-weight: 600;
            color: #374151;
        }
        .details-box .val-muted {
            font-weight: 400;
            color: #6b7280;
            font-size: 13px;
        }
        .table-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        .table-items th {
            text-align: left;
            padding: 12px 16px;
            background: #f9fafb;
            color: #4b5563;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            border-bottom: 1px solid #e5e7eb;
        }
        .table-items td {
            padding: 16px;
            border-bottom: 1px solid #f3f4f6;
        }
        .table-items .item-desc {
            font-weight: 600;
            color: #111827;
        }
        .table-items .item-subtext {
            font-size: 12px;
            color: #6b7280;
            margin-top: 2px;
        }
        .total-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 40px;
        }
        .total-box {
            width: 250px;
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .total-row:last-child {
            margin-bottom: 0;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
        }
        .total-row span {
            color: #6b7280;
            font-size: 13px;
        }
        .total-row strong {
            color: #0f172a;
            font-size: 16px;
        }
        .footer {
            text-align: center;
            color: #9ca3af;
            font-size: 12px;
            border-top: 1px solid #f3f4f6;
            padding-top: 20px;
        }
        .btn-print-action {
            display: inline-flex;
            align-items: center;
            background: #00288e;
            color: white;
            border: 0;
            padding: 10px 20px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            margin-bottom: 20px;
            text-decoration: none;
        }
        .btn-print-action:hover {
            background: #1e40af;
        }
        @media print {
            body {
                padding: 0;
            }
            .receipt-card {
                border: 0;
                box-shadow: none;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div style="max-width: 680px; margin: 0 auto;" class="no-print">
        <button onclick="window.print()" class="btn-print-action">Cetak Kwitansi</button>
    </div>

    <div class="receipt-card">
        <div class="header">
            <div class="brand">
                <h2>Kost AJ Lanraki</h2>
                <p>Jalan Lanraki, Makassar</p>
                <p>WhatsApp: +62 823-4567-8910</p>
            </div>
            <div class="invoice-title">
                <h1>KWITANSI PEMBAYARAN</h1>
                <p>#REC-{{ str_pad($pemasukan->id, 5, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>

        <div class="details-grid">
            <div class="details-box">
                <h6>Diterima Dari:</h6>
                <p>{{ optional($pemasukan->penghuni)->nama ?? 'Pemasukan lainnya' }}</p>
                <div class="val-muted">
                    @if(optional($pemasukan->penghuni)->no_hp)
                        HP: {{ $pemasukan->penghuni->no_hp }}
                    @endif
                </div>
            </div>
            <div class="details-box">
                <h6>Detail Pembayaran:</h6>
                <p>Tanggal: {{ $pemasukan->tanggal->locale('id')->translatedFormat('d F Y') }}</p>
                <div class="val-muted">Metode: Tunai / Transfer</div>
            </div>
        </div>

        <table class="table-items">
            <thead>
                <tr>
                    <th>Deskripsi Layanan / Kategori</th>
                    <th>Kamar</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="item-desc">
                            {{ $pemasukan->kategori === 'pembayaran_kost' ? 'Sewa Kamar Bulanan' : 'Pemasukan Lainnya' }}
                        </div>
                        <div class="item-subtext">{{ $pemasukan->keterangan }}</div>
                    </td>
                    <td class="fw-semibold">
                        {{ optional(optional($pemasukan->penghuni)->kamar)->nomor_kamar ? 'Kamar ' . $pemasukan->penghuni->kamar->nomor_kamar : '-' }}
                    </td>
                    <td style="text-align: right; font-weight: 700; color: #111827;">
                        {{ $pemasukan->formatted_jumlah }}
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="total-section">
            <div class="total-box">
                <div class="total-row">
                    <span>Subtotal</span>
                    <span>{{ $pemasukan->formatted_jumlah }}</span>
                </div>
                <div class="total-row">
                    <span>Biaya Admin</span>
                    <span>Rp 0</span>
                </div>
                <div class="total-row">
                    <span><strong>Total Bayar</strong></span>
                    <strong>{{ $pemasukan->formatted_jumlah }}</strong>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>Terima kasih atas pembayaran Anda. Tetap simpan kwitansi ini sebagai bukti pembayaran yang sah.</p>
            <small style="margin-top: 10px; display: block;">Kost AJ Lanraki Management System</small>
        </div>
    </div>

    <script>
        // Auto trigger print when loaded
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
