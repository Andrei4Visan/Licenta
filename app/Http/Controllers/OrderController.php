<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use PhpParser\Builder\Function_;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        /** var \App\Models\User $user */
        $user = $request->user();

        $orders = Order::query()
            ->where(['created_by' => $user->id])
            ->orderBy('created_at','desc')
            ->paginate(5);
        return view('order.index', compact('orders'));
    }

    public function view(Request$request, Order $order)
    {
        /** var \App\Models\User $user */
        $user = \request()->user();
        if($order->created_by !== $user->id){
            return response("Nu ai permisiunea de a vedea comanda!",403);
        }
        return view('order.view', compact('order'));
    }

    //
}
