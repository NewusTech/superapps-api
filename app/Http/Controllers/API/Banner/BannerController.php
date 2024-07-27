<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('check.admin')->only(['store', 'update', 'destroy']);
    }

    public function index()
    {
        try {
            $data = Banner::all();
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil get data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }

            $file = $request->file('image');
            $filePath = $file->store('banners', 's3');
            $fileUrl = Storage::disk('s3')->url($filePath);

            $data = Banner::create([
                'title' => $request->title,
                'image' => $fileUrl,
            ]);

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Berhasil menyimpan data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(Banner $banner)
    {
        try {
            if (!$banner) {
                return response()->json(['message' => 'Banner not found'], 404);
            }
            return response()->json([
                'success' => true,
                'data' => $banner,
                'message' => 'Berhasil get data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function edit(Banner $banner)
    {
        if (!$banner) {
            return response()->json(['message' => 'Banner not found'], 404);
        }
        try {
            return response()->json([
                'success' => true,
                'data' => $banner,
                'message' => 'Berhasil get data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Banner $banner)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'image' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }

            if ($request->hasFile('image')) {
                // Hapus gambar lama dari S3
                if ($banner->image) {
                    $oldImagePath = parse_url($banner->image, PHP_URL_PATH);
                    Storage::disk('s3')->delete($oldImagePath);
                }

                // Unggah gambar baru
                $file = $request->file('image');
                $filePath = $file->store('banners', 's3');
                $fileUrl = Storage::disk('s3')->url($filePath);

                $banner->image = $fileUrl;
            }

            $banner->title = $request->title;
            $banner->save();

            return response()->json([
                'success' => true,
                'data' => $banner,
                'message' => 'Berhasil ubah data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Banner $banner)
    {
        try {
            if (!$banner) {
                return response()->json(['message' => 'Banner not found'], 404);
            }

            // Hapus gambar dari S3
            if ($banner->image) {
                $imagePath = parse_url($banner->image, PHP_URL_PATH);
                Storage::disk('s3')->delete($imagePath);
            }

            $banner->delete();
            return response()->json([
                'success' => true,
                'data' => $banner,
                'message' => 'Berhasil hapus data'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
