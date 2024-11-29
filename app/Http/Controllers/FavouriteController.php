<?php

namespace App\Http\Controllers;

use App\Models\Favourite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavouriteController extends Controller
{
    public function index(Request $request)
    {
        $favourite = Favourite::where('customerID', $request->customerID)->get();

        return response()->json([
            'status' => 'success',
            'data' => $favourite
        ], 200);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'customerID'   => 'required|integer',
            'serviceID'   => 'required|integer',
        ]);

        try {
            Favourite::create([
                'customerID'   => $validated['customerID'],
                'serviceID'   => $validated['serviceID'],
            ]);

            return response()->json(['message' => 'success'], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'failed',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function delete($favouriteID)
    {
        try {
            $banner = Favourite::where('favouriteID', $favouriteID)->first();

            if (!$banner) {
                return response()->json(['message' => 'not found'], 404);
            }

            Favourite::where('favouriteID', $favouriteID)->delete();

            return response()->json(['message' => 'success'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'failed',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
