<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style>
        @page {
            margin: 0px;
        }

        body {
            margin: 0px;
        }

        td {
            padding: 10px;
        }

        .td-field strong {
            color: black;
        }

        .point>tr>td>p {
            padding: 5px;
        }

        .tr-head>td {
            padding-top: 0px;
            padding-bottom: 0px;
        }

        .field>p {
            color: #8C8D89;
        }

        .table-penumpang {
            margin-top: 25px;
            border-collapse: collapse;
            border: 1px solid black;
        }

        .table tr td {
            padding: 0px;
            padding-top: 5px;
        }

        .td-field {
            border: 1px solid black;
            border-right: none;
            border-left: none;
        }

        p,
        h1 {
            margin: 0;
            margin-top: 5px;
        }
    </style>
</head>

<body style=" font-family:'Nunito', sans-serif;">
    <div style="padding:0px 60px;">
        <table id="header" style="table-layout: auto; width: 100%;">
            <tr>
                <td style="width: 75px;">
                    <img src="{{ public_path('assets/Icon.png') }}" alt="Logo" style="width: 90px; height: 90px">
                </td>
                <td>
                    <p style="font-weight: bold; text-align: center; font-size: 36px">RAMA TRANZ</p>
                    <p style="text-align: center; font-size: 24px">PT. RASYA MANDIRI TRANZ</>
                </td>
            </tr>
        </table>
        <hr style="margin-bottom: 30px">
        <table style="width: 100%; table-layout: auto; padding: 0px">
            <tr class="tr-head">
                <td><strong>Surat Jalan</strong></td>
                <td><strong>No: 123123</strong></td>
                <td>Rute</td>
                <td>: {{$data->master_rute->kota_asal}} - {{$data->master_rute->kota_tujuan}}</td>
                <td>Supir</td>
                <td>: {{$data->master_supir->nama}}</td>
            </tr>
            <tr>
                <td>{{$data->master_mobil->type}}</td>
                <td></td>
                <td>Tanggal</td>
                <td>: {{$data->tanggal_berangkat}}</td>
                <td>Jam</td>
                <td>: {{$data->waktu_keberangkatan}}</td>
            </tr>
        </table>
        <table class="table-penumpang" style="table-layout: auto; width: 100%;">
            <thead>
                <tr>
                    <td class="td-field" style="width: 25%;">
                        <strong>Nama / Telp</strong>
                    </td>
                    <td class="td-field">
                        <strong>Titik Jemput</strong>
                    </td>
                    <td class="td-field">
                        <strong>Rute</strong>
                    </td>
                    <td class="td-field">
                        <strong>Harga</strong>
                    </td>
                    <td class="td-field">
                        <strong>BBM</strong>
                    </td>
                    <td class="td-field">
                        <strong>VC</strong>
                    </td>
                    <td class="td-field">
                        <strong>Status</strong>
                    </td>
                </tr>
            </thead>
            <tbody>
                <!-- todo: nanti di foreach -->
                @foreach ($data->pemesanan as $pesanan )
                @foreach ($pesanan->penumpang as $penumpang )
                <tr>
                    <td class="td-field">{{$penumpang?->nama}} / {{$penumpang->no_telp}}</td>
                    <td class="td-field">{{$pesanan->titikJemput?->nama}}</td>
                    <td class="td-field">{{$data->master_rute->kota_asal}} - {{$data->master_rute->kota_tujuan}}</td>
                    <td class="td-field">Rp.{{number_format($data->master_rute->harga,0,',','.')}}</td>
                    <td class="td-field"></td>
                    <td class="td-field"></td>
                    <td class="td-field"></td>
                </tr>
                @endforeach
                @endforeach
            </tbody>
        </table>
        <table class="table" style="width: 100%;">
            <tr>
                <td style="padding-bottom: 8px">Penumpang .....................x</td>
                <td>Rp. ...................................</td>
                <td style="padding-left: 20px">= Rp. ..........................</td>
            </tr>
            <tr>
                <td>Komisi Ktr</td>
                <td>Rp. ...................................</td>
                <td></td>
            </tr>
            <tr>
                <td>Komisi Agen</td>
                <td>Rp. ...................................</td>
                <td></td>
            </tr>
            <tr>
                <td>Jemputan</td>
                <td>Rp. ...................................</td>
                <td></td>
            </tr>
            <tr>
                <td>Snack</td>
                <td>Rp. ...................................</td>
                <td></td>
            </tr>
            <tr>
                <td>BBM</td>
                <td>Rp. ...................................</td>
                <td></td>
            </tr>
            <tr>
                <td>Kapal</td>
                <td>Rp. ...................................</td>
                <td></td>
            </tr>
            <tr>
                <td>BBM Tol</td>
                <td>Rp. ...................................</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td style="width: 30%; border-bottom:1px solid black; text-align:right;">(+)</td>
                <td></td>
            </tr>
        </table>
        <table style="width: 100%; margin-top: 20px">
            <tr>
                <td style="width: 70%;">Jumlah Pengeluaran</td>
                <td style="width: 40%;">Rp. ...............................</td>
            </tr>
            <tr>
                <td><strong>Jumlah Bersih</strong></td>
                <td><strong>Rp. ...............................</strong></td>
            </tr>
        </table>
        <table style="width: 100%;">
            <tr>
                <td style="text-align: center; margin-right: 40px; width: 25%;">
                    <p style="margin-bottom: 50px;">Kantor,</p>
                    <p>(.......................)</p>
                </td>
                <td style="text-align: center; width: 40%;">
                    <p style="margin-bottom: 50px;">Supir,</p>
                    <p>(.......................)</p>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
