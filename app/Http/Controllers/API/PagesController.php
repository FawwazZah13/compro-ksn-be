<?php

namespace App\Http\Controllers\API;

use App\Models\Pages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PageResource;


class PagesController extends Controller
{
    public function index(Request $request)
    {
        $pages = Pages::all();

        return PageResource::collection($pages)->additional([
            'message' => $pages->isEmpty() ? 'Data pages tidak ada' : 'Data pages berhasil diambil',
            'error' => $pages->isEmpty(),
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'route' => 'required',
            'name' => 'required',
            'title_en' => 'required',
            'title_ina' => 'required',
            'type' => 'required',
            'parent_id' => 'nullable|exists:pages,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ada kesalahan',
                'data' => $validator->errors()
            ]);
        }

        $pages = Pages::create([
            'route' => $request->route,
            'name' => $request->name,
            'title_en' => $request->title_en,
            'title_ina' => $request->title_ina,
            'type' => $request->type,
            'parent_id' => $request->parent_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sukses create Data',
        ]);
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pages = Pages::find($id);

        if (!$pages) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'route' => 'required',
            'name' => 'required',
            'title_en' => 'required',
            'title_ina' => 'required',
            'type' => 'required',
            'parent_id' => 'nullable|exists:pages,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal melakukan update data!',
                'data' => $validator->errors()
            ]);
        }

        $pages->route = $request->input('route');
        $pages->name = $request->input('name');
        $pages->title_en = $request->input('title_en');
        $pages->title_ina = $request->input('title_ina');
        $pages->type = $request->input('type');
        $pages->parent_id = $request->input('parent_id'); // Menambahkan parent_id

        $pages->save();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Update Data'
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $pages = Pages::find($id);

        if (!$pages) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }


        $pages->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Hapus Data'
        ]);
    }

    
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        // Lakukan pencarian menggunakan keyword di database
        $results = Pages::where('route', 'like', '%'.$keyword.'%')
                        ->orWhere('name', 'like', '%'.$keyword.'%')
                        ->orWhere('title_en', 'like', '%'.$keyword.'%')
                        ->orWhere('title_ina', 'like', '%'.$keyword.'%')
                        ->orWhere('type', 'like', '%'.$keyword.'%')
                        ->orWhere('parent_id', 'like', '%'.$keyword.'%')
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
    public function edit(Pages $pages)
    {
        //
    }


}