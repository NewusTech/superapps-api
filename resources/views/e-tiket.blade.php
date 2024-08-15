<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Tiket</title>
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

        .point>tr>td>p {
            padding: 5px;
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

<body style="padding: 40px; font-family:'Nunito', sans-serif;">
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

                <!-- BODY -->
                <div style="margin-top: 20px; margin-bottom: 10px;"> <strong>Detail Pemesan</strong></div>
                <table style="table-layout: auto; width: 100%;">
                    <tr>
                        <td style="padding:0;">
                            <div>
                                <p style="margin-bottom: 10px; font-size: 14px;">Nama</p>
                                <p style="margin-bottom: 10px;"><strong>{{$data->nama}}</strong></p>
                            </div>

                            <div>
                                <p style="margin-bottom: 10px; font-size: 14px;">No. Telepon</p>
                                <p style="margin-bottom: 10px;"><strong>{{$data->no_telp}}</strong></p>
                            </div>
                        </td>
                        <td style="vertical-align: top; text-align: center;">
                            <div>
                                <img src="data:image/png;base64,{{ $qrcode }}" alt="QR Code" style="width: 150px; height: 150px;">
                            </div>
                        </td>
                    </tr>
                </table>
                <hr style="border: 0; border-top: 1px dashed #000; color: #8C8D89; margin-top: 10px; margin: bottom 10px;">
                <div style="margin-top: 20px;"> <strong>Pergi</strong></div>
                <table style="table-layout: auto; width: 50%;">
                    <tr>
                        <td style="width: 15%;">
                            <img src="{{ public_path('assets/point.png') }}" alt="point">
                        </td>
                        <td>
                            <table class="point">
                                <tr style="vertical-align: top">
                                    <td>
                                        <p>{{$data->jadwal->master_rute->kota_asal}}</p>
                                        <p>{{$data->titikJemput->nama}}</p>
                                        <p>{{$data->jadwal->tanggal_berangkat}}</p>
                                    </td>
                                </tr>
                                <tr style="vertical-align: bottom">
                                    <td>
                                        <p>{{$data->jadwal->master_rute->kota_tujuan}}</p>
                                        <p>{{$data->titikAntar->nama}}</p>
                                        <p>{{$data->jadwal->tanggal_berangkat}}</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <!-- DETAIL PENUMPANG -->
                <div style="margin-top: 20px; margin-bottom: 10px;"> <strong>Detail Pembayaran</strong></div>
                <table style="table-layout: auto; width: 100%;">
                    <thead>
                        <tr style="background-color: #E8E8E8">
                            <td>
                                <strong>Nama</strong>
                            </td>
                            <td>
                                <strong>NIK</strong>
                            </td>
                            <td>
                                <strong>Email</strong>
                            </td>
                            <td>
                                <strong>Nomor Telepon</strong>
                            </td>
                            <td>
                                <strong>Nomor Kursi</strong>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- todo: nanti di foreach -->
                        @foreach ($data->penumpang as $penumpang)
                        <tr>
                            <td>{{$penumpang->nama}}</td>
                            <td>{{$penumpang->nik}}</td>
                            <td>{{$penumpang->email}}</td>
                            <td>{{$penumpang->no_telp}}</td>
                            <td style="text-align: center">{{$penumpang->kursi->nomor_kursi}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
