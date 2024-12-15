<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\ServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewService extends ViewRecord
{
    protected static string $resource = ServiceResource::class;

    protected function getViewData(): array
    {
        $service = $this->record;
        $serviceDetails = $service->serviceDetail;
        return [
            'service' => $service,
            'serviceDetails' => $serviceDetails,
        ];
    }
}
