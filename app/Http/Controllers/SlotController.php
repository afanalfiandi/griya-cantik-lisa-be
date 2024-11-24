<?php

namespace App\Http\Controllers;

use App\Models\Slot;
use Illuminate\Http\JsonResponse;

class SlotController extends Controller
{
    public function index(): JsonResponse
    {
        $slot = Slot::all();

        return response()->json([
            'status' => 'success',
            'data' => $slot
        ], 200);
    }
}
