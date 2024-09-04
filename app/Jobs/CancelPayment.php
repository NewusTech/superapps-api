<?php

namespace App\Jobs;

use App\Models\Kursi;
use App\Models\Pembayaran;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CancelPayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $pembayaran;

    /**
     * Create a new job instance.
     */
    public function __construct(Pembayaran $pembayaran)
    {
        $this->pembayaran = $pembayaran;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->pembayaran->status == 'Menunggu Pembayaran') {
            $this->pembayaran->status = 'Gagal';
            $this->pembayaran->save();

            $this->pembayaran->pesanan->status = 'Gagal';
            $this->pembayaran->pesanan->save();

            $penumpangs = $this->pembayaran->pesanan->penumpang();
            $penumpangs->each(function ($penumpang) {
                $kursi = Kursi::where('id', $penumpang->kursi_id)->first();
                $kursi->update([
                    'status' => 'kosong',
                ]);
            });
        }
    }
}
