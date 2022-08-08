<?php

namespace Azuriom\Plugin\NowPayments;

use Azuriom\Plugin\Shop\Cart\Cart;
use Azuriom\Plugin\Shop\Models\Payment;
use Azuriom\Plugin\Shop\Payment\PaymentMethod;
use Illuminate\Http\Request;

class NowPaymentsMethod extends PaymentMethod
{
    /**
     * The payment method id name.
     *
     * @var string
     */
    protected $id = 'nowpayments';

    /**
     * The payment method display name.
     *
     * @var string
     */
    protected $name = 'NowPayments';

    public function startPayment(Cart $cart, float $amount, string $currency)
    {
        $payment = $this->createPayment($cart, $amount, $currency);

        $api = new NowPaymentsAPI($this->gateway->data['api-key']);

        $invoice = $api->createInvoice([
            'price_amount' => $amount,
            'price_currency' => $currency,
            'ipn_callback_url' => route('shop.payments.notification', ['gateway' => 'nowpayments']),
            'order_id' => $payment->id,
            'order_description' => $this->getPurchaseDescription($payment->id),
            'success_url' => route('shop.payments.success', $this->id),
            'cancel_url' => route('shop.payments.failure', $this->id),
        ]);

        $invoice = json_decode($invoice);

        return redirect()->away($invoice->invoice_url);
    }

    public function notification(Request $request, ?string $paymentId)
    {
        $body = $request->all();
        ksort($body);
        $json = json_encode($body, JSON_UNESCAPED_SLASHES);
        $recived_hmac = $_SERVER['HTTP_X_NOWPAYMENTS_SIG'];
        $hmac = hash_hmac('sha512', $json, $this->gateway->data['ipn-secret']);

        abort_unless($recived_hmac === $hmac, 403);

        $payment = Payment::findOrFail($body['order_id']);

        if ($body['payment_status'] === 'confirmed') {
            return $this->processPayment($payment, $paymentId);
        }

        return response()->json(['status' => 'waiting nowpayments confirmation.']);
    }

    public function success(Request $request)
    {
        return view('shop::payments.success');
    }

    public function rules()
    {
        return [
            'api-key' => ['required', 'string'],
            'ipn-secret' => ['required', 'string'],
        ];
    }

    public function image()
    {
        return asset('plugins/nowpayments/img/logo.svg');
    }

    public function view()
    {
        return 'nowpayments::admin.index';
    }
}
