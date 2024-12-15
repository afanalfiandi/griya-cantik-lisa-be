<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\ServiceResource;
use App\Models\ServiceDetail;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditService extends EditRecord
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterUpdate(): void
    {
        $service = $this->record;
        $uploadedImages = $this->data['img'] ?? []; // Data gambar yang diunggah (dari form)
        $existingDetails = $service->serviceDetails; // Gambar terkait yang sudah ada di database

        foreach ($uploadedImages as $index => $uploadedImage) {
            if (isset($existingDetails[$index])) {
                // Update gambar yang sudah ada
                $existingDetails[$index]->update([
                    'img' => $uploadedImage,
                ]);
            } else {
                // Tambahkan gambar baru jika tidak ada entri yang sesuai
                ServiceDetail::create([
                    'serviceID' => $service->serviceID,
                    'img' => $uploadedImage,
                ]);
            }
        }

        // Hapus gambar yang tidak lagi digunakan jika jumlah existingDetails lebih banyak dari uploadedImages
        if (count($uploadedImages) < $existingDetails->count()) {
            $existingDetails
                ->skip(count($uploadedImages))
                ->each(function ($detail) {
                    $detail->delete();
                });
        }
    }
}
