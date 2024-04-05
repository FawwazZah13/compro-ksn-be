<?php

namespace App\Http\Controllers\API;

use App\Models\Contents;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ContentResource;
use Illuminate\Support\Facades\Validator;


class ContentsController extends Controller
{

    public function index(Request $request)
    {
        // Ambil data dengan nilai 'active' sama dengan 1
        $contents = Contents::with(['sections.pages.parent'])
            // ->where('active', 1)
            ->get();

        $response = ContentResource::collection($contents)->additional([
            'message' => $contents->isEmpty() ? 'Tidak ada data yang aktif' : 'Data contents berhasil diambil',
            'error' => $contents->isEmpty(),
        ]);

        return $response;
    }

    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'section_id' => 'required',
            'title_en' => 'required',
            'title_ina' => 'required',
            'description_en' => 'required',
            'description_ina' => 'required',
            'image' => 'nullable', // Menjadikan gambar opsional
            'type' => 'required',
            'active' => 'required|in:0,1', // Validasi untuk nilai 0 atau 1
            'order' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ada kesalahan',
                'data' => $validator->errors()
            ], 400); // Mengembalikan status 400 untuk kesalahan validasi
        }

        $image = $request->file('image');
        $imagePath = null;

        // Memeriksa apakah file gambar telah diunggah
        if ($image) {
            $imagePath = $image->move('assets/img', $image->getClientOriginalName())->getPathName();
            $imagePath = str_replace('\\', '/', $imagePath); // Ganti backslash dengan slash biasa
            $imagePath = str_replace('assets/img/', '', $imagePath);// Hapus duplikasi 'assets/img/'
        }

        // Menyimpan data baru ke dalam database
        $contents = Contents::create([
            'section_id' => $request->section_id,
            'title_en' => $request->title_en,
            'description_en' => $request->description_en,
            'title_ina' => $request->title_ina,
            'description_ina' => $request->description_ina,
            'image' => $imagePath, // Menggunakan $imagePath yang mungkin NULL
            'type' => $request->type,
            'active' => (int) $request->active, // Menggunakan nilai 'active' dari permintaan
            'order' => $request->order,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sukses create Data',
            'data' => $contents, // Mengembalikan data yang baru dibuat
        ], 201); // Mengembalikan status 201 untuk penciptaan sukses
    }




    public function update(Request $request, string $id)
    {
        $contents = Contents::find($id);

        if (!$contents) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'section_id' => 'required',
            'title_en' => 'required',
            'title_ina' => 'required',
            'description_en' => 'required',
            'description_ina' => 'required',
            'type' => 'required',
            'active' => 'required|integer|in:0,1',
            'order' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal melakukan update data!',
                'data' => $validator->errors()
            ]);
        }

        $contents->section_id = $request->input('section_id');
        $contents->title_en = $request->input('title_en');
        $contents->description_en = $request->input('description_en');
        $contents->title_ina = $request->input('title_ina');
        $contents->description_ina = $request->input('description_ina');
        $contents->type = $request->input('type');
        $contents->active = (int) $request->input('active');
        $contents->order = $request->input('order');

        $image = $request->file('image');

        // Memeriksa apakah file gambar telah diunggah
        if ($image) {
            $imageName = $image->getClientOriginalName();
            $image->move('assets/img', $imageName); // Memindahkan file ke direktori tujuan
            $contents->image = $imageName; // Memperbarui kolom gambar dengan nama gambar yang baru
        }

        $contents->save();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Update Data'
        ]);
    }


    public function destroy(Request $request, string $id)
    {
        $contents = Contents::find($id);

        if (!$contents) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }


        $contents->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Hapus Data'
        ]);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        // Lakukan pencarian menggunakan keyword di database
        $results = Contents::where('section_id', 'like', '%' . $keyword . '%')
            ->orWhere('type', 'like', '%' . $keyword . '%')
            ->orWhere('title_en', 'like', '%' . $keyword . '%')
            ->orWhere('title_ina', 'like', '%' . $keyword . '%')
            ->orWhere('description_en', 'like', '%' . $keyword . '%')
            ->orWhere('description_ina', 'like', '%' . $keyword . '%')
            ->orWhere('active', 'like', '%' . $keyword . '%')
            ->orWhere('order', 'like', '%' . $keyword . '%')
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
    public function show(Contents $contents)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contents $contents)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
}