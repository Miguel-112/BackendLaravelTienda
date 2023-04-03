<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Marca;

class MarcaController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $marcas = Marca::all();
        return response()->json(['marca' => $marcas]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:marcas|max:255',
            'description' => 'nullable',
        ]);

        $marca = Marca::create($validatedData);

        return response()->json(['marca' => $marca], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Marca $marca)
    {
        return response()->json(['marca' => $marca]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Marca $marca)
    {
        $validatedData = $request->validate([
            'name' => [
                'required',
                Rule::unique('marcas')->ignore($marca->id),
                'max:255',
            ],
            'description' => 'nullable',
        ]);

        $marca->update($validatedData);

        return response()->json(['marca' => $marca], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $marca = Marca::findOrFail($id);
        $marca->delete();


        return response()->json(['marca'], 204);
    }
}
