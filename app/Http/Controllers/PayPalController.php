<?php
namespace App\Http\Controllers;

use App\Models\CustomerInfo;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalController extends Controller
{

    public function handlePayment(Request $request)
    {

        $quantity   = $request->quantity ?? 1;
        $subTotal   = $request->sub_total;
        $tax        = $request->tax ?? 0;
        $discount   = $request->discount ?? 0;
        $grandTotal = ($subTotal + $tax) - $discount;


        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal.success'),
                "cancel_url" => route('paypal.cancel'),
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => number_format($grandTotal, 2, '.', '')
                    ]
                ]
            ]
        ]);

        if (isset($response['id']) && $response['id'] != null) {


            session()->put('pending_order_data', [
                'customer_id'          => Auth::user()->id?? $request->customer_id,
                'first_name'           => $request->first_name,
                'last_name'            => $request->last_name,
                'country_region'       => $request->country_region,
                'address_line_one'     => $request->address_line_one,
                'sub_burb'             => $request->sub_burb,
                'state'                => $request->state,
                'post_code'            => $request->post_code,
                'email'                => $request->email,
                'product_id'           => $request->product_id,
                'product_variation_id' => $request->product_variation_id,
                'quantity'             => $quantity,
                'sub_total'            => $subTotal,
                'tax'                  => $tax,
                'discount'             => $discount,
                'grand_total'          => $grandTotal,
                'notes'                => $request->notes,
            ]);


            foreach ($response['links'] as $link) {
                if ($link['rel'] == 'approve') {
                    return response()->json([
                        'status' => 'success',
                        'paypal_url' => $link['href']
                    ]);
                }
            }
        }

        return response()->json(['status' => 'error', 'message' => 'Something went wrong'], 500);
    }


    public function paymentSuccess(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();


        $response = $provider->capturePaymentOrder($request['token']);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {


            $orderData = session()->get('pending_order_data');

            if ($orderData) {

                $customerInfo = CustomerInfo::updateOrCreate([
                    'customer_id'      => $orderData['customer_id'],
                    'first_name'       => $orderData['first_name'],
                    'last_name'        => $orderData['last_name'],
                    'country_region'   => $orderData['country_region'],
                    'address_line_one' => $orderData['address_line_one'],
                    'sub_burb'         => $orderData['sub_burb'],
                    'state'            => $orderData['state'],
                    'post_code'        => $orderData['post_code'],
                    'email'            => $orderData['email'],
                ]);


                $order = Order::create([
                    'invoice_no'           => 'INV-' . strtoupper(uniqid()),
                    'customer_id'          => $orderData['customer_id'],
                    'customer_info_id'     => $customerInfo->id,
                    'product_id'           => $orderData['product_id'],
                    'product_variation_id' => $orderData['product_variation_id'],
                    'quantity'             => $orderData['quantity'],
                    'sub_total'            => $orderData['sub_total'],
                    'tax'                  => $orderData['tax'],
                    'discount'             => $orderData['discount'],
                    'grand_total'          => $orderData['grand_total'],
                    'transaction_id'       => $response['id'],
                    'payment_method'       => 'paypal',
                    'payment_status'       => 'paid',
                    'order_status'         => 'processing',
                    'notes'                => $orderData['notes'],
                ]);

                session()->forget('pending_order_data');

                $successUrl = env('PAYPAL_SUCCESS_REDIRECT_URL', 'http://localhost:3000/payment-success');
                return redirect()->away($successUrl . '?status=success&invoice_no=' . $order->invoice_no);
            }
        }

        $cancelUrl = env('PAYPAL_CANCEL_REDIRECT_URL', 'http://localhost:3000/payment-cancel');
        return redirect()->away($cancelUrl . '?status=failed');
    }


    public function paymentCancel()
    {
        session()->forget('pending_order_data');
        $cancelUrl = env('PAYPAL_CANCEL_REDIRECT_URL', 'http://localhost:3000/payment-cancel');
        return redirect()->away($cancelUrl . '?status=cancelled');
    }
}
