<?php

namespace App\Http\Controllers;

use App\Models\Specialist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function update(Request $request, $specialistID)
    {
        try {
            $request->validate([
                'specialistName'   => 'required|string|max:50',
                'img'        => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            ]);

            $specialist = Specialist::where('specialistID', $specialistID)->first();

            if (!$specialist) {
                return response()->json(['message' => 'data not found'], 404);
            }

            if ($request->hasFile('img')) {
                if ($specialist->img && Storage::exists('public/images/specialist/' . $specialist->img)) {
                    Storage::delete('public/images/specialist/' . $specialist->img);
                }

                $image = $request->file('img');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/images/specialist', $imageName);

                $specialist->where('specialistID', $specialistID)
                    ->update([
                        'specialistName' => $request->specialistName,
                        'img' => $imageName
                    ]);
            }

            $specialist->where(
                'specialistID',
                $specialistID
            )->update([
                'specialistName' => $request->specialistName,
            ]);

            return response()->json(['message' => 'success', 'specialist' => $specialist], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'failed',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function delete($specialistID)
    {
        try {
            $customer = Specialist::where('specialistID', $specialistID)->first();

            if (!$customer) {
                return response()->json(['message' => 'not found'], 404);
            }

            Specialist::where('specialistID', $specialistID)->delete();

            return response()->json(['message' => 'success'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete customer',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
