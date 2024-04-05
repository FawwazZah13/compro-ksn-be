<?php

namespace App\Http\Controllers\API;

use App\Models\Sections;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Http\Resources\SectionResource;
use Illuminate\Support\Facades\Validator;


class SectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $sections = Sections::with('pages.parent')->get();

        $response = SectionResource::collection($sections)->additional([
            'message' => $sections->isEmpty() ? 'Data sections tidak ada' : 'Data sections berhasil diambil',
            'error' => $sections->isEmpty(),
        ]);

        return $response;
    }


    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page_id' => 'required',
            'type' => 'required',
            'image' => 'nullable',
            'description_en' => 'required',
            'description_ina' => 'required',
            'title_en' => 'required',
            'title_ina' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ada kesalahan',
                'data' => $validator->errors()
            ]);
        }

        $image = $request->file('image');
        $imagePath = null;

        // Memeriksa apakah file gambar telah diunggah
        if ($image) {
            //  $imagePath = $image->move('assets/img', $image->getClientOriginalName())->getPathName();
            $imagePath = $image->move('assets/img', $image->getClientOriginalName())->getPathName();
            // Ganti backslash dengan slash biasa
            $imagePath = str_replace('\\', '/', $imagePath);
            // Hapus duplikasi 'assets/img/'
            $imagePath = str_replace('assets/img/', '', $imagePath);
            ;

        }

        $sections = Sections::create([
            'page_id' => $request->page_id,
            'type' => $request->type,
            'image' => $imagePath, // Menggunakan $imagePath yang mungkin NULL
            'description_en' => $request->description_en,
            'description_ina' => $request->description_ina,
            'title_en' => $request->title_en,
            'title_ina' => $request->title_ina,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sukses create Data',
        ]);
    }

    public function update(Request $request, string $id)
    {
        $sections = Sections::find($id);

        if (!$sections) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'page_id' => 'required',
            'type' => 'required',
            'title_en' => 'required',
            'title_ina' => 'required',
            'description_en' => 'required',
            'description_ina' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal melakukan update data!',
                'data' => $validator->errors()
            ]);
        }

        // Update fields that are always required
        $sections->page_id = $request->input('page_id');
        $sections->type = $request->input('type');
        $sections->description_en = $request->input('description_en');
        $sections->description_ina = $request->input('description_ina');
        $sections->title_en = $request->input('title_en');
        $sections->title_ina = $request->input('title_ina');

        // Check if image is provided in the request
        $image = $request->file('image');

        // Memeriksa apakah file telah diunggah
        if ($image) {
            $imageName = $image->getClientOriginalName();
            $image->move('assets/img', $imageName); // Memindahkan file ke direktori tujuan
            $sections->image = $imageName; // Memperbarui kolom gambar dengan nama gambar yang baru
        }

        $sections->save();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Update Data'
        ]);
    }


    public function destroy(Request $request, string $id)
    {
        $sections = Sections::find($id);

        if (!$sections) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }


        $sections->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Hapus Data'
        ]);
    }


    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        // Lakukan pencarian menggunakan keyword di database
        $results = Sections::where('page_id', 'like', '%' . $keyword . '%')
            ->orWhere('type', 'like', '%' . $keyword . '%')
            ->orWhere('title_en', 'like', '%' . $keyword . '%')
            ->orWhere('title_ina', 'like', '%' . $keyword . '%')
            ->orWhere('description_en', 'like', '%' . $keyword . '%')
            ->orWhere('description_ina', 'like', '%' . $keyword . '%')
            ->get();

        if ($results->isEmpty()) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Data tidak ada.',
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $results,
            'message' => 'Data ditemukan.',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    // public function show(Sections $sections)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sections $sections)
    {
        //
    }
}