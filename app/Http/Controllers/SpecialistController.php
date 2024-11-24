<?php

namespace App\Http\Controllers;

use App\Models\Specialist;
use Illuminate\Http\JsonResponse;

class SpecialistController extends Controller
{
    public function index(): JsonResponse
    {
        $serviceCategories = Specialist::all();

        return response()->json([
            'status' => 'success',
            'data' => $serviceCategories
        ], 200);
    }
}
