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
        <div style="text-align: center; padding-top: 25px; font-size: 17px; color:white">E-tiket Keberangkatan Travel</div>
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
                    <div style="margin-top: 20px; margin-bottom: 10px; font-size: 18px"><strong>Detail Tiket Travel</strong></div>
                    <table style="table-layout: auto; width: 100%;">
                        <tr>
                            <table style="table-layout: auto; width: 100%; ">
                                <tr style="padding:0;margin:0;">
                                    <td style="padding:0;">
                                        <div>
                                            <p style="margin-bottom: 10px; font-size: 14px;">No.Pemesan</p>
                                            <p style="margin-bottom: 10px;"><strong>{{$data->kode_pesanan}}</strong></p>
                                        </div>
                                    </td>
                                    <td style="padding:0;">
                                        <div>
                                            <p style="margin-bottom: 10px; font-size: 14px;">Waktu Pemesanan</p>
                                            <p style="margin-bottom: 10px;"><strong>{{$data->hari . ', ' . $data->waktu_pemesanan}}</strong></p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:0;">
                                        <div>
                                            <p style="margin-bottom: 10px; font-size: 14px;">Nama Pemesan</p>
                                            <p style="margin-bottom: 10px;"><strong>{{$data->nama}}</strong></p>
                                        </div>
                                    </td>
                                    <td style="padding:0;">
                                        <div>
                                            <p style="margin-bottom: 10px; font-size: 14px;">Hari Keberangkatan</p>
                                            <p style="margin-bottom: 10px;"><strong>{{"Kamis"}}</strong></p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:0;">
                                        <div>
                                            <p style="margin-bottom: 10px; font-size: 14px;">No. Telepon</p>
                                            <p style="margin-bottom: 10px;"><strong>{{$data->no_telp}}</strong></p>
                                        </div>
                                    </td>
                                    <td style="padding:0;">
                                        <div>
                                            <p style="margin-bottom: 10px; font-size: 14px;">Waktu Keberangkatan</p>
                                            <p style="margin-bottom: 10px;"><strong>{{$data->jam}} WIB</strong></p>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <td>
                                <p style="text-align: center; font-weight: bold; margin-bottom: 10px">QR Code Pass</p>
                                <div style="text-align: right; padding-right:40px;">
                                    <img src="data:image/png;base64,{{ $qrcode }}" alt="QR Code" style="width: 150px; height: 150px;">
                                </div>
                            </td>
                        </tr>
                    </table>
                    <hr style="border: 0; border-top: 1px solid #000; color: #8C8D89; margin-top: 10px; margin: bottom 10px;">
                    <div style="margin-top: 20px; font-size: 18px"><strong>Detail Rute Keberangkatan</strong></div>
                    <table style="table-layout: auto; width: 100%;">
                        <tr>
                            <td style="text-align: left; width: 20%;">
                                <p>{{$data->jadwal->master_rute->kota_asal}}</p>
                                <p>{{$data->titikJemput->nama}}</p>
                                <p>{{$data->jadwal->tanggal_berangkat}}</p>
                            </td>
                            <td style="width: 60%;">
                                <img src="{{ public_path('assets/point-2.png') }}" alt="point" style="width: 100%;">
                            </td>
                            <td style="text-align: right; width: 20%">
                                <p>{{$data->jadwal->master_rute->kota_tujuan}}</p>
                                <p>{{$data->titikAntar->nama}}</p>
                                <p>{{$data->jadwal->tanggal_berangkat}}</p>
                            </td>
                        </tr>
                    </table>

                    <!-- DETAIL PENUMPANG -->
                    <div style="margin-top: 20px; margin-bottom: 10px; font-size: 20px"> <strong>Detail Penumpang</strong></div>
                    <table class="table-penumpang" style="table-layout: auto; width: 100%;">
                        <thead>
                            <tr style="background-color: #3572EF">
                                <td class="td-field">
                                    <strong>Nama</strong>
                                </td>
                                <td class="td-field">
                                    <strong>NIK</strong>
                                </td>
                                <td class="td-field">
                                    <strong>Email</strong>
                                </td>
                                <td class="td-field">
                                    <strong>Nomor Telepon</strong>
                                </td>
                                <td class="td-field">
                                    <strong>Nomor Kursi</strong>
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- todo: nanti di foreach -->
                            @foreach ($data->penumpang as $penumpang)
                            <tr>
                                <td class="td-field">{{$penumpang->nama}}</td>
                                <td class="td-field">{{$penumpang->nik}}</td>
                                <td class="td-field">{{$penumpang->email}}</td>
                                <td class="td-field">{{$penumpang->no_telp}}</td>
                                <td style="text-align: center" class="td-field">{{$penumpang->kursi->nomor_kursi}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <!-- Page 2 -->
    <div style="page-break-after: always;"></div>
    <div style="width: 100%; height: 69px; background-color: #3572EF">
        <div style="text-align: center; padding-top: 25px; font-size: 17px; color:white">E-tiket Keberangkatan Travel</div>
    </div>
    <div style="padding:0px 60px;">
        <div style="margin-top: 20px;"><strong style="font-size: 18px;">Syarat & Ketentuan</strong></div>
        <div class="" style="text-align: justify; font-size: 18px">
            <ol>
                <li>Anak di atas usia 7 tahun dihitung 1 seat.</li>
                <li>Pembatalan keberangkatan harus dilaporkan pada loket 3 jam sebelum jangka waktu keberangkatan dan dikenakan biaya 25% dari harga tiket.</li>
                <li>Apabila bersangkutan tidak berangkat tepat waktunya tanpa pemberitahuan maka ongkos/panjar yang telah dilunasi tidak dapat dikembalikan.</li>
                <li>Tiket yang telah dimiliki hanya berlaku untuk tanggal dan jam keberangkatan yang dicantumkan.</li>
                <li>Apabila terjadi kecelakaan di perjalanan, kerusakan atau hilangnya barang-barang/bagasi di luar tanggung jawab perusahaan, jikalau ada korban adalah tanggung jawab PT. Asuransi Kerugian Jasa Raharja (Berdasarkan Undang-undang No. 33/1964).</li>
                <li>Barang bawaan maksimum 20 kg. Selebihnya dikenakan biaya, barang barang bagasi harus memakai label barang</li>
                <li>Barang-barang kecil (cabin) dijaga sendiri. Pengangkut tidak bertanggung jawab terhadap uang, perhiasan, dokumen, serta surat berharga atau sejenisnya dan barang pecah belah.</li>
                <li>Dilarang membawa barang-barang yang cepat busuk/berbau tajam dan binatang.</li>
                <li>Barang-barang terlarang: Narkotika, Heroin, diluar tanggung jawab perusahaan.</li>
                <li>Tambahan perongkosan yang diakibatkan karena bencana alam/terputusnya jalan raya tidak menjadi tanggung jawab perusahaan.</li>
            </ol>
        </div>
        <table style="table-layout: auto; margin-top: 50px; width: 100%; border: 2px solid #016DB7; border-radius: 10px; width: 100%; height: 98px;">
            <tr>
                <td>
                    <img src="{{ public_path('assets/logo_asuransi.png') }}" alt="logo jasa raharja" style="width: 90px; height: 90px;">
                </td>
                <td style="text-align: center;">
                    <p style="font-size: 22px; color:#016DB7">SUDAH TERMASUK PREMI ASURANSU KECELAKAAN PT. JASA RAHARJA UNDANG - UNDANG NO. 33 TAHUN 1964</p>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
