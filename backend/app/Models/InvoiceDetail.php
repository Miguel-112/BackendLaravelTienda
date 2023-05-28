<?php

namespace App\Models;
use App\Models\Invoice;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    protected $fillable = [
        'name',
        'cantidad',
        'iva',
        'purchase_price',
        'sale_price',
        'saleprice_total',
        'invoice_id',
        'id_motorcycle_parts',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    
}
