<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    use HasFactory;

    protected $fillable = [
        'route',
        'name',
        'title_en',
        'title_ina',
        'type',
        'parent_id'
    ];

    public function parent()
    {
        return $this->belongsTo(Pages::class, 'parent_id');
    }
    

    public function sections()
    {
        return $this->hasMany(Sections::class, 'page_id');
    }

    public function contents(){
        return $this->hasMany(Contents::class, 'page_id');
    }

}
