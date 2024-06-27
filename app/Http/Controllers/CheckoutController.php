<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Helpers\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckoutController extends Controller
{

    public function checkout(Request $request)
    {
        /** var \App\Models\User $user */
        $user = $request->user();
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        list($products, $cartItems) = Cart::getProductsAndCartItems();
        $orderItems=[];
        $lineItems = [];
        $totalPrice = 0;
        foreach ($products as $product) {
            $quantity = $cartItems[$product->id]['quantity'];
            $totalPrice += $product->price * $quantity;
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'ron',
                    'product_data' => [
                        'name' => $product->title,
                    ],
                    'unit_amount' => $product->price * 100, // Convert the price to bani (cents for RON)
                ],
                'quantity' => $cartItems[$product->id]['quantity'],
            ];
            $orderItems[] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $product->price
            ];
        }

        // Correct the URL formation to not include spaces
        $success_url = route('checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}';
        $cancel_url = route('checkout.failure');

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'], // Ensure to specify allowed payment methods
            'line_items' => [$lineItems],
            'mode' => 'payment',
            'success_url' => $success_url,
            'cancel_url' => $cancel_url,
            'customer_creation'=>'always',
        ]);
        // Create order
        $orderData = [
            'total_price' => $totalPrice,
            'status' => OrderStatus::Unpaid,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ];
        $order = Order::create($orderData);

        // Cream OrderItems
        foreach ($orderItems as $orderItem){
            $orderItem['order_id'] = $order->id;
            OrderItem::create($orderItem);
        }



        $paymentData = [
            'order_id' => $order->id,
            'amount' => $totalPrice,
            'status' => PaymentStatus::Pending,
            'type' => 'cc',
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'session_id' => $session->id,
        ];

        Payment::create($paymentData);

        CartItem::where(['user_id' => $user->id])->delete();



        return redirect($session->url);
    }

    public function success(Request $request)
    {
        /** var \App\Models\User $user */
        $user = $request->user();
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        try{
            $session_id = $request->get('session_id');
            $session = \Stripe\Checkout\Session::retrieve($session_id);

            if(!$session){
                return view('checkout.failure', ['message' => 'Id-ul sesiunii este invalid']);
            }

            $payment = Payment::query()
                ->where(['session_id' => $session_id])
                ->whereIn('status', [PaymentStatus::Pending, PaymentStatus::Paid])
                ->first();
            if(!$payment){
                throw new NotFoundHttpException();
            }

            if($payment->status === PaymentStatus::Pending->value){
                $this->updateOrderAndSession($payment);
            }

            $customer = \Stripe\Customer::retrieve($session->customer);
            return view('checkout.success', compact('customer'));

        }catch(\Exception $e){

            return view('checkout.failure',['message' => $e->getMessage()]);

        }

    }
    public function failure(Request $request)
    {
        return view('checkout.failure',['message' => ""]);
    }
    public function checkoutOrder(Order $order, Request $request)
    {
        /** var \App\Models\User $user */
        $user = $request->user();
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $lineItems = [];
        foreach ($order->items as $item){
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'ron',
                    'product_data' => [
                        'name' => $item->product->title,
                    ],
                    'unit_amount' => $item->unit_price * 100,
                ],
                'quantity' => $item->quantity,
            ];
        }
        $success_url = route('checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}';
        $cancel_url = route('checkout.failure');
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'], // Ensure to specify allowed payment methods
            'line_items' => [$lineItems],
            'mode' => 'payment',
            'success_url' => $success_url,
            'cancel_url' => $cancel_url,
            'customer_creation'=>'always',

        ]);

        $order->payment->session_id = $session->id;
        $order->payment->save();


        return redirect($session->url);

    }

    private function updateOrderAndSession(Payment $payment)
    {
        $payment->status = PaymentStatus::Paid;
        $payment->update();

        $order = $payment->order;

        $order->status = OrderStatus::Paid;
        $order->update();

    }




}
