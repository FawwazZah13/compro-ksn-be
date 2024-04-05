<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sections extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'type',
        'title_en',
        'description_en',
        'title_ina',
        'description_ina',
        'image',
    ];

    public function pages()
    {
        return $this->belongsTo(Pages::class, 'page_id' );
    }    

    public function parent()
    {
        return $this->belongsTo(Pages::class, 'page_id');
    }

    public function contents()
    {
        return $this->hasMany(Contents::class, 'section_id');
    }
    
}
