<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\Alert;
use App\Entities\Order;
use App\Entities\Product;
use App\Http\Requests\StoreAlertRequest;

class AlertsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $alerts = Alert::orderBy('created_at', 'DESC')->paginate(15);
        return view('home.alerts.index', compact('alerts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('home.alerts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAlertRequest $request)
    {

        $alert = new Alert($request->all());
        $alert->save();

        return redirect()
            ->route('alertas.index')
            ->with('success', 'Alerta creada con éxito.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $alert = Alert::findOrFail($id)->first();

        $alert->update([
            'read' => true,
        ]);

        if (is_null($alert->order)) {
            return redirect()
                ->back()
                ->with('alert', 'La Alerta no esta atada a una orden.');
        }

        $order = Order::findByResource($alert->order)->first();
        $product = Product::isMeliId($order->product_id)->first();

        if (is_null($product)) {
            return redirect()
                ->back()
                ->with('alert', 'La orden de la alerta no esta atada a un producto');
        }

        return view('home.products.show', [
            'product' => $product->detail,
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Alert::destroy($id)) {
            throw new \Exception("Error Processing Request", 1);
        }

        return redirect()
            ->route('alertas.index')
            ->with('success', 'Alerta eliminada');
    }
}
