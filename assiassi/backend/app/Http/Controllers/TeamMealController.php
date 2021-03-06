<?php

namespace App\Http\Controllers;

use App\Models\TeamMeal;
use Illuminate\Http\Request;

class TeamMealController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TeamMeal  $teamMeal
     * @return \Illuminate\Http\Response
     */
    public function show(TeamMeal $teamMeal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TeamMeal  $teamMeal
     * @return \Illuminate\Http\Response
     */
    public function edit(TeamMeal $teamMeal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TeamMeal  $teamMeal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TeamMeal $teamMeal)
    {
        $teamMeal=TeamMeal::firstOrCreate($teamMeal);
        return $teamMeal;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TeamMeal  $teamMeal
     * @return \Illuminate\Http\Response
     */
    public function destroy(TeamMeal $teamMeal)
    {
        //
    }
}
