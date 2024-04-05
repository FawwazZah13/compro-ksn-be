<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contents extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'title_en',
        'description_en',
        'title_ina',
        'description_ina',
        'image',
        'type',
        'active',
        'order',
    ];

    public function sections()
    {
        return $this->belongsTo(Sections::class, 'section_id');
    }

    public function parent()
    {
        return $this->belongsTo(Pages::class, 'parent_id');
    }
    
    public function pages()
    {
        return $this->belongsTo(Pages::class, 'page_id');
    }
}
