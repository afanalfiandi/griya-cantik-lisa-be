<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    private function getServicesData($serviceID = null, $serviceCategoryID = null)
    {
        $query = DB::table('service')
            ->join('service_category', 'service.serviceCategoryID', '=', 'service_category.serviceCategoryID')
            ->leftJoin('service_detail', 'service.serviceID', '=', 'service_detail.serviceID')
            ->select(
                'service.serviceID',
                'service.serviceCategoryID',
                'service_category.serviceCategoryName',
                'service.serviceName',
                'service.description',
                'service.price',
                'service.time',
                'service_detail.img',
                'service_detail.serviceDetailID',
            );

        if ($serviceID) {
            $query->where('service.serviceID', '=', $serviceID);
        }

        if ($serviceCategoryID) {
            $query->where('service.serviceCategoryID', '=', $serviceCategoryID);
        }

        $services = $query->get();

        $servicesData = $services->groupBy('serviceID')->map(function ($service) {
            return [
                'serviceId' => $service[0]->serviceID,
                'serviceCategoryId' => $service[0]->serviceCategoryID,
                'serviceCategoryName' => $service[0]->serviceCategoryName,
                'serviceName' => $service[0]->serviceName,
                'description' => $service[0]->description,
                'price' => $service[0]->price,
                'time' => $service[0]->time,
                'img' => $service->map(function ($detail) {
                    return [
                        'serviceDetailID' => $detail->serviceDetailID,
                        'img' => $detail->img,
                    ];
                }),
            ];
        });

        return $servicesData->values();
    }

    public function index(): JsonResponse
    {
        $servicesData = $this->getServicesData();
        return response()->json([
            'status' => 'success',
            'data' => $servicesData
        ], 200);
    }

    public function serviceByID($serviceID): JsonResponse
    {
        $servicesData = $this->getServicesData($serviceID);
        return response()->json([
            'status' => 'success',
            'data' => $servicesData
        ], 200);
    }

    public function serviceByCategoryID($serviceCategoryID): JsonResponse
    {
        $servicesData = $this->getServicesData(null, $serviceCategoryID);
        return response()->json([
            'status' => 'success',
            'data' => $servicesData
        ], 200);
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'serviceCategoryID' => 'required|integer',
            'serviceName'       => 'required|string|max:255',
            'description'       => 'nullable|string',
            'price'             => 'required|numeric',
            'time'              => 'required|string|max:255',
            'img'               => 'nullable|array',
            'img.*'             => 'file|image|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $service = Service::create([
                'serviceCategoryID' => $validatedData['serviceCategoryID'],
                'serviceName'       => $validatedData['serviceName'],
                'description'       => $validatedData['description'] ?? null,
                'price'             => $validatedData['price'],
                'time'              => $validatedData['time'],
            ]);

            if ($request->has('img')) {
                foreach ($validatedData['img'] as $image) {
                    $path = $image->store('images/service', 'public');

                    ServiceDetail::create([
                        'serviceID' => $service->id,
                        'img'       => $path,
                    ]);
                }
            }

            DB::commit();

            return response()->json(['message' => 'success'], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'failed', 'message' => $e->getMessage()], 500);
        }
    }
}
