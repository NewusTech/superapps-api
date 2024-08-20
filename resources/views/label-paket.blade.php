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
    <table style="width: 100%; border: 1px solid #000000; border-top: none; padding:3px">
        <div><strong style="font-size: 14px;">DETAIL PENGIRIM</strong></div>
        <tr>
            <td>
                <div><strong style="font-size: 12px;">Nama</strong></div>
                <div><strong style="font-size: 12px;">Nomor Telepon</strong></div>
                <div><strong style="font-size: 12px;">Alamat</strong></div>
                <div><strong style="font-size: 12px;">Tujuan</strong></div>
                <div><strong style="font-size: 12px;">Tanggal Dikirim</strong></div>
                <div><strong style="font-size: 12px;">Jenis</strong></div>
                <div><strong style="font-size: 12px;">Total Berat</strong></div>
                <div><strong style="font-size: 12px;">Biaya</strong></div>
            </td>
            <td>
                <div style="font-size:12px">{{$paket->nama_pengirim}}</div>
                <div style="font-size:12px">{{$paket->no_telp_pengirim ?? '-'}}</div>
                <div style="font-size:12px">{{$paket->alamat_pengirim ?? '-'}}</div>
                <div style="font-size:12px">{{$paket->tujuan}}</div>
                <div style="font-size:12px">{{$paket->tanggal_dikirim}}</div>
                <div style="font-size:12px">{{$paket->jenis_paket}}</div>
                <div style="font-size:12px">{{$paket->total_berat}}</div>
                <div style="font-size:12px">{{$paket->biaya}}</div>
            </td>
        </tr>
    </table>
    <table style="width: 100%; border: 1px solid #000000; margin-top: 3px; padding:3px">
        <div><strong style="font-size: 14px;">DETAIL PENERIMA</strong></div>
        <tr>
            <td>
                <div><strong style="font-size: 12px;">Nama</strong></div>
                <div><strong style="font-size: 12px;">Nomor Telepon</strong></div>
                <div><strong style="font-size: 12px;">Alamat</strong></div>
                <div><strong style="font-size: 12px;">Estimasi Tanggal Diterima</strong></div>
            </td>
            <td>
                <div style="font-size:12px">{{$paket->nama_penerima}}</div>
                <div style="font-size:12px">{{$paket->no_telp_penerima}}</div>
                <div style="font-size:12px">{{$paket->alamat_penerima}}</div>
                <div style="font-size:12px">{{$paket->tanggal_diterima}}</div>
            </td>
        </tr>
    </table>
    <div style="text-align: center; padding: 10px">
        <img src="data:image/png;base64,{{ $barcode}}" alt="barcode">
    </div>
</body>

</html>
