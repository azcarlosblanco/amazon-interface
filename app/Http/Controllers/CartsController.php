<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\Cart;
use App\Entities\Order;
use App\Entities\Product;

class CartsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $carts = Cart::orderBy('created_at', 'ASC')->paginate(10);
        return view('home.carts.index', compact('carts'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function processed($id)
    {
        $cart = Cart::findOrFail($id);
        $cart->update(['processed' => true]);
        return redirect()
            ->back()
            ->with('success', 'El carrito ha cambiado su estatus a <procesado>');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cart = Cart::findOrFail($id);
        $orders = Order::where('cart_id', $cart->cart_id)->get();

        $products = array();

        foreach ($orders as $order) {
            $products[] = [
                'item' => Product::where('ml_id', $order->product_id)->first(),
                'order' => $order,
            ];
        }
        return view('home.alerts.products', compact('products'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Cart::destroy($id)) {
            throw new \Exception("Error Processing Request", 1);
        }

        return redirect()
            ->route('carritos.index')
            ->with('success', 'Carrito eliminado');
    }
}
