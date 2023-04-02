<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Provider;
use Illuminate\Validation\Rule;

class ProviderController extends Controller
{
    
    public function index()
    {

         $provider = Provider::all();
        return response()->json(['provider' => $provider]); 
    
        
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:providers|max:100',
            'tel' => 'required|max:10',
            'address' => 'required|max:200',
        ]);
    
        $provider = Provider::create($validatedData);
    
        return response()->json([
            'message' => 'Proveedor creado correctamente',
            'data' => $provider
        ], 201);
    }


    public function update(Request $request, Provider $provider)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:100',
            'tel' => 'required|max:20',
            'address' => 'required|max:200',
           
        ]);

        $provider->update($validatedData);

        return response()->json(['provider' => $provider], 200);
    }

    
    
    public function destroy($id)
    {
        $provider = Provider::findOrFail($id);
        $provider->delete();
        return response()->json(['provider'], 204);
    }



   


    
   
   
}
