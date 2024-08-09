<?php

namespace App\Services;

use App\Models\Pesanan;

class OrderService
{
    protected $pesanan;
    public function __construct()
    {
        $this->pesanan = Pesanan::with('jadwal', 'jadwal.master_rute', 'jadwal.master_mobil', 'jadwal.master_supir', 'user', 'pembayaran', 'penumpang.kursi');
    }
    public function getAllOrders()
    {
        $pesanan = $this->pesanan->where('user_id', auth()->user()->id)->get();
        $data = $pesanan->map(function ($order) {
            return [
                'created_at' => $order->created_at,
                'kode_pesanan' => $order->kode_pesanan,
                'kota_asal' => $order->jadwal->master_rute->kota_asal,
                'kota_tujuan' => $order->jadwal->master_rute->kota_tujuan,
                'tanggal' => $order->jadwal->tanggal_berangkat,
                'jam' => $order->jadwal->jam_berangkat,
                'status' => $order->status,
            ];
        });

        return $data->toArray();
    }

    public function getOrderDetails($orderCode)
    {
        $pesanan = $this->pesanan->where('user_id', auth()->user()->id)
            ->where('kode_pesanan', $orderCode)
            ->first();

        if (!$pesanan) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan',
            ]);
        }
        $seatTaken =[];
        $data = [
            'pembayaran' => [
                'status' => $pesanan->pembayaran->status,
                'metode' => $pesanan->metode->metode,
                'nominal' => $pesanan->pembayaran->amount
            ],
            'penumpang' => $pesanan->penumpang->map(function ($penumpang) use(&$seatTaken){
                array_push($seatTaken, $penumpang->kursi->nomor_kursi);
                return [
                    'nama' => $penumpang->nama,
                    'nik'=> $penumpang->nik,
                    'no_telp' => $penumpang->no_telp,
                    'kursi' => $penumpang->kursi->nomor_kursi,
                ];
            }),
            'pesanan' => [
                'mobil' => $pesanan->jadwal->master_mobil->type,
                'jam' => $pesanan->jadwal->waktu_keberangkatan,
                'tanggal' => $pesanan->jadwal->tanggal_berangkat,
                'kota_asal' => $pesanan->jadwal->master_rute->kota_asal,
                'kota_tujuan' => $pesanan->jadwal->master_rute->kota_tujuan,
                'titik_jemput' => $pesanan->titikJemput->nama,
                'titik_antar' => $pesanan->titikAntar->nama,
                'kursi' => $seatTaken
            ],
        ];

        return $data;
    }
}
