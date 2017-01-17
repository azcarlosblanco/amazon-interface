<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\Product;
use App\Entities\Parameter;
use App\Entities\MeliUpdate;

class ParametersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parameters = Parameter::findOrfail(1)->first()->toArray();
        return view('home.parameters.index', compact('parameters'));
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $isValid = $this->validateParameters($id);

        if (!$isValid) {
            return redirect('/parametros')
                ->with('alert', 'Parametro invalído');
        }

        $parameters = Parameter::findOrFail(1)->first()->toArray();
        $toModify = array(
            'key' => $id,
            'value' => $parameters[$id],
        );

        return view('home.parameters.edit', compact('toModify'));
    }

    /**
     * [validateParameters description]
     * @param [type] $id [description]
     */
    protected function validateParameters($id)
    {
        $validParameters = ['TRM', 'tax_usa', 'iva_co', 'costo_envio_kg', 'default_weight', 'utilidad', 'comision_meli', 'comision_linio'];
        $isValid = false;

        foreach ($validParameters as $validParameter) {
            if ($validParameter == $id) {
                $isValid = true;
            }
        }

        return $isValid;
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
        $isValid = $this->validateParameters($id);

        if (!$isValid) {
            return redirect('/parametros')
                ->with('alert', 'Parametro invalído');
        }

        $parameters = Parameter::findOrFail(1)->first();

        if ($parameters->update($request->toArray())) {

            $products = Product::where('ml_p', true)->get();
            foreach ($products as $product) {
                MeliUpdate::create(['meli_id' => $product->ml_id]);
            }

            return redirect()
                ->route('parametros.index')
                ->with('success', 'Parametro actualizado con exíto');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
