<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RazorpayService
{
    protected ?string $keyId;
    protected ?string $keySecret;
    protected bool $isConfigured;

    public function __construct()
    {
        $this->keyId = config('services.razorpay.key_id') ?: env('RAZORPAY_KEY_ID');
        $this->keySecret = config('services.razorpay.key_secret') ?: env('RAZORPAY_KEY_SECRET');
        $this->isConfigured = !empty($this->keyId) && !empty($this->keySecret) && !str_starts_with($this->keyId, 'rzp_test_placeholder');
    }

    public function getKeyId(): ?string
    {
        return $this->keyId ?: 'rzp_test_jacario_mock';
    }

    public function isLiveReady(): bool
    {
        return $this->isConfigured;
    }

    public function createRazorpayOrder(Order $order): array
    {
        $amountInPaise = (int) round($order->total_amount * 100);

        if (!$this->isConfigured) {
            // Simulated test gateway response for smooth local development / testing without live keys
            $mockOrderId = 'order_mock_' . uniqid() . '_' . $order->id;
            
            $payment = $order->latestPayment;
            if ($payment) {
                $payment->update([
                    'razorpay_order_id' => $mockOrderId,
                ]);
            }

            return [
                'id' => $mockOrderId,
                'amount' => $amountInPaise,
                'currency' => 'INR',
                'receipt' => $order->order_number,
                'status' => 'created',
                'is_mock' => true,
            ];
        }

        try {
            $response = Http::withBasicAuth($this->keyId, $this->keySecret)
                ->post('https://api.razorpay.com/v1/orders', [
                    'amount' => $amountInPaise,
                    'currency' => 'INR',
                    'receipt' => $order->order_number,
                    'notes' => [
                        'order_id' => (string) $order->id,
                        'customer_email' => $order->customer_email,
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $payment = $order->latestPayment;
                if ($payment) {
                    $payment->update([
                        'razorpay_order_id' => $data['id'],
                    ]);
                }
                return array_merge($data, ['is_mock' => false]);
            }

            Log::error('Razorpay Order Creation Failed: ' . $response->body());
            throw new Exception('Unable to initiate Razorpay payment order: ' . ($response->json('error.description') ?? 'Unknown gateway error'));
        } catch (Exception $e) {
            Log::error('Razorpay exception: ' . $e->getMessage());
            throw $e;
        }
    }

    public function verifyPaymentSignature(string $razorpayOrderId, string $razorpayPaymentId, string $signature): bool
    {
        if (str_starts_with($razorpayOrderId, 'order_mock_')) {
            // Valid in mock / simulation environment
            return true;
        }

        if (!$this->isConfigured) {
            return true;
        }

        $expectedSignature = hash_hmac('sha256', $razorpayOrderId . '|' . $razorpayPaymentId, $this->keySecret);
        return hash_equals($expectedSignature, $signature);
    }
}
