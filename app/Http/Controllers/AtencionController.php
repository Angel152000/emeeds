<?php

namespace App\Http\Controllers;

use App\Models\atencion;
use Illuminate\Http\Request;

class AtencionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('atencion.index');
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
     * @param  \App\Models\atencion  $atencion
     * @return \Illuminate\Http\Response
     */
    public function show(atencion $atencion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\atencion  $atencion
     * @return \Illuminate\Http\Response
     */
    public function edit(atencion $atencion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\atencion  $atencion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, atencion $atencion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\atencion  $atencion
     * @return \Illuminate\Http\Response
     */
    public function destroy(atencion $atencion)
    {
        //
    }
}
