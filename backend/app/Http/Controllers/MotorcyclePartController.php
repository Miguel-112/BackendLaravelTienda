<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

use App\Models\MotorcyclePart;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Storage;

/* use Intervention\Image\Facades\Image;  */
use Intervention\Image\ImageManagerStatic as Image;






use Illuminate\Support\Facades\DB;
use Psy\Readline\Hoa\Console;

class MotorcyclePartController extends Controller
{


    public function index(Request $request)
    {

        try {

            $category = $request->query('category');
            $brand = $request->query('brand');
            $perPage = $request->query('perPage', 5);
            $searchTerm = $request->query('searchTerm');

            /* if ($searchTerm != null && $category == 0 && $brand == 0) { */

            $motorcyclepart = MotorcyclePart::when($searchTerm != null && filled($searchTerm), function ($query) use ($searchTerm) {
                return $query->where('name', 'like', '%' . $searchTerm . '%');
            })
                ->when($category != null && $category > 0, function ($query) use ($category) {
                    return $query->where('category_id', $category);
                })
                ->when($brand != null && $brand > 0, function ($query) use ($brand) {
                    return $query->where('brand_id', $brand);
                })
                ->with(['brand', 'category', 'provider'])
                ->paginate($perPage);

            return response()->json([
                'data' => $motorcyclepart->items(),
                'current_page' => $motorcyclepart->currentPage(),
                'last_page' => $motorcyclepart->lastPage()
            ]);



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
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }





    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $name = $request->input('name');
        $category_id = intval($request->input('category_id'));
        $provider_id = intval($request->input('provider_id'));
        $brand_id = intval($request->input('brand_id'));
        $purchase_price = floatval($request->input('purchase_price'));
        $sale_price = floatval($request->input('sale_price'));
        $quantity = intval($request->input('quantity'));
        $iva = floatval($request->input('iva'));


        echo ($request->input('image'));

        $validator = \Illuminate\Support\Facades\Validator::make([
            'name' => $name,
            'category_id' => $category_id,
            'provider_id' => $provider_id,
            'brand_id' => $brand_id,
            'purchase_price' => $purchase_price,
            'sale_price' => $sale_price,
            'quantity' => $quantity,
            'image' => $request->file('image'),
            'iva' => $iva,
        ], [
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



        if ($validator->fails()) {

            $errors = $validator->errors()->all();
            $detailed_errors = [];

            // Agregar detalles a los errores de validación
            foreach ($validator->errors()->keys() as $key) {
                $detailed_errors[$key] = [
                    'message' => $validator->errors()->first($key),
                    /* 'value' => $data[$key] */
                ];
            }

            return response([
                'errors' => $errors,
                'detailed_errors' => $detailed_errors
            ], 422);
        }

        // Crear la motorcyclepart
        $motorcyclepart = new MotorcyclePart();
        $motorcyclepart->name = $name;
        $motorcyclepart->category_id = $category_id;
        $motorcyclepart->provider_id = $provider_id;
        $motorcyclepart->brand_id = $brand_id;
        $motorcyclepart->purchase_price = $purchase_price;
        $motorcyclepart->sale_price = $sale_price;
        $motorcyclepart->quantity = $quantity;
        $motorcyclepart->iva = $iva;



        if ($request->hasFile('image')) {

            $image = $request->file('image');
            // $filename = $image->getClientOriginalName();
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images', $filename, ['visibility' => 'public']);
            $motorcyclepart->image = $filename;
        }


        // Guardar la motorcyclepart
        $motorcyclepart->save();

        // Enviar una respuesta con la motorcyclepart creada
        return response()->json(['message' => 'MotorcyclePart created successfully', 'motorcyclepart' => $motorcyclepart, 'image_url' => Storage::url("images/$filename")], 201);
    }




    public function show(string $id)
    {
    }


 public function update(Request $request, $id)
 {
     $motorcyclepart = MotorcyclePart::find($id);
 
     if (!$motorcyclepart) {
         return response()->json(['message' => 'Repuesto no encontrado'], 404);
     }
 
     $data = $request->validate([
         'name' => 'required|string|max:255',
         'category_id' => 'required|integer|exists:categories,id',
         'provider_id' => 'required|integer|exists:providers,id',
         'brand_id' => 'required|integer',
         'purchase_price' => 'required|numeric',
         'sale_price' => 'required|numeric',
         'quantity' => 'required|integer',
         'iva' => 'required|numeric',
         'image' => 'nullable|string',
          
     ]);
 
     // ...

     if ($request->has('image')) {

        if ($motorcyclepart->image && Storage::exists("public/images/$motorcyclepart->image")) {
            Storage::delete("public/images/$motorcyclepart->image");
        }

        $imageData = $request->input('image');
        $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
        $imageData = str_replace(' ', '+', $imageData);
        $imageData = base64_decode($imageData);
    
        // Generar nuevo nombre de archivo
        $filename = time() . '.' . 'jpg';
    
        // Almacenar imagen en el servidor
        Storage::disk('public')->put("images/$filename", $imageData);
    
        // Actualizar el registro de la motocicleta con el nuevo nombre de imagen
        $motorcyclepart->image = $filename;
    }
 
    
 
     $motorcyclepart->name = $data['name'];
     $motorcyclepart->category_id = $data['category_id'];
     $motorcyclepart->provider_id = $data['provider_id'];
     $motorcyclepart->brand_id = $data['brand_id'];
     $motorcyclepart->purchase_price = $data['purchase_price'];
     $motorcyclepart->sale_price = $data['sale_price'];
     $motorcyclepart->quantity = $data['quantity'];
     $motorcyclepart->iva = $data['iva'];
 
     $motorcyclepart->save();
 
     return response()->json(['message' => 'Repuesto actualizado con éxito', 'data' => $motorcyclepart], 200);
 }


    public function destroy(string $id)
    {
        $motorcyclepart = MotorcyclePart::findOrFail($id);
        $motorcyclepart->delete();
        return response()->json(['motorcyclepart'], 204);
        
    }
}
