<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
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
    <div style="width: 100%; height: 69px; background-color: #3572EF; margin-top:30px">
        <div style="text-align: center; padding-top: 25px; font-size: 17px; color:white">E-Voucher Rental</div>
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
                    <div style="font-size: 20px; font-weight: bold; padding:20px 0px">Invoice Pemesanan Rental</div>
                    <!-- BODY -->
                    <table style="table-layout: auto; width: 100%;">
                        <tr>
                            <td style="padding-top:15px;">
                                <div>
                                    <p><strong>Nama Pemesan</strong></p>
                                    <p>{{$data->rental->nama}}</p>
                                </div>
                            </td>
                            <td style="padding-top:15px;">
                                <div>
                                    <p><strong>No. Telepon</strong></p>
                                    <p>{{$data->rental->no_telp}}</p>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top:15px;">
                                <div>
                                    <p><strong>No. Identitas</strong></p>
                                    <p>{{$data->rental->nik}}</p>
                                </div>
                            </td>
                            <td style=" padding-top:15px;">
                                <div>
                                    <p><strong>Email</strong></p>
                                    <p>{{$data->rental->email}}</p>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top:15px;">
                                <div>
                                    <p><strong>No. Invoice</strong></p>
                                    <p>{{$data->kode_pembayaran}}</p>
                                </div>
                            </td>
                            <td style=" padding-top:15px;">
                                <div>
                                    <p><strong>Metode Pembayaran</strong></p>
                                    <p>{{$data->rental->metode->metode}}</p>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top:15px;">
                                <div>
                                    <p><strong>Waktu Pembayaran</strong></p>
                                    <p>{{$data->waktu_pembayaran}}</p>
                                </div>
                            </td>
                            <td style=" padding-top:15px;">
                                <div>
                                    <p><strong>Tanggal Pembayaran</strong></p>
                                    <p>{{$data->tanggal_pembayaran}}</p>
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
                    <td><strong>Tipe Mobil</strong></td>
                    <td><strong>Area Sewa</strong></td>
                    <td><strong>Tanggal Sewa</strong></td>
                    <td><strong>Durasi</strong></td>
                    <td><strong>Harga</strong></td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{$data->rental->mobil->type}}</td>
                    <td>{{$data->rental->area}}</td>
                    <td>{{$data->rental->tanggal_mulai_sewa}} - {{$data->rental->tanggal_akhir_sewa}}</td>
                    <td>{{$data->rental->durasi_sewa}} Hari</td>
                    <td>Rp. {{number_format($data->rental->mobil->biaya_sewa, 0, ',', '.')}}</td>
                </tr>
                <tr>
                    <td colspan="3"><strong>All In</strong></td>
                    <td colspan="2">{{$data->rental->all_in ? $data->rental->mobil->biaya_all_in : '-'}}</td>
                </tr><tr>
                    <td colspan="3"><strong>Total Harga</strong></td>
                    <td colspan="2">Rp. {{number_format($data->nominal, 0, ',', '.')}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
