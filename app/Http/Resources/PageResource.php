<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'title_en' => $this->title_en,
            'title_ina' => $this->title_ina,
            'type' => $this->type,
            'parent_id' => $this->parent_id,
            'route' => $this->parent ? $this->parent->route . $this->route : $this->route, // Jika parent ada, maka route akan diambil dari parent->route ditambah dengan route milik objek saat ini
            'parent' => $this->whenLoaded('parent', function () {
                return [
                    'id' => $this->parent->id,
                    'name' => $this->parent->name,
                    'route' => $this->parent->route,
                    'title' => $this->parent->title,
                ];
            }),
        ];
    }
}