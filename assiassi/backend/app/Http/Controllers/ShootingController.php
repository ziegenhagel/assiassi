<?php

namespace App\Http\Controllers;

use App\Models\Shooting;
use Illuminate\Http\Request;

class ShootingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        return Shooting::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shooting  $shooting
     * @return \Illuminate\Http\Response
     */
    public function show(Shooting $shooting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shooting  $shooting
     * @return \Illuminate\Http\Response
     */
    public function edit(Shooting $shooting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shooting  $shooting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shooting $shooting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shooting  $shooting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shooting $shooting)
    {
        //
    }
}
