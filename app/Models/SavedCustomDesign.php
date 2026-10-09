<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedCustomDesign extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customizable_product_id',
        'color_id',
        'size_id',
        'title',
        'design_data',
        'preview_path',
        'artwork_paths',
    ];

    protected $casts = [
        'design_data' => 'array',
        'artwork_paths' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(CustomizableProduct::class, 'customizable_product_id');
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }
}
