<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>RKT BEM 2026</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #1f2937;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #667eea;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
            color: #6b7280;
        }
        .table-container {
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        th {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 8px;
            text-align: left;
            font-weight: 600;
            color: #374151;
        }
        td {
            border: 1px solid #e5e7eb;
            padding: 8px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .status {
            padding: 3px 8px;
            border-radius: 12px;
            display: inline-block;
            font-weight: 600;
            font-size: 10px;
        }
        .status-belum {
            background-color: #fef3c7;
            color: #78350f;
        }
        .status-berjalan {
            background-color: #dbeafe;
            color: #0c2340;
        }
        .status-selesai {
            background-color: #d1fae5;
            color: #065f46;
        }
        .divisi {
            padding: 3px 8px;
            border-radius: 12px;
            display: inline-block;
            font-weight: 600;
            font-size: 10px;
        }
        .progress-bar {
            width: 100%;
            background-color: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 3px;
        }
        .progress-fill {
            height: 6px;
        }
        .footer {
            margin-top: 40px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 40px;
            text-align: center;
            font-size: 11px;
        }
        .footer-item {
            border-top: 1px solid #000;
            padding-top: 40px;
            height: 50px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>RENCANA KERJA TAHUNAN (RKT) BEM 2026</h1>
        <h1>Badan Eksekutif Mahasiswa</h1>
        <h1>UBSI Kab. Karawang</h1>
        <h1>Kabinet Swarnadipa Pratibhana</h1>
        <h1>Tahun Akademik 2025/2026</h1>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 20%;">Program/Kegiatan</th>
                    <th style="width: 12%;">Divisi</th>
                    <th style="width: 15%;">Periode</th>
                    <th style="width: 10%;">PIC</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 10%;">Anggaran (Rp)</th>
                    <th style="width: 8%;">Progress</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rkt as $item)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $item->nama_program }}</strong>
                        @if($item->lokasi)
                        <br><span style="color: #6b7280; font-size: 10px;">Lokasi: {{ $item->lokasi }}</span>
                        @endif
                    </td>
                    <td>
                        <div class="divisi" style="background-color: {{ $item->divisi_color }}22; color: {{ $item->divisi_color }};">
                            {{ str_replace('Dept. ', '', $item->divisi_label) }}
                        </div>
                    </td>
                    <td style="font-size: 10px;">
                        {{ $item->tanggal_mulai->format('d/m/Y') }}<br>
                        s.d {{ $item->tanggal_selesai->format('d/m/Y') }}
                    </td>
                    <td>{{ $item->pic }}</td>
                    <td>
                        <div class="status {{ 'status-' . $item->status }}">
                            {{ $item->status_label }}
                        </div>
                    </td>
                    <td style="text-align: right;">
                        {{ number_format($item->anggaran, 0, ',', '.') }}
                    </td>
                    <td style="text-align: center;">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $item->progress_percent }}%; background-color: {{ $item->status_color }};"></div>
                        </div>
                        <span style="font-size: 10px;">{{ $item->progress_percent }}%</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #6b7280;">Tidak ada data program</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 30px; font-size: 11px; border-top: 1px solid #e5e7eb; padding-top: 20px;">
        <p style="margin: 5px 0;">
            <strong>Catatan:</strong> Dokumen ini adalah ringkasan dari Rencana Kerja Tahunan BEM 2026.
            Diharapkan semua program dan kegiatan dapat dilaksanakan sesuai dengan jadwal dan anggaran yang telah ditetapkan.
        </p>
    </div>

    <div class="footer">
        <div class="footer-item">
            <div style="margin-bottom: 50px;">Ketua BEM</div>
            <div style="font-weight: 600;">___________________</div>
        </div>
        <div class="footer-item">
            <div style="margin-bottom: 50px;">Wakil Ketua</div>
            <div style="font-weight: 600;">___________________</div>
        </div>
        <div class="footer-item">
            <div style="margin-bottom: 50px;">Kemahasiswaan</div>
            <div style="font-weight: 600;">___________________</div>
        </div>
    </div>
</body>
</html>
