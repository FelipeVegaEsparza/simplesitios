<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CancelExpiredOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $expiredOrders = Order::expiring()->get();
        
        $count = 0;
        foreach ($expiredOrders as $order) {
            try {
                $order->cancel('Orden cancelada automáticamente por expiración (48 horas sin pago)');
                $count++;
            } catch (\Exception $e) {
                Log::error("Error cancelando orden #{$order->id}: " . $e->getMessage());
            }
        }
        
        if ($count > 0) {
            Log::info("CancelExpiredOrders: {$count} orden(es) cancelada(s) automáticamente");
        }
    }
}
