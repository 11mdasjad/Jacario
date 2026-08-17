<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'chest',
        'length',
        'shoulder',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
