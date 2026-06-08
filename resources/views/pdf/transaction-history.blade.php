<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Mutasi Rekening</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #1a1a2e;
            background: #fff;
        }

        /* ── HEADER ── */
        .header {
            background: #1d4ed8;
            color: #fff;
            padding: 24px 32px;
            margin-bottom: 24px;
        }
        .header h1 { font-size: 20px; font-weight: bold; letter-spacing: 0.5px; }
        .header p  { font-size: 11px; opacity: 0.85; margin-top: 2px; }
        .header-meta {
            margin-top: 12px;
            display: table;
            width: 100%;
        }
        .header-meta td { font-size: 11px; padding: 2px 0; }
        .header-meta .label { opacity: 0.75; width: 130px; }
        .header-meta .value { font-weight: bold; }

        /* ── SECTION TITLE ── */
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #1d4ed8;
            border-bottom: 2px solid #1d4ed8;
            padding-bottom: 4px;
            margin: 0 32px 12px 32px;
        }

        /* ── TABLE ── */
        .wrap { padding: 0 32px; }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        thead tr {
            background: #1d4ed8;
            color: #fff;
        }
        thead th {
            padding: 8px 10px;
            text-align: left;
            font-weight: bold;
        }
        tbody tr:nth-child(even) { background: #f0f5ff; }
        tbody tr:nth-child(odd)  { background: #ffffff; }
        tbody td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; }
        .text-right { text-align: right; }
        .text-center{ text-align: center; }

        /* ── BADGE STATUS ── */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-failed  { background: #fee2e2; color: #991b1b; }
        .badge-pending { background: #fef9c3; color: #713f12; }

        /* ── AMOUNT ── */
        .credit { color: #16a34a; font-weight: bold; }
        .debit  { color: #dc2626; font-weight: bold; }

        /* ── SUMMARY BOX ── */
        .summary {
            margin: 20px 32px 0 32px;
            display: table;
            width: calc(100% - 64px);
        }
        .summary-box {
            display: table-cell;
            width: 33.33%;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px 16px;
            text-align: center;
        }
        .summary-box + .summary-box { margin-left: 8px; }
        .summary-box .s-label { font-size: 10px; color: #64748b; }
        .summary-box .s-value { font-size: 15px; font-weight: bold; margin-top: 4px; }
        .summary-box .s-value.green { color: #16a34a; }
        .summary-box .s-value.red   { color: #dc2626; }

        /* ── FOOTER ── */
        .footer {
            margin-top: 32px;
            padding: 16px 32px;
            border-top: 1px solid #e2e8f0;
            font-size: 10px;
            color: #94a3b8;
            text-align: center;
        }

        /* ── EMPTY ── */
        .empty { text-align: center; padding: 24px; color: #94a3b8; font-style: italic; }
    </style>
</head>
<body>

{{-- ─── HEADER ─────────────────────────────────── --}}
<div class="header">
    <h1>💳 Digital Banking</h1>
    <p>Laporan Mutasi Rekening</p>

    <table class="header-meta">
        <tr>
            <td class="label">Nama Nasabah</td>
            <td class="value">: {{ $user->name }}</td>
            <td class="label">Tanggal Cetak</td>
            <td class="value">: {{ $generated_at }}</td>
        </tr>
        <tr>
            <td class="label">No. Rekening</td>
            <td class="value">: {{ $account->account_number }}</td>
            <td class="label">Saldo Saat Ini</td>
            <td class="value">: Rp {{ number_format($account->balance, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Email</td>
            <td class="value">: {{ $user->email }}</td>
            <td></td><td></td>
        </tr>
    </table>
</div>

{{-- ─── SUMMARY ─────────────────────────────────── --}}
@php
    $totalDeposit  = $transactions->where('type', 'deposit')->sum('amount');
    $totalKeluar   = $transactions->whereIn('type', ['withdraw', 'transfer'])->sum('amount');
    $totalTrx      = $transactions->count();
@endphp

<div class="summary">
    <div class="summary-box">
        <div class="s-label">Total Transaksi</div>
        <div class="s-value">{{ $totalTrx }}</div>
    </div>
    <div class="summary-box" style="margin-left:8px;">
        <div class="s-label">Total Uang Masuk</div>
        <div class="s-value green">Rp {{ number_format($totalDeposit, 0, ',', '.') }}</div>
    </div>
    <div class="summary-box" style="margin-left:8px;">
        <div class="s-label">Total Uang Keluar</div>
        <div class="s-value red">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</div>
    </div>
</div>

{{-- ─── TABLE ──────────────────────────────────── --}}
<div style="margin-top:24px;">
    <div class="section-title">Riwayat Transaksi</div>
    <div class="wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>Keterangan</th>
                    <th class="text-right">Jumlah</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $i => $trx)
                    @php
                        $isCredit = $trx->type === 'deposit' && $trx->receiver_id === $account->id;
                        $label = match($trx->type) {
                            'deposit'  => 'Setoran',
                            'withdraw' => 'Penarikan',
                            'transfer' => $trx->sender_id === $account->id ? 'Transfer Keluar' : 'Transfer Masuk',
                            default    => ucfirst($trx->type),
                        };
                        // Tentukan arah uang
                        $credit = ($trx->type === 'deposit')
                               || ($trx->type === 'transfer' && $trx->receiver_id === $account->id);
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $trx->created_at->format('d M Y') }}<br>
                            <span style="color:#94a3b8">{{ $trx->created_at->format('H:i') }}</span>
                        </td>
                        <td>{{ $label }}</td>
                        <td>
                            @if($trx->type === 'transfer')
                                @if($trx->sender_id === $account->id)
                                    ke {{ $trx->receiver?->account_number ?? '-' }}
                                @else
                                    dari {{ $trx->sender?->account_number ?? '-' }}
                                @endif
                            @elseif($trx->type === 'withdraw')
                                Pembayaran / Penarikan
                            @else
                                Setoran tunai
                            @endif
                        </td>
                        <td class="text-right {{ $credit ? 'credit' : 'debit' }}">
                            {{ $credit ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            <span class="badge badge-{{ $trx->status }}">
                                {{ ucfirst($trx->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty">Tidak ada riwayat transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ─── FOOTER ─────────────────────────────────── --}}
<div class="footer">
    Dokumen ini digenerate otomatis oleh sistem Digital Banking pada {{ $generated_at }}.<br>
    Dokumen ini sah tanpa tanda tangan dan stempel.
</div>

</body>
</html>
