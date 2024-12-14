<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Resources\ServiceResource\RelationManagers;
use App\Models\Service;
use App\Models\ServiceDetail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('serviceCategoryID')->relationship('serviceCategory', 'serviceCategoryName'),
                Forms\Components\TextInput::make('serviceName'),
                Forms\Components\Textarea::make('description'),
                Forms\Components\TextInput::make('price')->numeric(),
                Forms\Components\TextInput::make('time')->numeric(),
                Forms\Components\FileUpload::make('img')
                    ->label('Upload Images')
                    ->multiple()
                    ->disk('public')
                    ->preserveFilenames()
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('serviceName'),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('price'),
                Tables\Columns\TextColumn::make('time'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }

    public static function afterSave($record)
    {
        if (isset($record->images) && $record->images->isNotEmpty()) {
            foreach ($record->images as $image) {
                ServiceDetail::create([
                    'serviceID' => $record->serviceID,  // Mengaitkan gambar dengan serviceID
                    'img' => $image->hashName(),  // Nama gambar setelah diupload
                ]);
            }
        }
    }
}
