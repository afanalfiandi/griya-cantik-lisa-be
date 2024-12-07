<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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

    public function update(Request $request, $customerID)
    {
        try {
            $customer = Customer::where('customerID', $customerID)->firstOrFail();

            $customer->username = $request->input('username', $customer->username);
            $customer->firstName = $request->input('firstName', $customer->firstName);
            $customer->lastName = $request->input('lastName', $customer->lastName);

            if (!empty($request->input('password'))) {
                $customer->password = Hash::make($request->input('password'));
            }

            Customer::where('customerID', $customerID)
                ->update([
                    'username'  => $request->input('username', $customer->username),
                    'firstName' => $request->input('firstName', $customer->firstName),
                    'lastName'  => $request->input('lastName', $customer->lastName),
                    'password'  => !empty($request->input('password')) ? Hash::make($request->input('password')) : $customer->password,
                ]);
            $customer->save();

            return response()->json(['message' => 'success'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'failed',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function updateImg(Request $request, $customerID)
    {
        try {
            $request->validate([
                'img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $customer = Customer::where('customerID', $customerID)->first();

            if (!$customer) {
                return response()->json(['message' => 'data not found'], 404);
            }

            if ($request->hasFile('img')) {
                if ($customer->image && Storage::exists('public/images/customers/' . $customer->image)) {
                    Storage::delete('public/images/customers/' . $customer->image);
                }

                $image = $request->file('img');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/customers', $imageName);

                Customer::where('customerID', $customerID)
                    ->update([
                        'img'  => $imageName,
                    ]);

                return response()->json(['message' => 'success', 'img' => $imageName], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'failed',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function delete($customerID)
    {
        try {
            $customer = Customer::where('customerID', $customerID)->first();

            if (!$customer) {
                return response()->json(['message' => 'not found'], 404);
            }

            Customer::where('customerID', $customerID)->delete();

            return response()->json(['message' => 'success'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete customer',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
