<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;

class PaymentController extends Controller
{
    protected $razorpay;

    public function __construct()
    {
        $this->razorpay = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
    }

    /**
     * Store payment details after successful payment
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'payment_id' => 'required',
            'order_id' => 'nullable',
            'signature' => 'nullable',
            'amount' => 'required',
            'currency' => 'nullable',
            'product_id' => 'required',
        ]);

        DB::transaction(function () use ($data) {
            Payment::updateOrCreate(
                ['payment_id' => $data['payment_id']],
                [
                    'order_id' => $data['order_id'] ?? null,
                    'signature' => $data['signature'] ?? null,
                    'product_id' => $data['product_id'],
                    'amount' => $data['amount'] / 100,
                    'currency' => $data['currency'] ?? 'INR',
                    'status' => 'success',
                ]
            );
        });

        return response()->json(['message' => 'Payment saved successfully']);
    }

    /**
     * Initiate refund for a payment by Razorpay Payment ID
     */

    /**
     * Handle Razorpay webhook calls
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('X-Razorpay-Signature');
        $secret = env('RAZORPAY_WEBHOOK_SECRET');

        // Verify webhook signature
        $expectedSignature = hash_hmac('sha256', $payload, $secret);

        if (!hash_equals($expectedSignature, $signature)) {
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        $event = $request->input('event');

        switch ($event) {
            case 'payment.authorized':
                // Payment has been authorized but not captured yet
                $payment = $request->input('payload.payment.entity');
                DB::transaction(function () use ($payment) {
                    Payment::updateOrCreate(
                        ['payment_id' => $payment['id']],
                        [
                            'order_id' => $payment['order_id'] ?? null,
                            'product_id' => 1, // Map properly in your logic
                            'amount' => $payment['amount'] / 100,
                            'currency' => $payment['currency'],
                            'status' => 'authorized',
                        ]
                    );
                });
                break;

            case 'payment.captured':
                // Payment is successful and captured
                $payment = $request->input('payload.payment.entity');
                DB::transaction(function () use ($payment) {
                    Payment::updateOrCreate(
                        ['payment_id' => $payment['id']],
                        [
                            'order_id' => $payment['order_id'] ?? null,
                            'product_id' => 1,
                            'amount' => $payment['amount'] / 100,
                            'currency' => $payment['currency'],
                            'status' => 'captured',
                        ]
                    );
                });
                break;

            case 'payment.failed':
                $payment = $request->input('payload.payment.entity');
                DB::transaction(function () use ($payment) {
                    Payment::updateOrCreate(
                        ['payment_id' => $payment['id']],
                        [
                            'order_id' => $payment['order_id'] ?? null,
                            'product_id' => 1,
                            'amount' => $payment['amount'] / 100,
                            'currency' => $payment['currency'],
                            'status' => 'failed',
                        ]
                    );
                });
                break;

            case 'payment.refunded':
                $payment = $request->input('payload.payment.entity');
                Payment::where('payment_id', $payment['id'])
                    ->update(['status' => 'refunded']);
                break;

            case 'refund.created':
            case 'refund.processed':
                $refund = $request->input('payload.refund.entity');
                Payment::where('payment_id', $refund['payment_id'])
                    ->update(['status' => 'refunded']);
                break;

            default:
                // For unhandled events, just acknowledge
                break;
        }

        return response()->json(['status' => 'ok']);
    }
}
