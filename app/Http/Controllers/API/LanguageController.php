<?php

namespace App\Http\Controllers\API;

use App\Models\Contents;
use App\Models\Sections;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LanguageController extends Controller
{
    public function getContent(Request $request)
{
    $languageCode = $request->input('lang');
    
    // Tentukan kolom yang ingin diambil berdasarkan kode bahasa
    $titleColumnContent = ($languageCode === 'ina') ? 'title_ina' : 'title_en';
    $descriptionColumnContent = ($languageCode === 'ina') ? 'description_ina' : 'description_en';
    
    // Ambil data konten sesuai dengan bahasa yang dipilih
    $contents = Contents::select($titleColumnContent . ' as title', $descriptionColumnContent . ' as description')->get();

    // Tentukan kolom yang ingin diambil berdasarkan kode bahasa
    $titleColumnSection = ($languageCode === 'ina') ? 'title_ina' : 'title_en';
    $descriptionColumnSection = ($languageCode === 'ina') ? 'description_ina' : 'description_en';
    
    // Ambil data section sesuai dengan bahasa yang dipilih
    $sections = Sections::select($titleColumnSection . ' as title', $descriptionColumnSection . ' as description')->get();

    return response()->json(['sections' => $sections, 'contents' => $contents]);
}

}