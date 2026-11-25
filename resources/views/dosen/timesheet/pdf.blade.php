<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Timesheet Asisten Praktikum</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1f2937;
            margin: 0;
            padding: 18px 24px;
        }
        .header {
            border-bottom: 2px solid #1E5EFF;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .brand {
            font-size: 10px;
            font-weight: bold;
            color: #1E5EFF;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            color: #111827;
            margin: 4px 0;
        }
        .subtitle {
            font-size: 10px;
            color: #6b7280;
        }
        .summary-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px 0;
            margin: 0 -8px 16px -8px;
        }
        .summary-card {
            border: 1px solid #e5e7eb;
            background-color: #f9fafb;
            border-radius: 6px;
            padding: 10px 12px;
            width: 20%;
            vertical-align: top;
        }
        .summary-label {
            font-size: 9px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: bold;
        }
        .summary-value {
            font-size: 15px;
            font-weight: bold;
            color: #111827;
            margin-top: 4px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        .data-table th {
            background-color: #EFF5FF;
            color: #1E5EFF;
            font-weight: bold;
            text-align: left;
            padding: 8px 10px;
            border: 1px solid #dbeafe;
            font-size: 10px;
        }
        .data-table td {
            padding: 8px 10px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
            font-size: 10px;
        }
        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-approved { background-color: #E8F8F0; color: #059669; }
        .badge-rejected { background-color: #FEE2E2; color: #DC2626; }
        .badge-pending  { background-color: #FEF3C7; color: #D97706; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .muted { color: #6b7280; font-size: 9px; margin-top: 2px; }
        .footer {
            margin-top: 24px;
            font-size: 10px;
            color: #6b7280;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">ASISTIN · Portal Dosen Universitas Pertamina</div>
        <div class="title">Laporan Rekapitulasi Timesheet Asisten Praktikum</div>
        <div class="subtitle">
            Dicetak pada: {{ $generatedAt }}
            @if(!empty($filters['status'])) · Status: {{ ucfirst($filters['status']) }} @endif
            @if(!empty($filters['matakuliah'])) · Mata Kuliah: {{ $filters['matakuliah'] }} @endif
            @if(!empty($filters['q'])) · Pencarian: "{{ $filters['q'] }}" @endif
        </div>
    </div>

    <table class="summary-table">
        <tr>
            <td class="summary-card">
                <div class="summary-label">Total Pengajuan</div>
                <div class="summary-value">{{ $statistik['total'] }} Entri</div>
            </td>
            <td class="summary-card">
                <div class="summary-label">Disetujui</div>
                <div class="summary-value">{{ $statistik['disetujui'] }} Entri</div>
            </td>
            <td class="summary-card">
                <div class="summary-label">Menunggu Review</div>
                <div class="summary-value">{{ $statistik['menunggu'] }} Entri</div>
            </td>
            <td class="summary-card">
                <div class="summary-label">Jam Disetujui</div>
                <div class="summary-value">{{ number_format($statistik['total_jam'], 1, ',', '.') }} Jam</div>
            </td>
            <td class="summary-card">
                <div class="summary-label">Estimasi Honor</div>
                <div class="summary-value">Rp {{ number_format($statistik['estimasi_honor'], 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;" class="text-center">No</th>
                <th style="width: 17%;">Asisten</th>
                <th style="width: 18%;">Mata Kuliah</th>
                <th style="width: 11%;">Tanggal</th>
                <th style="width: 26%;">Aktivitas &amp; Uraian</th>
                <th style="width: 8%;" class="text-right">Jam</th>
                <th style="width: 8%;" class="text-center">Status</th>
                <th style="width: 8%;" class="text-right">Honor</th>
            </tr>
        </thead>
        <tbody>
            @forelse($entries as $index => $entry)
                @php
                    $isApproved = $entry->status_approval === 'approved';
                    $isRejected = $entry->status_approval === 'rejected';
                    $honor = $isApproved ? ((float) $entry->jumlah_jam * 35000) : 0;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $entry->asisten?->name ?? 'Mahasiswa #' . $entry->asisten_id }}</strong>
                        <div class="muted">NIM: {{ $entry->asisten?->nim ?? '12' . str_pad((string) $entry->asisten_id, 7, '0', STR_PAD_LEFT) }}</div>
                    </td>
                    <td>{{ $entry->mata_kuliah ?? '-' }}</td>
                    <td>{{ $entry->tanggal?->translatedFormat('d M Y') ?? '-' }}</td>
                    <td>
                        <strong>{{ $entry->aktivitas ?: 'Asistensi Praktikum' }}</strong>
                        <div class="muted">{{ $entry->uraian_pekerjaan }}</div>
                        @if($isRejected && $entry->catatan_revisi)
                            <div class="muted" style="color: #DC2626;">Catatan: {{ $entry->catatan_revisi }}</div>
                        @endif
                    </td>
                    <td class="text-right">{{ number_format((float) $entry->jumlah_jam, 1, ',', '.') }}</td>
                    <td class="text-center">
                        @if($isApproved)
                            <span class="badge badge-approved">Disetujui</span>
                        @elseif($isRejected)
                            <span class="badge badge-rejected">Ditolak</span>
                        @else
                            <span class="badge badge-pending">Menunggu</span>
                        @endif
                    </td>
                    <td class="text-right">
                        {{ $isApproved ? 'Rp ' . number_format($honor, 0, ',', '.') : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 18px; color: #6b7280;">
                        Tidak ada data timesheet untuk filter yang dipilih.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        SK Dekanat 2026: Tarif honorarium asistensi praktikum Rp 35.000 / Jam · Dokumen dihasilkan otomatis oleh sistem ASISTIN.
    </div>
</body>
</html>
