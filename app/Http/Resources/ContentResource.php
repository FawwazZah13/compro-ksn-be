<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'section_id' => $this->section_id,
            'type' => $this->type,
            'image' => env('IMAGE_URL'). '/assets/img/' . $this->image,
            'active' => $this->active,
            'order' => $this->order,
            'title_en' => $this->title_en,
            'title_ina' => $this->title_ina,
            'description_en' => $this->description_en,
            'description_ina' => $this->description_ina,
            'sections' => $this->whenLoaded('sections', function () {
                return [
                    'id' => $this->sections->id,
                    'page_id' => $this->sections->page_id,
                    'type' => $this->sections->type,
                    'image' => config('app.url') . $this->sections->image,
                    'title_en' => $this->sections->title_en,
                    'title_ina' => $this->sections->title_ina,
                    'description_en' => $this->sections->description_en,
                    'description_ina' => $this->sections->description_ina,

                    'pages' => $this->sections->pages ? [
                        'id' => $this->sections->pages->id,
                        'name' => $this->sections->pages->name,
                        'type' => $this->sections->pages->type,
                        'parent_id' => $this->sections->pages->parent_id,
                        'title_en' => $this->sections->pages->title_en,
                        'title_ina' => $this->sections->pages->title_ina,
                        'route' => $this->sections->pages->parent ? $this->sections->pages->parent->route . $this->sections->pages->route : $this->sections->pages->route,

                        'parent' => $this->sections->pages->parent ? [
                            'id' => $this->sections->pages->parent->id,
                            'name' => $this->sections->pages->parent->name,
                            'route' => $this->sections->pages->parent->route,
                            'title' => $this->sections->pages->parent->title,
                        ] : null, //hanya akan berlaku jika 'parent' dari 'pages' tidak tersedia
                    ] : null, //menunjukkan bahwa jika tidak ada 'pages' yang tersedia di 'sections', 
                ];
            }),
        ];
    }
}