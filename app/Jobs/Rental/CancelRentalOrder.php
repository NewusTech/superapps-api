<?php

namespace App\Jobs\Rental;

use App\Models\Rental;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CancelRentalOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $pesanan;
    public function __construct(Rental $pesanan)
    {
        Log::info("CancelRentalOrder class jobs started");
        $this->pesanan = $pesanan;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
      try {
        Log::info("Handle CancelRentalOrder jobs started");

        if ($this->pesanan->pembayaran->status == 'Menunggu Pembayaran') {
            $this->pesanan->pembayaran->status = 'Gagal';
            $this->pesanan->pembayaran->save();

            Log::info("CancelRentalOrder jobs Success");
        }
      } catch (\Throwable $th) {
          Log::error("CancelRentalOrder jobs error: {$th->getMessage()}");
          throw $th;
      } finally{
          Log::info("CancelRentalOrder jobs ended");
      }
    }
}
