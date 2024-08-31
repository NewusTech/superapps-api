<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Tiket</title>
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
            color: white;
        }

        .point>tr>td>p {
            padding: 5px;
        }

        .field>p {
            color: #8C8D89;
        }

        .table-penumpang {
            border-collapse: collapse;
            border: 1px solid black;
        }

        .td-field {
            border: 1px solid black;
        }

        p,
        h1 {
            margin: 0;
            margin-top: 5px;
        }
    </style>
</head>

<body style=" font-family:'Nunito', sans-serif;">
    <div style="width: 100%; height: 69px; background-color: #3572EF">
        <div style="text-align: center; padding-top: 25px; font-size: 17px; color:white">E-tiket Rental</div>
    </div>
    <div style="padding:0px 60px;">
        <table style="width: 100%;">
            <tr>
                <td>
                    <!-- HEADER -->
                    <table id="header" style="table-layout: auto;">
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
                    <hr>
                    <!-- BODY -->
                    <div style="margin-top: 20px; margin-bottom: 10px; font-size: 18px"><strong>Detail Pemesanan Rental</strong></div>
                    <table style="table-layout: auto; width: 100%;">
                        <tr>
                            <table style="table-layout: auto; width: 100%; ">
                                <tr style="padding:0;margin:0;">
                                    <td style="padding:0;">
                                        <div>
                                            <p style="margin-bottom: 10px; font-size: 14px;">No.Pemesan</p>
                                            <p style="margin-bottom: 10px;"><strong>{{$data->rental->kode_pesanan}}</strong></p>
                                        </div>
                                    </td>
                                    <td style="padding:0;">
                                        <div>
                                            <p style="margin-bottom: 10px; font-size: 14px;">Waktu Pemesanan</p>
                                            <p style="margin-bottom: 10px;"><strong>{{$data->rental->waktu_pemesanan}}</strong></p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:0;">
                                        <div>
                                            <p style="margin-bottom: 10px; font-size: 14px;">Nama Pemesan</p>
                                            <p style="margin-bottom: 10px;"><strong>{{$data->rental->nama}}</strong></p>
                                        </div>
                                    </td>
                                    <td style="padding:0;">
                                        <div>
                                            <p style="margin-bottom: 10px; font-size: 14px;">No Identitas</p>
                                            <p style="margin-bottom: 10px;"><strong>{{$data->rental->nik}}</strong></p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:0;">
                                        <div>
                                            <p style="margin-bottom: 10px; font-size: 14px;">No. Telepon</p>
                                            <p style="margin-bottom: 10px;"><strong>{{$data->rental->no_telp}}</strong></p>
                                        </div>
                                    </td>
                                    <td style="padding:0;">
                                        <div>
                                            <p style="margin-bottom: 10px; font-size: 14px;">Email</p>
                                            <p style="margin-bottom: 10px;"><strong>{{$data->rental->email}}</strong></p>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </tr>
                    </table>
                    <hr style="border: 0; border-top: 1px solid #000; color: #8C8D89; margin-top: 10px; margin: bottom 10px;">
                    <div style="margin-top: 20px; margin-bottom: 10px; font-size: 18px"><strong>Detail Sewa Mobil</strong></div>
                    <table style="table-layout: auto; width: 100%;">
                        <tr>
                            <table style="table-layout: auto; width: 100%; ">
                                <tr style="padding:0;margin:0;">
                                    <td style="padding:0;">
                                        <div>
                                            <p style="margin-bottom: 10px; font-size: 14px;">Tipe Mobil</p>
                                            <p style="margin-bottom: 10px;"><strong>{{$data->rental->mobil->type}}</strong></p>
                                        </div>
                                    </td>
                                    <td style="padding:0;">
                                        <div>
                                            <p style="margin-bottom: 10px; font-size: 14px;">Tanggal Mulai Sewa</p>
                                            <p style="margin-bottom: 10px;"><strong>{{$data->rental->tanggal_mulai_sewa}}</strong></p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:0;">
                                        <div>
                                            <p style="margin-bottom: 10px; font-size: 14px;">Area Sewa</p>
                                            <p style="margin-bottom: 10px;"><strong>{{$data->rental->area}}</strong></p>
                                        </div>
                                    </td>
                                    <td style="padding:0;">
                                        <div>
                                            <p style="margin-bottom: 10px; font-size: 14px;">Jam Keberangkatan</p>
                                            <p style="margin-bottom: 10px;"><strong>{{$data->rental->jam_keberangkatan}}</strong></p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:0;">
                                        <div>
                                            <p style="margin-bottom: 10px; font-size: 14px;">Durasi Sewa</p>
                                            <p style="margin-bottom: 10px;"><strong>{{$data->rental->durasi_sewa}} Hari</strong></p>
                                        </div>
                                    </td>
                                    <td style="padding:0;">
                                        <div>
                                            <p style="margin-bottom: 10px; font-size: 14px;">Alamat Keberangkatan</p>
                                            <p style="margin-bottom: 10px;"><strong>{{$data->rental->alamat_keberangkatan}}</strong></p>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </tr>
                    </table>
                    <hr style="border: 0; border-top: 1px solid #000; color: #8C8D89; margin-top: 10px; margin: bottom 10px;">
                    <div style="background-color: #3572EF26; height: 102px; padding:20px 15px; margin-top: 20px">
                        <p style="font-size: 18px">Catatan</p>
                        <p style="font-size: 14px">{{$data->rental->catatan_sopir}}</p>
                    </div>
                    <!-- Catatan -->
                </td>
            </tr>
        </table>
    </div>

</body>

</html>
