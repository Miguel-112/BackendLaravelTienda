<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {

         $client = Client::all();
        return response()->json(['client' => $client]); 
    
        
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:100',
            'tel' => 'required|max:10',
            'address' => 'required|max:200',
        ]);
    
        $client= Client::create($validatedData);
    
        return response()->json([
            'message' => 'Proveedor creado correctamente',
            'data' => $client
        ], 201);
    }

    public function update(Request $request, Client $client)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|max:255',
                'email' => 'required|email|max:100',
                'tel' => 'required|max:20',
                'address' => 'required|max:200',
            ]);
    
            $client->update($validatedData);
    
            return response()->json(['client' => $client], 200);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    

    
    
    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();
        return response()->json(['client'], 204);
    }
}
