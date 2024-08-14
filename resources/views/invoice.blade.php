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

        body {
            margin: 0px;
        }

        td {
            padding: 10px;
        }

        .field>p {
            color: #8C8D89;
        }

        p,h1 {
            margin: 0;
            margin-top: 5px;
        }
    </style>
</head>

<body style="padding: 40px; font-family:'Nunito', sans-serif;">
    <table>
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
                <!-- BODY -->
                <table style="table-layout: auto; width: 80%;">
                    <tr>
                        <td style="padding:0;">
                            <div>
                                <p><strong>Invoice</strong></p>
                                <p>{{$data->pesanan->invoice}}</p>
                            </div>

                            <div style="margin-top: 10px;">
                                <p><strong>Metode Pembayaran</strong></p>
                                <p>{{$data->pesanan->metode_pembayaran}}</p>
                            </div>
                        </td>
                        <td style="padding:0;">
                            <div>
                                <p><strong>Nama Pemesan</strong></p>
                                <p>{{$data->pesanan->nama}}</p>
                            </div>

                            <div style="margin-top: 10px;">
                                <p><strong>No. Telepon</strong></p>
                                <p>{{$data->pesanan->no_telp}}</p>
                            </div>
                        </td>
                    </tr>
                </table>
                <div style="margin-top: 20px;"> <strong>Detail Penumpang</strong></div>
                <table style="table-layout: auto; width: 100%;">
                    <thead>
                        <tr style="background-color: #E8E8E8;">
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
                <div style="margin-top: 20px;"> <strong>Detail Pembayaran</strong></div>
                <table style="table-layout: auto; width: 100%;">
                    <thead>
                        <tr style="background-color: #E8E8E8">
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
                            <td style="text-align: center">{{$data->pembayaran->harga_tiket}}</td>
                            <td style="text-align: center">{{$data->pembayaran->jumlah_tiket}}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center"><strong>Total Harga</strong></td>
                            <td style="text-align: center">{{$data->pembayaran->total_harga}}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
