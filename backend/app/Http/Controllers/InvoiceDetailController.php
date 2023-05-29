<?php

namespace App\Http\Controllers;
use App\Models\Invoice;
use App\Models\InvoiceDetail;

use Illuminate\Http\Request;

class InvoiceDetailController extends Controller
{
    // public function index(Request $invoice)
    // {
    //     $invoiceDetails = $invoice->invoiceDetails;
    //     return response()->json($invoiceDetails);
    // }

    public function index(Request $request)
{
    $invoiceId = $request->input('id'); 

    // Obtener la factura maestra basada en el ID
    $invoice = Invoice::find($invoiceId);

    if (!$invoice) {
        // Si no se encuentra la factura, puedes devolver una respuesta adecuada
        return response()->json(['error' => 'Factura no encontrada'], 404);
    }

    // Obtener los detalles de la factura relacionados
    $invoiceDetails = $invoice->invoiceDetails;

    // Devolver los detalles de la factura como respuesta JSON
    return response()->json($invoiceDetails);
}


    public function store(Request $request, Invoice $invoice)
    {
        $invoiceDetail = $invoice->invoiceDetails()->create($request->all());
        return response()->json($invoiceDetail, 201);
    }

    public function show(Invoice $invoice, InvoiceDetail $invoiceDetail)
    {
        return response()->json($invoiceDetail);
    }

    public function update(Request $request, Invoice $invoice, InvoiceDetail $invoiceDetail)
    {
        $invoiceDetail->update($request->all());
        return response()->json($invoiceDetail);
    }

    public function destroy(Invoice $invoice, InvoiceDetail $invoiceDetail)
    {
        $invoiceDetail->delete();
        return response()->json(null, 204);
    }
}
