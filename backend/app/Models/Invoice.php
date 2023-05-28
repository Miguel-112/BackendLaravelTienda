<?php

namespace App\Models;
use invoiceDetails;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'total_sale',
        'client_id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

   

    public function invoiceDetails()
    {
        return $this->hasMany(InvoiceDetail::class);
    }
    
}
