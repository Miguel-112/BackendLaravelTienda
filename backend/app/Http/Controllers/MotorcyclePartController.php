<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MotorcyclePart;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Storage;

use Intervention\Image\Facades\Image;


use Illuminate\Support\Facades\DB;




class MotorcyclePartController extends Controller
{


    public function index(Request $request)
    {

        try {

            $category = $request->query('category');
            $brand = $request->query('brand');
            $perPage = $request->query('perPage', 5);
            $searchTerm = $request->query('searchTerm');

            if($searchTerm != null && $category == 0 && $brand == 0){
                $motorcyclepart = MotorcyclePart::when($searchTerm != null && filled($searchTerm), function ($query) use ($searchTerm) {
                    return $query->where('name', 'like', '%' . $searchTerm . '%');
                })
                ->paginate($perPage);
        
                return response()->json([
                    'data' => $motorcyclepart->items(),
                    'current_page' => $motorcyclepart->currentPage(),
                    'last_page' => $motorcyclepart->lastPage()
                ]);

            }



             $motorcyclepart = MotorcyclePart::when($category > 0, function ($query) use ($category) {
                return $query->where('category_id', $category);
            })
                ->when($brand > 0, function ($query) use ($brand) {
                    return $query->where('brand_id', $brand);
                })
                ->paginate($perPage);

            return response()->json([
                'data' => $motorcyclepart->items(),
                'current_page' => $motorcyclepart->currentPage(),
                'last_page' => $motorcyclepart->lastPage()
            ]);

           
    

            /*  $motorcyclepart = MotorcyclePart::paginate($perPage);
            return response()->json([
                'data' => $motorcyclepart->items(),
                'current_page' => $motorcyclepart->currentPage(),
                'last_page' => $motorcyclepart->lastPage()
            ]); */ 
            
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }




    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Obtener los datos del request
        $data = $request->all();

        // Validar los datos
        $validator = \Illuminate\Support\Facades\Validator::make($data, [
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'provider_id' => 'required|integer',
            'brand_id' => 'required|integer',
            'purchase_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'quantity' => 'required|integer',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'iva' => 'required|numeric',
        ]);



        // Si la validación falla, enviar una respuesta con el error
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        // Crear la motorcyclepart
        $motorcyclepart = new MotorcyclePart();
        $motorcyclepart->name = $data['name'];
        $motorcyclepart->category_id = $data['category_id'];
        $motorcyclepart->provider_id = $data['provider_id'];
        $motorcyclepart->brand_id = $data['brand_id'];
        $motorcyclepart->purchase_price = $data['purchase_price'];
        $motorcyclepart->sale_price = $data['sale_price'];
        $motorcyclepart->quantity = $data['quantity'];
        $motorcyclepart->iva = $data['iva'];

        // Guardar la imagen
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $path = public_path('/uploads/motorcycleparts/' . $filename);
            $image->storeAs('public/images', $filename, ['visibility' => 'public']);
            $motorcyclepart->image = $filename;
        }



        // Guardar la motorcyclepart
        $motorcyclepart->save();

        // Enviar una respuesta con la motorcyclepart creada
        return response()->json(['message' => 'MotorcyclePart created successfully', 'motorcyclepart' => $motorcyclepart, 'image_url' => Storage::url("images/$filename")], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
