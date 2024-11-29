<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BannerController extends Controller
{
    public function index(): JsonResponse
    {
        $serviceCategories = Banner::all();

        return response()->json([
            'status' => 'success',
            'data' => $serviceCategories
        ], 200);
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'img'             => 'file|image|max:2048',
        ]);

        DB::beginTransaction();

        try {
            if ($request->has('img')) {
                $imgName = md5(uniqid() . '-' . time()) . '.' . $request->file('img')->getClientOriginalExtension();

                $request->file('img')->storeAs('images/banner', $imgName, 'public');

                Banner::create([
                    'img'       => $imgName,
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'success'], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'failed', 'message' => $e->getMessage()], 500);
        }
    }

    public function delete($bannerID)
    {
        try {
            $banner = Banner::where('bannerID', $bannerID)->first();

            if (!$banner) {
                return response()->json(['message' => 'not found'], 404);
            }

            Banner::where('bannerID', $bannerID)->delete();

            return response()->json(['message' => 'success'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'failed',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
