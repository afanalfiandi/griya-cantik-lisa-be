<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
}
