<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'title',
        'description',
        'stock',
        'price',
        'image',
        'expired',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
