<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px !important;
        }
        .logo {
            width: 80px;
            height: auto;
        }    
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #333;
            padding: 2px;
            text-align: center;
        }
        thead tr:first-child th {
            background-color: #555;
            color: #fff;
        }
        thead tr:nth-child(2) th {
            background-color: #ddd;
        }
        tfoot {
            font-weight: bold;
        }
        .text-left { text-align: left; }
        .bg-info { background-color: #ffffff; }
    </style>

    <!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="{{ $url }}">
<meta property="og:title" content="{{ $title}}">
<meta property="og:description" content="{{ $description}}">
<meta property="og:image" content="{{ $logo }}">
<meta property="og:image:width" content="300">
<meta property="og:image:height" content="300">	
<meta property="og:image:type" content="image/jpg">
<!-- Twitter -->
<meta property="twitter:card" content="{{ $logo}}">
<meta property="twitter:url" content="{{$url}}">
<meta property="twitter:title" content="{{$title}}">
<meta property="twitter:description" content="{{$description}}">
<meta property="twitter:image" content="{{$logo }}">

</head>
<body>

    <table width="100%" style="border: 0px solid #333;">
        <tr  style="border: 0px solid #333;">
            <td style="width: 80px; border: 0px solid #333;">
                <img src="{{ $logo }}" alt="Logo" style="width: 70px;">
            </td>
            <td style="text-align: center; border: 0px solid #333; ">
                <h4 style="margin: 0; font-size : 14px">{{$title}}</h4>
                <h4 style="margin: 0;">{{ $aplikasi_nama }}</h4>
                <p style="margin: 0;">Periode {{ $bulan_saja }} Tahun {{ $tahun }}</p>
            </td>
        </tr>
    </table> 
<table>
    <thead>
        <tr>
            <th colspan="3"></th>
            <th colspan="{{ count($kategori_header) }}">Kategori</th>
        </tr>
        <tr>
            <th>No</th>
            <th>Tgl</th>
            <th class="text-left">Keterangan</th>
            @foreach($kategori_header as $kategori)
                <th>{{ $kategori }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
        <tr>
            <td>{{ $item['no'] }}</td>
            <td>{{ $item['tgl'] }}</td>
            <td class="text-left">{!!  $item['uraian'] !!}</td>
            @foreach($kategori_header as $kategori)
                <td>
                    {{ isset($item[$kategori]) && $item[$kategori] > 0
                        ? number_format($item[$kategori], 0, ',', '.')
                        : '-' }}
                </td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3">Total per Kategori</td>
            @foreach($kategori_header as $kategori)
                <td>
                    {{ number_format($kategori_total[$kategori] ?? 0, 0, ',', '.') }}
                </td>
            @endforeach
        </tr>
        <tr>
            <td colspan="3">Total Seluruh</td>
            <td colspan="{{ count($kategori_header) }}" class="bg-info">
                <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong><br>
                <em>{{ $total_terbilang }}</em>
            </td>
        </tr>
    </tfoot>
</table>

<script>
    window.baseApiUrl = "{{ url('/api') }}";
</script>
</body>
</html>

