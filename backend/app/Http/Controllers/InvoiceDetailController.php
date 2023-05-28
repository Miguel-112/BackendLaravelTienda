<?php

namespace App\Http\Controllers;
use App\Models\Invoice;
use App\Models\InvoiceDetail;

use Illuminate\Http\Request;

class InvoiceDetailController extends Controller
{
    public function index(Invoice $invoice)
    {
        $invoiceDetails = $invoice->invoiceDetails;
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
