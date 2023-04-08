<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'tel',
        'address',
    ];

    public function motorcycleParts()
    {
        return $this->hasMany(MotorcyclePart::class);
    }
}
