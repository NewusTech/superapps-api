<?php

namespace App\Services;

use App\Models\Pesanan;
use Carbon\Carbon;

class OrderService
{
    protected $pesanan;
    public function __construct()
    {
        $this->pesanan = Pesanan::with('jadwal', 'jadwal.master_rute', 'jadwal.master_mobil', 'jadwal.master_supir', 'user', 'pembayaran', 'penumpang.kursi');
    }
    public function getAllOrders($status)
    {
        $query = $this->pesanan->newQuery();
        $this->isAdmin() ? $query = $query->where('status', $status) : $query = $query->where('user_id', auth()->user()->id)->where('status', $status);
        $query = $query->orderBy('created_at', 'desc')->get();
        $data = $query->map(function ($order) {
            return [
                'created_at' => $order->created_at,
                'kode_pesanan' => $order->kode_pesanan,
                'kota_asal' => $order->jadwal->master_rute->kota_asal,
                'kota_tujuan' => $order->jadwal->master_rute->kota_tujuan,
                'tanggal' => $order->jadwal->tanggal_berangkat,
                'jam' => $order->jadwal->waktu_keberangkatan,
                'status' => $order->status,
            ];
        });

        return $data->toArray();
    }

    protected function isAdmin(){
        return str_contains(auth()->user()->roles->first()->name, 'Admin');
    }

    public function getOrderDetails($orderCode)
    {
        $user = auth()->user();
        if (str_contains($user->roles->first()->name, 'Admin')) {
            $pesanan = $this->pesanan->where('kode_pesanan', $orderCode)->first();
        } else {
            $pesanan = $this->pesanan
                ->where('user_id', $user->id)
                ->where('kode_pesanan', $orderCode)
                ->first();
        }
        if (!$pesanan) {
            return $pesanan;
        }

        $seatTaken = [];
        $data = [
            'pembayaran' => [
                'status' => $pesanan->pembayaran?->status ?? $pesanan->status,
                'metode' => $pesanan->metode?->metode ?? null,
                'kode_pembayaran' => $pesanan->pembayaran?->kode_pembayaran ?? null,
                'payment_link' => $pesanan->pembayaran?->payment_link ?? null,
                'created_at' => $pesanan->pembayaran?->created_at ?? null,
                'expired_at' => Carbon::parse($pesanan->created_at)->addMinutes(15) ?? null,
                'nominal' => $pesanan->pembayaran->amount ?? $pesanan->jadwal->master_rute->harga * $pesanan->penumpang->count(),
                'link_tiket' => "https://backend-superapps.newus.id/e-tiket/{$pesanan->pembayaran?->kode_pembayaran}",
                'link_invoice' => "https://backend-superapps.newus.id/invoice/{$pesanan->pembayaran?->kode_pembayaran}",
            ],
            'penumpang' => $pesanan->penumpang->map(function ($penumpang) use (&$seatTaken) {
                array_push($seatTaken, $penumpang->kursi->nomor_kursi);
                return [
                    'nama' => $penumpang->nama,
                    'nik' => $penumpang->nik,
                    'no_telp' => $penumpang->no_telp,
                    'kursi' => $penumpang->kursi->nomor_kursi,
                ];
            }),
            'pesanan' => [
                'mobil' => $pesanan->jadwal->master_mobil->type,
                'kode_pesanan' => $pesanan->kode_pesanan,
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
