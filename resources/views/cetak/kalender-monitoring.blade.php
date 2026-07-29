<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Kalender Monitoring Agenda</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .header h2 {
            margin: 0;
            font-size: 13pt;
            color: #1e3a8a;
            text-transform: uppercase;
        }
        .header h3 {
            margin: 3px 0 0 0;
            font-size: 10pt;
            color: #475569;
            font-weight: normal;
        }
        .legend {
            margin-bottom: 10px;
            font-size: 8pt;
        }
        .legend-item {
            display: inline-block;
            margin-right: 15px;
        }
        .legend-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 4px;
        }
        .calendar-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .calendar-table th {
            background-color: #1e3a8a;
            color: #ffffff;
            padding: 6px;
            font-size: 8.5pt;
            text-align: center;
            border: 1px solid #1e3a8a;
        }
        .calendar-table td {
            border: 1px solid #cbd5e1;
            height: 28mm;
            vertical-align: top;
            padding: 4px;
            background-color: #fafafa;
        }
        .date-num {
            font-weight: bold;
            font-size: 9pt;
            color: #0f172a;
            margin-bottom: 4px;
            display: block;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 2px;
        }
        .event-card {
            background-color: #ffffff;
            border-left: 3px solid #1e3a8a;
            padding: 3px 4px;
            margin-bottom: 3px;
            border-radius: 2px;
            font-size: 7pt;
            line-height: 1.2;
        }
        .event-pka { border-left-color: #8b5cf6; background-color: #f5f3ff; }
        .event-pp { border-left-color: #ec4899; background-color: #fdf2f8; }
        .event-pha { border-left-color: #10b981; background-color: #ecfdf5; }
        .event-khp { border-left-color: #06b6d4; background-color: #ecfeff; }
        .event-time { font-weight: bold; color: #334155; }
        .event-title { font-weight: bold; color: #0f172a; display: block; }
    </style>
</head>
<body>

    <div class="header">
        <h2>KALENDER MONITORING AGENDA & KEGIATAN DINAS PPPA</h2>
        <h3>Periode Bulan: {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->locale('id')->translatedFormat('F Y') }} — SIMANDIS-PPPA</h3>
    </div>

    <div class="legend">
        <strong>Kategori Bidang:</strong>
        <span class="legend-item"><span class="legend-dot" style="background:#8b5cf6;"></span>Bidang PKA</span>
        <span class="legend-item"><span class="legend-dot" style="background:#ec4899;"></span>Bidang PP</span>
        <span class="legend-item"><span class="legend-dot" style="background:#10b981;"></span>Bidang PHA</span>
        <span class="legend-item"><span class="legend-dot" style="background:#06b6d4;"></span>Bidang KHP</span>
    </div>

    @php
        $daysInMonth = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->daysInMonth;
        $startOfWeek = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->dayOfWeekIso; // 1 (Senin) - 7 (Minggu)
        $currentDay = 1;
        $bidangClasses = [
            1 => 'event-pka',
            2 => 'event-pp',
            3 => 'event-pha',
            4 => 'event-khp',
        ];
    @endphp

    <table class="calendar-table">
        <thead>
            <tr>
                <th>SENIN</th>
                <th>SELASA</th>
                <th>RABU</th>
                <th>KAMIS</th>
                <th>JUMAT</th>
                <th>SABTU</th>
                <th>MINGGU</th>
            </tr>
        </thead>
        <tbody>
            @while ($currentDay <= $daysInMonth)
                <tr>
                    @for ($i = 1; $i <= 7; $i++)
                        @if (($currentDay == 1 && $i < $startOfWeek) || $currentDay > $daysInMonth)
                            <td style="background-color: #f1f5f9;"></td>
                        @else
                            <td>
                                <span class="date-num">{{ $currentDay }}</span>
                                @if (isset($agendas[$currentDay]))
                                    @foreach ($agendas[$currentDay] as $item)
                                        <div class="event-card {{ $bidangClasses[$item->bidang_id] ?? '' }}">
                                            <span class="event-time">{{ \Carbon\Carbon::parse($item->tgl_surat)->format('H:i') }} WITA</span>
                                            <span class="event-title">{{ $item->perihal }}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </td>
                            @php $currentDay++; @endphp
                        @endif
                    @endfor
                </tr>
            @endwhile
        </tbody>
    </table>

</body>
</html>