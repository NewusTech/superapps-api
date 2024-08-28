<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paket</title>
    <style>
        @page {
            margin: 0px;
        }

        body {
            margin: 0px;
        }

        td {
            padding: 0px;
        }

        .field>p {
            color: #8C8D89;
        }

        p,
        h1 {
            margin: 0;
        }
    </style>
</head>

<body style="padding: 25.87px 32px; font-family:'Nunito', sans-serif;">
    <table style="width: 100%; border: 1px solid #000000">
        <tr>
            <td>
                <!-- HEADER -->
                <table id="header" style="table-layout: auto;">
                    <tr>
                        <td>
                            <table style="table-layout: auto; width: 100%;">
                                <tr>
                                    <td>
                                        <img src="{{ public_path('assets/Icon.png') }}" alt="Logo" style="width: 60px; height: 60px">
                                    </td>
                                    <td>
                                        <p style="font-weight: bold; font-size: 14px; padding: 0px; margin: 0px;">RAMA TRANZ</>
                                        <p style="font-size: 12px; padding: 0px; margin: 0px;">PT. RASYA MANDIRI TRANZ</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <!-- BODY -->
            </td>
        </tr>
    </table>
    <table style="width: 100%; border: 1px solid #000000; border-top: none;padding:5px">
        <tr>
            <td><strong style="font-size: 12px;">Tanda Terima Kiriman Barang / Paket</strong></td>
        </tr>
        <tr>
            <td><strong style="font-size: 12px;">No. {{$paket->resi}}</strong></td>
        </tr>
    </table>
    <table style=" width: 100%;  border: 1px solid #000000; border-top: none; padding:3px">
        <div><strong style="font-size: 14px;">DETAIL PENGIRIM</strong></div>
        <tr>
            <td style="width: 45%"><strong style="font-size: 12px;">Nama</strong></td>
            <td style="font-size:12px">{{$paket->nama_pengirim}}</td>
        </tr>
        <tr>
            <td><strong style="font-size: 12px;">Nomor Telepon</strong></td>
            <td style="font-size:12px">{{$paket->no_telp_pengirim ?? '-'}}</td>
        </tr>
        <tr>
            <td><strong style="font-size: 12px;">Alamat</strong></td>
            <td style="font-size:12px">{{$paket->alamat_pengirim ?? '-'}}</td>
        </tr>
        <tr>
            <td><strong style="font-size: 12px;">Tujuan</strong></td>
            <td style="font-size:12px">{{$paket->tujuan ?? '-'}}</td>
        </tr>
        <tr>
            <td><strong style="font-size: 12px;">Tanggal Dikirim</strong></td>
            <td style="font-size:12px">{{$paket->tanggal_dikirim}}</td>
        </tr>
        <tr>
            <td><strong style="font-size: 12px;">Jenis</strong></td>
            <td style="font-size:12px">{{$paket->jenis_paket}}</td>
        </tr>
        <tr>
            <td><strong style="font-size: 12px;">Total Berat</strong></td>
            <td style="font-size:12px">{{$paket->total_berat}}</td>
        </tr>
        <tr>
            <td><strong style="font-size: 12px;">Biaya</strong></td>
            <td style="font-size:12px">{{$paket->biaya}}</td>
        </tr>

    </table>
    <table style="background-color: #f2f2f2; width: 100%; border: 1px solid #000000; margin-top: 3px; padding:3px; margin-bottom: 3px">
        <div><strong style="font-size: 14px;">DETAIL PENERIMA</strong></div>
        <tr>
            <td style="width: 45%"><strong style="font-size: 12px;">Nama</strong></td>
            <td style="font-size:12px">{{$paket->nama_penerima}}</td>
        </tr>
        <tr>
            <td><strong style="font-size: 12px;">Nomor Telepon</strong></td>
            <td style="font-size:12px">{{$paket->no_telp_penerima ?? '-'}}</td>
        </tr>
        <tr>
            <td><strong style="font-size: 12px;">Alamat</strong></td>
            <td style="font-size:12px">{{$paket->alamat_penerima}}</td>
        </tr>
        <tr>
            <td><strong style="font-size: 12px;">Estimasi Tanggal Diterima</strong></td>
            <td style="font-size:12px">{{$paket->tanggal_diterima ?? '-'}}</td>
        </tr>
    </table>
    <div style="text-align: center;">
        <img src="data:image/png;base64,{{ $barcode}}" alt="barcode">
    </div>
</body>

</html>
