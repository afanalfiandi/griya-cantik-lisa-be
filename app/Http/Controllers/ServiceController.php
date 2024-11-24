<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
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
                'service.created_at',
                'service.updated_at',
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
                'created_at' => $service[0]->created_at,
                'updated_at' => $service[0]->updated_at,
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
        $servicesData = $this->getServicesData($serviceCategoryID);
        return response()->json([
            'status' => 'success',
            'data' => $servicesData
        ], 200);
    }
}
