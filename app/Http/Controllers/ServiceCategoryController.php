<?php

namespace App\Http\Controllers;

use App\Models\ServiceCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceCategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $serviceCategories = ServiceCategory::all();

        return response()->json([
            'status' => 'success',
            'data' => $serviceCategories
        ], 200);
    }
}
