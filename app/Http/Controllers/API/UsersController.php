<?php

namespace App\Http\Controllers\API;

use App\Models\Users;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UsersController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil token dari header Authorization
        $token = $request->bearerToken();

        // Jika token tidak ada
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token diperlukan untuk mengakses data admin.',
            ], 401);
        }

        // Mengambil data admin
        $users = Users::all();

        return response()->json([
            'success' => true,
            'message' => 'Data admin berhasil diambil',
            'error' => $users->isEmpty(),
            'data' => UserResource::collection($users),
        ]);
    }


    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ada kesalahan',
                'data' => $validator->errors()
            ]);
        }

        $user = Users::create([
            'username' => $request->username,
            'password' => Hash::make($request->password), // Menggunakan Hash::make() untuk mengenkripsi password
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sukses create Data user',
        ]);
    }

    public function update(Request $request, string $id)
    {
        $user = Users::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal melakukan update data!',
                'data' => $validator->errors()
            ]);
        }

        $user->username = $request->input('username');
        $user->password = bcrypt($request->input('password'));

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Update Data'
        ]);
    }



    public function destroy(Request $request, string $id)
    {
        $user = Users::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }


        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Hapus Data'
        ]);
    }

    public function login(Request $request)
    {
        // Periksa apakah pengguna sudah terotentikasi
        if (Auth::check()) {
            // Jika sudah terotentikasi, kembalikan respons dengan pesan bahwa pengguna sudah masuk
            return response()->json([
                'success' => false,
                'message' => 'Pengguna sudah masuk. Silakan keluar terlebih dahulu.',
            ], 401);
        }

        // Lakukan autentikasi pengguna
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            $user = Auth::user();
            // Membuat token baru untuk pengguna yang dibuat
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'token' => $token, // Mengirimkan token sebagai bagian dari respons
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Username atau password salah',
        ], 401);
    }



    public function logout(Request $request)
    {
        // Hapus token pengguna dari sistem autentikasi Sanctum
        Auth::user()->tokens()->delete();

        // Lakukan proses logout
        Auth::logout();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ]);
    }

}

