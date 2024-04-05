<?php
namespace App\Http\Resources;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    
    public function toArray($request)
    {
        $selectedLanguage = $request->input('lang', 'en');
    
        $title = $this->applyColorToTitle($selectedLanguage === 'en' ? $this->title_en : $this->title_ina, $selectedLanguage);
    

        return [
            'id' => $this->id,
            'page_id' => $this->page_id,
            'type' => $this->type,
            // 'image' => config('app.url') . $this->image,
            'image' => env('IMAGE_URL'). '/assets/img/' . $this->image,
            'title_en' => $this->applyColorToTitle($this->title_en, 'en'), 
            'title_ina' => $this->applyColorToTitle($this->title_ina, 'ina'),
            'description_en' => $this->description_en,
            'description_ina' => $this->description_ina,
            'pages' => $this->whenLoaded('pages', function () {
                return [
                    'id' => $this->pages->id,
                    'name' => $this->pages->name,
                    'type' => $this->pages->type,
                    'parent_id' => $this->pages->parent_id,
                    'title_en' => $this->pages->title_en,
                    'title_ina' => $this->pages->title_ina,
                    'route' => $this->pages->parent ? $this->pages->parent->route . $this->pages->route : $this->pages->route,
    
                    'parent' => $this->pages->parent ? [
                        'id' => $this->pages->parent->id,
                        'name' => $this->pages->parent->name,
                        'route' => $this->pages->parent->route,
                        'title' => $this->pages->parent->title,
                    ] : null,
                ];
            }),
        ];
    }
    
    private function applyColorToTitle($title, $language)
    {
        if ($language === 'en') {
            return $this->applyColorToTitleEN($title);
        } elseif ($language === 'ina') {
            return $this->applyColorToTitleID($title);
        }
    
        return $title;
    }

    private function applyColorToTitleEN($title_en)
    {
        $formattedTitle = $title_en;
        $formattedTitle = Str::replaceFirst('Technology !', '<span style="color: orange;">Technology !</span>', $formattedTitle);
        $formattedTitle = Str::replaceFirst('Offer', '<span style="color: orange;">Offer</span>', $formattedTitle);
        $formattedTitle = Str::replaceFirst('your business growth.', '<span style="color: orange;">your business growth.</span>', $formattedTitle);
        $formattedTitle = Str::replaceFirst('Success', '<span style="color: orange;">Success</span>', $formattedTitle);
        $formattedTitle = Str::replaceFirst('experience working with us.', '<span style="color: orange;">experience working with us.</span>', $formattedTitle);
        $formattedTitle = Str::replaceFirst('GET', '<span style="color: orange;">GET</span>', $formattedTitle);
        $formattedTitle = Str::replaceFirst('IN', '<span style="color: #1640D6;">IN</span>', $formattedTitle);

        return $formattedTitle;
    }

    private function applyColorToTitleID($title_ina)
    {
        $formattedTitle = $title_ina;
        $formattedTitle = Str::replaceFirst('Technologi !', '<span style="color: orange;">Technologi !</span>', $formattedTitle);
        $formattedTitle = Str::replaceFirst('tawarkan', '<span style="color: orange;">tawarkan</span>', $formattedTitle);
        $formattedTitle = Str::replaceFirst('mempercepat pertumbuhan bisnis Anda.', '<span style="color: orange;">mempercepat pertumbuhan bisnis Anda.</span>', $formattedTitle);
        $formattedTitle = Str::replaceFirst('Keberhasilan', '<span style="color: orange;">Keberhasilan</span>', $formattedTitle);
        $formattedTitle = Str::replaceFirst('pengalaman mereka bekerja dengan kami.', '<span style="color: orange;">pengalaman mereka bekerja dengan kami.</span>', $formattedTitle);
        $formattedTitle = Str::replaceFirst('untuk', '<br>untuk', $formattedTitle);
        
        return $formattedTitle;
    }
}
