<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        @font-face {
            font-family: "Nunito";
            font-style: normal;
            src: url('{{ public_path("assets/font/Nunito-Regular.ttf") }}') format('truetype');
        }

        @font-face {
            font-family: 'Nunito';
            src: url('{{ public_path("assets/font/Nunito-SemiBold.ttf") }}') format('truetype');
            font-weight: 600;
            font-style: semibold;
        }

        @page {
            margin: 0px;
        }

        .table-data {
            border-collapse: collapse;
            border: 1px solid black;
        }

        .table-data td {
            border: 1px solid black;
        }

        .table-data thead tr {
            color: white;
        }

        body {
            margin: 0px;
        }

        td {
            padding: 10px;
        }

        .field>p {
            color: #8C8D89;
        }

        p,
        h1 {
            margin: 0;
            margin-top: 5px;
        }
    </style>
</head>

<body style="font-family:'Nunito', sans-serif;">
    <div style="width: 100%; height: 69px; background-color: #3572EF">
        <div style="text-align: center; padding-top: 25px; font-size: 17px; color:white">E-tiket Keberangkatan Travel</div>
    </div>
    <div style="padding:0px 40px;">
        <table style="width: 100%;">
            <tr>
                <td>
                    <!-- HEADER -->
                    <table id="header" style="table-layout: auto;">
                        <tr>
                            <td>
                                <table style="table-layout: auto; width: 100%;">
                                    <tr>
                                        <td style="width: 75px;">
                                            <img src="{{ public_path('assets/Icon.png') }}" alt="Logo" style="width: 90px; height: 90px">
                                        </td>
                                        <td>
                                            <h1 style="font-weight: bold;">RAMA TRANZ</h1>
                                            <p>PT. RASYA MANDIRI TRANZ</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <hr>
                    <div style="font-size: 20px; font-weight: bold; padding:20px 0px">Invoice Tiket Travel</div>
                    <!-- BODY -->
                    <table style="table-layout: auto; width: 100%;">
                        <tr>
                            <td style="padding-top:15px;">
                                <div>
                                    <p><strong>Nama Pemesan</strong></p>
                                    <p>{{$data->pesanan->nama}}</p>
                                </div>
                            </td>
                            <td style="padding-top:15px;">
                                <div>
                                    <p><strong>No. Telepon</strong></p>
                                    <p>{{$data->pesanan->no_telp}}</p>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top:15px;">
                                <div>
                                    <p><strong>No. Invoice</strong></p>
                                    <p>{{$data->pesanan->invoice}}</p>
                                </div>
                            </td>
                            <td style=" padding-top:15px;">
                                <div>
                                    <p><strong>Metode Pembayaran</strong></p>
                                    <p>{{$data->pembayaran->metode}}</p>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top:15px;">
                                <div>
                                    <p><strong>Waktu Pembayaran</strong></p>
                                    <p>{{$data->pembayaran->jam}}</p>
                                </div>
                            </td>
                            <td style=" padding-top:15px;">
                                <div>
                                    <p><strong>Tanggal Pembayaran</strong></p>
                                    <p>{{$data->pembayaran->tanggal}}</p>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <hr>
        <div style="font-size: 20px; font-weight: bold;  padding:20px 0px">Detail Penumpang</div>
        <table class="table-data" style="table-layout: auto; width: 100%; margin-bottom: 20px; ">
            <thead>
                <tr style="background-color: #3572EF;">
                    <td><strong>Nama</strong></td>
                    <td><strong>NIK</strong></td>
                    <td><strong>Email</strong></td>
                    <td><strong>No. Tlp</strong></td>
                    <td><strong>No. Kursi</strong></td>
                </tr>
            </thead>
            <tbody>
                @foreach ($data->penumpang as $penumpang)
                <tr>
                    <td>{{$penumpang->nama}}</td>
                    <td>{{$penumpang->nik}}</td>
                    <td>{{$penumpang->email}}</td>
                    <td>{{$penumpang->no_telp}}</td>
                    <td>{{$penumpang->kursi}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- DETAIL PENUMPANG -->
        <hr>
        <div style="font-size: 20px; font-weight: bold; padding:20px 0px">Detail Pembayaran</div>
        <table class="table-data" style="table-layout: auto; width: 100%;">
            <thead>
                <tr style="background-color: #3572EF">
                    <td style="text-align: center">
                        <strong>Jumlah Tiket</strong>
                    </td>
                    <td style="text-align: center">
                        <strong>Harga Tiket</strong>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center">{{$data->pembayaran->jumlah_tiket}}</td>
                    <td style="text-align: center">Rp.{{number_format($data->pembayaran->harga_tiket,0,',','.')}}</td>
                </tr>
                <tr>
                    <td style="text-align: center"><strong>Total Harga</strong></td>
                    <td style="text-align: center">Rp.{{number_format($data->pembayaran->total_harga,0,',','.')}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
