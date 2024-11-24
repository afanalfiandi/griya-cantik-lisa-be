<?php

namespace App\Http\Controllers;

use App\Models\Specialist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SpecialistController extends Controller
{
    public function index(): JsonResponse
    {
        $specialist = Specialist::all();

        return response()->json([
            'status' => 'success',
            'data' => $specialist
        ], 200);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'specialistName'   => 'required|string|max:50',
            'img'        => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        try {
            $imgName = 'default.png';
            if ($request->hasFile('img')) {
                $imgName = md5(uniqid() . '-' . time()) . '.' . $request->file('img')->getClientOriginalExtension();

                $request->file('img')->storeAs('images/specialist', $imgName, 'public');
            }

            Specialist::create([
                'specialistName'   => $validated['specialistName'],
                'img'        => $imgName,
            ]);

            return response()->json(['message' => 'success'], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'failed',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
