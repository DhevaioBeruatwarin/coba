<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
        Config::$is3ds = env('MIDTRANS_IS_3DS', true);
    }

    public function createTransaction($orderId, $grossAmount, $customerDetails, $itemDetails)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $grossAmount,
            ],
            'customer_details' => $customerDetails,
            'item_details' => $itemDetails,
            'enabled_payments' => [
                'credit_card',
                'bca_va',
                'bni_va',
                'bri_va',
                'permata_va',
                'other_va',
                'gopay',
                'shopeepay',
                'qris'
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return $snapToken;
        } catch (\Exception $e) {
            throw new \Exception('Error creating Midtrans transaction: ' . $e->getMessage());
        }
    }

    public function getTransactionStatus($orderId)
    {
        try {
            return \Midtrans\Transaction::status($orderId);
        } catch (\Exception $e) {
            throw new \Exception('Error getting transaction status: ' . $e->getMessage());
        }
    }
}