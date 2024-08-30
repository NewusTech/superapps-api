<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Tiket</title>
    <style>
        .field>p {
            font-size: 13px;
        }

        .td-field {
            padding: 7px 4px
        }

        .field>strong {
            font-size: 15px;
        }

        table,
        tr,
        td {
            padding: 0px;
            margin: 0px;
        }

        @page {
            margin: 0px;
        }

        body {
            margin: 0px;
        }


        p,
        h1 {
            margin: 0;
            margin-top: 5px;
        }
    </style>
</head>

<body style="padding: 20px 0px;  font-family:'Nunito', sans-serif; background-color: #3572EF">
    @foreach ($data as $penumpang)
    <table style="background-color: white; width: 100%;">
        <tr>
            <td>
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 75px;">
                            <img src="{{ public_path('assets/Icon.png') }}" alt="Logo" style="width: 99px; height: 66px">
                        </td>
                        <td>
                            <strong style="font-weight: bold; font-size: 22px">RAMA TRANZ</strong>
                            <p style="font-size: 13px">PT. RASYA MANDIRI TRANZ</p>
                        </td>
                    </tr>
                </table>
                <table style="width: 100%; padding: 5px">
                    <tr>
                        <td class="td-field">
                            <div class="field">
                                <p>Nama</p>
                                <strong>{{$penumpang['nama']}}</strong>
                            </div>
                        </td>
                        <td class="td-field">
                            <div class="field">
                                <p>No Telepon</p>
                                <strong>{{$penumpang['no_telp']}}</strong>
                            </div>
                        </td>
                        <td class="td-field">
                            <div class="field">
                                <p>No. Pesanan</p>
                                <strong>{{$penumpang['kode']}}</strong>
                            </div>
                        </td>
                        <td class="td-field">
                            <div class="field">
                                <p>No Kursi</p>
                                <strong>{{$penumpang['kursi']}}</strong>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-field">
                            <div class="field">
                                <p>No Identitas</p>
                                <strong>{{$penumpang['nik']}}344</strong>
                            </div>
                        </td>
                        <td class="td-field">
                            <div class="field">
                                <p>Email</p>
                                <strong>{{$penumpang['email']}}</strong>
                            </div>
                        </td>
                        <td class="td-field">
                            <div class="field">
                                <p>Tipe Mobil</p>
                                <strong>{{$penumpang['mobil']}}</strong>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-field" >
                            <div class="field">
                                <p>Hari</p>
                                <strong>{{$penumpang['hari']}}</strong>
                            </div>
                        </td>
                        <td class="td-field">
                            <div class="field">
                                <p>Waktu Berangkat</p>
                                <strong>{{$penumpang['jam']}} WIB</strong>
                            </div>
                        </td>
                        <td class="td-field">
                            <div class="field">
                                <p>Keberangkatan</p>
                                <strong>{{$penumpang['keberangkatan']}}</strong>
                            </div>
                        </td>
                        <td class="td-field">
                            <div class="field">
                                <p>Perkiraan Tiba</p>
                                <strong>{{$penumpang['tiba']}}</strong>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
            <td>
                <div style="text-align: center;">BOARDING PASS</div>
                <div id="qrcode">
                    <div style="padding-left: 10px; padding-top: 10px">
                        <img src="data:image/png;base64,{{ $qrcode }}" alt="QR Code" style="width: 150px; height: 150px;">
                    </div>
                </div>
            </td>
        </tr>
    </table>
    @endforeach
</body>

</html>
