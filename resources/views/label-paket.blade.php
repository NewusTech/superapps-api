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

<body style="padding: 10px 32px; font-family:'Nunito', sans-serif;">
    <table style="width: 100%;">
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
                <div style="border-top: 1px solid #8C8D89; height: 1px; margin-bottom: 5px;"></div>
                <!-- Barcode -->
                <div style="text-align: center;">
                    <img src="data:image/png;base64,{{$barcode}}" alt="barcode">
                    <p style="font-size: 12px; margin: 8px">{{$paket->resi}}</p>
                </div>
                <!-- BODY -->
            </td>
        </tr>
    </table>
    <table style="width: 100%; border: 1px solid #8C8D89; border-collapse: collapse;">
        <tr>
            <td style=" width: 100%/3; text-align: center;  border: 1px solid rgb(160 160 160); padding: 5px">
                <p style="font-size: 10px; padding: 0px; margin: 0px;">Jenis</p>
                <strong style="font-size: 11px; padding: 0px; margin: 0px;">{{$paket->jenis_paket}}</strong>
            </td>
            <td style="text-align: center; border: 1px solid rgb(160 160 160);">
                <p style="font-size: 10px; padding: 0px; margin: 0px;">Total Berat</p>
                <strong style="font-size: 11px; padding: 0px; margin: 0px;">{{$paket->total_berat}}</strong>
            </td>
            <td style="text-align: center; border: 1px solid rgb(160 160 160);">
                <p style="font-size: 10px;padding: 0px; margin: 0px;">Tunai</p>
                <strong style="font-size: 11px; padding: 0px; margin: 0px;">Rp. {{number_format($paket->biaya, 0, ',', '.')}}</strong>
            </td>
        </tr>
    </table>

    <div style="border: 1px solid #8C8D89; border-top: none; padding: 0; margin:0;">
        <div><strong style="font-size: 13px; padding-left: 5px">DETAIL PENGIRIM</strong> :</div>
        <table style=" width: 100%; padding:3px">
            <tr>
                <td style="width: 35%"><strong style="font-size: 12px;">Nama</strong></td>
                <td style="font-size:11px">: {{$paket->nama_pengirim}}</td>
            </tr>
            <tr>
                <td><strong style="font-size: 12px;">No. Telepon</strong></td>
                <td style="font-size:11px">: {{$paket->no_telp_pengirim ?? '-'}}</td>
            </tr>
            <tr>
                <td><strong style="font-size: 12px;">Alamat</strong></td>
                <td style="font-size:11px">: {{$paket->alamat_pengirim ?? '-'}}</td>
            </tr>
            <tr>
                <td><strong style="font-size: 12px;">Kota Tujuan</strong></td>
                <td style="font-size:11px">: {{$paket->tujuan ?? '-'}}</td>
            </tr>
            <tr>
                <td><strong style="font-size: 12px;">Tanggal Dikirim</strong></td>
                <td style="font-size:12px">: {{$paket->tanggal_dikirim}}</td>
            </tr>
        </table>
    </div>
    <div style="border: 1px solid #8C8D89; border-top: none; padding: 0; margin:0;">
        <div><strong style="font-size: 13px; padding: 8px 8px 8px 5px">DETAIL PENERIMA</strong> :</div>
        <table style=" width: 100%; padding:3px">
            <tr>
                <td style="width: 35%"><strong style="font-size: 12px;">Nama</strong></td>
                <td style="font-size:11px">: {{$paket->nama_penerima}}</td>
            </tr>
            <tr>
                <td><strong style="font-size: 12px;">No. Telepon</strong></td>
                <td style="font-size:11px">: {{$paket->no_telp_penerima ?? '-'}}</td>
            </tr>
            <tr>
                <td><strong style="font-size: 12px;">Alamat</strong></td>
                <td style="font-size:11px">: {{$paket->alamat_penerima ?? '-'}}</td>
            </tr>
            <tr>
                <td><strong style="font-size: 12px;">Tanggal Diterima</strong></td>
                <td style="font-size:12px">: {{$paket->tanggal_diterima}}</td>
            </tr>
        </table>
    </div>
    <div style="border: 1px solid #8C8D89; border-top: none; padding: 0; margin:0;">
        <div><strong style="font-size: 13px; padding-left: 5px">Catatan</strong> :</div>
        <p style="font-size: 11px; padding-left: 5px; height: 25px">{{$paket->catatan ?? '-'}}</p>
    </div>
    <div style="font-size: 11px; margin-top:5px"><strong>Alamat :</strong>Jl. Mayor Salim Batubara No. 7 Teluk Betung 35212 Bandar Lampung Lampung</div>

    <!-- Page 2 -->
    <div style="page-break-after: always;"></div>
    <table style="width: 100%;">
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
                <div style="border-top: 1px solid #8C8D89; height: 1px; margin-bottom: 5px;"></div>
                <!-- Barcode -->
                <div style="text-align: center;">
                    <img src="data:image/png;base64,{{$barcode}}" alt="barcode">
                    <p style="font-size: 12px; margin: 8px">{{$paket->resi}}</p>
                </div>
                <!-- BODY -->
            </td>
        </tr>
    </table>
    <table style="width: 100%; border: 1px solid #8C8D89; border-collapse: collapse;">
        <tr>
            <td style="width: 50%; padding:5px; text-align: center; border: 1px solid rgb(160 160 160);">
                <p style="font-size: 10px; padding: 0px; margin: 0px;">Pengirim</p>
                <strong style="font-size: 11px; padding: 0px; margin: 0px;">{{$paket->nama_pengirim}}</strong>
            </td>
            <td style="text-align: center; border: 1px solid rgb(160 160 160);">
                <p style="font-size: 10px;padding: 0px; margin: 0px;">Penerima</p>
                <strong style="font-size: 11px; padding: 0px; margin: 0px;">{{$paket->nama_penerima}}</strong>
            </td>
        </tr>
    </table>

    <div style="border: 1px solid #8C8D89; border-top: none; padding: 0; margin:0;">
        <div><strong style="font-size: 13px; padding-left: 5px">DETAIL</strong> :</div>
        <table style=" width: 100%; padding:3px">
            <tr>
                <td><strong style="font-size: 12px;">Tanggal Dikirim</strong></td>
                <td style="font-size:12px">: {{$paket->tanggal_dikirim}}</td>
            </tr>
            <tr>
                <td><strong style="font-size: 12px;">Berat</strong></td>
                <td style="font-size:12px">: {{$paket->total_berat}} KG</td>
            </tr>
            <tr>
                <td><strong style="font-size: 12px;">Jenis</strong></td>
                <td style="font-size:12px">: {{$paket->jenis_paket}}</td>
            </tr>
            <tr>
                <td><strong style="font-size: 12px;">Biaya</strong></td>
                <td style="font-size:12px">: Rp. {{number_format($paket->total_biaya, 0, ',', '.')}}</td>
            </tr>
            <tr>
                <td><strong style="font-size: 12px;">Jumlah Barang</strong></td>
                <td style="font-size:11px">: {{$paket->jumlah_barang ?? '-'}}</td>
            </tr>
            <tr>
                <td><strong style="font-size: 12px;">Kota Tujuan</strong></td>
                <td style="font-size:11px">: {{$paket->tujuan ?? '-'}}</td>
            </tr>
            <tr>
                <td><strong style="font-size: 12px;">Asuransi</strong></td>
                <td style="font-size:11px">: {{'-'}}</td>
            </tr>
        </table>
    </div>
    <div style="font-size: 11px; margin-top:5px"><strong>Alamat :</strong>Jl. Mayor Salim Batubara No. 7 Teluk Betung 35212 Bandar Lampung Lampung</div>

</body>

</html>
