<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index()
    {
        return Customer::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username'   => 'required|string|max:50',
            'password'   => 'required|string|min:6',
            'firstName'  => 'required|string|max:50',
            'lastName'   => 'required|string|max:50',
            'img'        => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        try {
            $imgName = 'default.png';
            if ($request->hasFile('img')) {
                $imgName = md5(uniqid() . '-' . time()) . '.' . $request->file('img')->getClientOriginalExtension();

                $request->file('img')->storeAs('images/customers', $imgName, 'public');
            }

            Customer::create([
                'username'   => $validated['username'],
                'password'   => Hash::make($validated['password']),
                'firstName'  => $validated['firstName'],
                'lastName'   => $validated['lastName'],
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
