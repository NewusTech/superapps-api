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


        .field >p{
            color: #8C8D89;
        }

        p,
        h1 {
            margin: 0;
            margin-top: 5px;
        }
    </style>
</head>

<body style="padding: 10px; font-family:'Nunito', sans-serif;">
    @foreach ($data as $penumpang)
    <table>
        <tr>
            <td>
                <table style="table-layout: auto;">
                    <tr>
                        <td>
                            <table style="table-layout: auto; width: 100%;">
                                <tr>
                                    <td style="width: 75px;">
                                        <img src="{{ public_path('assets/Icon.png') }}" alt="Logo" style="width: 90px; height: 90px">
                                    </td>
                                    <td>
                                        <h1 style="font-weight: bold;">RAMA TRANZ</h1>
                                        <p >PT. RASYA MANDIRI TRANZ</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <table style="table-layout: auto;">
                    <tr>
                        <td style="width: 18rem;">
                            <div id="orderData" class="">
                                <div class="grid grid-cols-2">
                                    <div class="mb-4 text-sm space-y-1">
                                        <div class="field">
                                            <p>Nama:</p>
                                            <strong>{{$penumpang['nama']}}</strong>
                                        </div>
                                        <div class="field">
                                            <p>No Identitas:</p>
                                            <strong>{{$penumpang['nik']}}</strong>
                                        </div>
                                        <div class="field">
                                            <p>No Telepon:</p>
                                            <strong>{{$penumpang['no_telp']}}</strong>
                                        </div>
                                        <div class="field">
                                            <p>Keberangkatan:</p>
                                            <strong>{{$penumpang['keberangkatan']}}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="mb-4 text-sm space-y-1">
                                <div class="field">
                                    <p>Email:</p>
                                    <strong>{{$penumpang['email']}}</strong>
                                </div>
                                <div class="field">
                                    <p>No Kursi:</p>
                                    <strong>{{$penumpang['kursi']}}</strong>
                                </div>
                                <div class="field">
                                    <p>Tipe Mobil:</p>
                                    <strong>{{$penumpang['mobil']}}</strong>
                                </div>
                                <div class="field">
                                    <p>Tiba:</p>
                                    <strong>{{$penumpang['tiba']}}</strong>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="padding-left: 70px; padding-top:15px;">
                <div id="qrcode">
                    <div style="text-align: center;">Qr Code</div>
                    <div class="flex justify-center">
                        <img src="data:image/png;base64,{{ $qrcode }}" alt="QR Code" style="width: 200px; height: 200px;">
                    </div>
                </div>
            </td>
        </tr>
    </table>
    <hr>
    @endforeach
</body>

</html>
