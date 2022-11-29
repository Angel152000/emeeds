<?php

namespace App\Http\Controllers;

use App\Models\Atencion;
use App\Models\Especialistas;
use App\Models\Fichas;
use App\Models\User;
use Illuminate\Http\Request;

class FichasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $objFichas = new Fichas();
        $especialista = Especialistas::where('id_user',)->first();
        
        $fichas = $objFichas->getFichasByEspecialista();

        return view('ficha.index');
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
     * @param  \App\Models\Fichas  $fichas
     * @return \Illuminate\Http\Response
     */
    public function show(Fichas $fichas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fichas  $fichas
     * @return \Illuminate\Http\Response
     */
    public function edit(Fichas $fichas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fichas  $fichas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fichas $fichas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fichas  $fichas
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fichas $fichas)
    {
        //
    }
}
