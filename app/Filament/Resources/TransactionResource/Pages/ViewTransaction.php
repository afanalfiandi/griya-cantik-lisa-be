<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ViewTransaction extends ViewRecord
{
    protected static string $resource = TransactionResource::class;


    protected function getViewData(): array
    {
        $service = $this->record;
        $transactionDetails = $service->transactionDetail;
        return [
            'service' => $service,
            'transactionDetails' => $transactionDetails,
        ];
    }
}
