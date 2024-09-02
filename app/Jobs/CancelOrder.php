<?php

namespace App\Jobs;

use App\Models\Kursi;
use App\Models\Pesanan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CancelOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $pesanan;
    public function __construct(Pesanan $pesanan)
    {
        $this->pesanan = $pesanan;
    }

    public function handle(): void
    {
        Log::info("CancelOrder jobs started");
        if ($this->pesanan->status == 'Menunggu Pembayaran') {
            $this->pesanan->status = 'Gagal';
            $this->pesanan->save();

           $penumpangs = $this->pesanan->penumpang();
           $penumpangs->each(function ($penumpang) {
              $kursi = Kursi::where('id', $penumpang->kursi_id)->first();
              $kursi->update([
                  'status' => 'kosong',
              ]);
           });
        }
    }
}
