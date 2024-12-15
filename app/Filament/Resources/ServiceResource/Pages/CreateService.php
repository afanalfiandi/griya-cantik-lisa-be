<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\ServiceResource;
use App\Models\ServiceDetail;
use Filament\Actions;
use Filament\Actions\Concerns\HasWizard;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreateService extends CreateRecord
{
    use HasWizard;

    protected static string $resource = ServiceResource::class;

    protected function afterCreate(): void
    {
        $service = $this->record;
        $images = $this->data['img'] ?? [];

        foreach ($images as $image) {
            ServiceDetail::create([
                'serviceID' => $service->serviceID,
                'img' => $image,
            ]);
        }
    }
}
