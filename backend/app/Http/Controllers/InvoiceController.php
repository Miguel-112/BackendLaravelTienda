<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\MotorcyclePart;

class InvoiceController extends Controller
{
    



//     public function index(Request $request)
// {
//     try {
//         $perPage = $request->query('perPage', 5);
//         $searchTerm = $request->query('searchTerm');

//         $invoice = Invoice::when($searchTerm != null && filled($searchTerm), function ($query) use ($searchTerm) {
//             return $query->whereHas('client', function ($query) use ($searchTerm) {
//                 $query->where('name', 'like', '%' . $searchTerm . '%');
//             });
//         })->paginate($perPage);

//         return response()->json([
//             'data' => $invoice->items(),
//             'current_page' => $invoice->currentPage(),
//             'last_page' => $invoice->lastPage()
//         ]);
//     } catch (\Exception $e) {
//         return response()->json(['error' => $e->getMessage()], 500);
//     }
// }




public function index(Request $request)
{
    try {
        $perPage = $request->query('perPage', 5);
        $searchTerm = $request->query('searchTerm');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $invoice = Invoice::with('client')
            ->when(isset($searchTerm) && !empty($searchTerm), function ($query) use ($searchTerm) {
                return $query->whereHas('client', function ($query) use ($searchTerm) {
                    $query->where('name', 'like', '%' . $searchTerm . '%');
                });
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->paginate($perPage);

        $formattedInvoices = collect($invoice->items())->map(function ($invoice) {
            return [
                'id' => $invoice->id,
                'total_sale' => $invoice->total_sale,
                'client_name' => $invoice->client->name,
                'created_at' => $invoice->created_at,
            ];
        });

        return response()->json([
            'data' => $formattedInvoices,
            'current_page' => $invoice->currentPage(),
            'last_page' => $invoice->lastPage()
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}













    
   


    public function store(Request $request)
{
    // Valida los campos requeridos
    $request->validate([
        'total_sale' => 'required',
        'client_id' => 'required',
        'invoice_details' => 'required|array',
        'invoice_details.*.name' => 'required',
        'invoice_details.*.cantidad' => 'required|integer',
        'invoice_details.*.iva' => 'required',
        'invoice_details.*.purchase_price' => 'required',
        'invoice_details.*.sale_price' => 'required',
        'invoice_details.*.saleprice_total' => 'required',
        'invoice_details.*.id_motorcycle_parts' => 'required|exists:motorcycle_parts,id',
    ]);

    $invoiceData = $request->only('total_sale', 'client_id');
    $invoiceDetailsData = $request->get('invoice_details', []);
    $productsToSell = [];

    foreach ($invoiceDetailsData as $invoiceDetailData) {
        $product = MotorcyclePart::find($invoiceDetailData['id_motorcycle_parts']);
        if ($product) {
            $productsToSell[] = $product;
        }
    }

    $invoice = Invoice::create($invoiceData);

    foreach ($invoiceDetailsData as $invoiceDetailData) {
        $invoice->invoiceDetails()->create($invoiceDetailData);
    }

    foreach ($productsToSell as $product) {
        // Actualiza la cantidad descontando la cantidad vendida
        $product->quantity -= $invoiceDetailData['cantidad'];
        // Guarda los cambios en el registro del producto
        $product->save();
    }

    return response()->json($invoice, 201);
}





    
    public function show(Invoice $invoice)
    {
        return response()->json($invoice);
    }

    public function update(Request $request, Invoice $invoice)
    {
        $invoice->update($request->all());
        return response()->json($invoice);
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return response()->json(null, 204);
    }
}
