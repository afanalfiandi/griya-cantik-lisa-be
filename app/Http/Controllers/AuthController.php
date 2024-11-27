<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function auth(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Input',
                'error' => $validator->errors()
            ], 422);
        }

        $customer = Customer::where('username', $request->username)->first();

        if ($customer && Hash::check($request->password, $customer->password)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Auth success',
                'data' => [
                    'customerID' => $customer->customerID,
                    'firstName' => $customer->firstName,
                    'lastName' => $customer->lastName,
                    'username' => $customer->username,
                    'img' => $customer->img
                ]
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials',
        ], 401);
    }

    public function authAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Input',
                'error' => $validator->errors()
            ], 422);
        }

        $customer = Admin::where('username', $request->username)->first();

        if ($customer && Hash::check($request->password, $customer->password)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Auth success',
                'data' => [
                    'username' => $customer->username,
                ]
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials',
        ], 401);
    }
}
