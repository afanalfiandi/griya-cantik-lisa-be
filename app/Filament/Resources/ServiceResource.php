<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Resources\ServiceResource\RelationManagers;
use App\Models\Service;
use App\Models\ServiceDetail;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section as ComponentsSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Str;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

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
                    ->required()
                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                        $timestamp = now()->format('YmdHis'); // Format timestamp
                        $extension = $file->getClientOriginalExtension(); // Ekstensi file
                        $uniqueId = Str::random(10); // ID unik tambahan
                        return "{$timestamp}_{$uniqueId}.{$extension}"; // Nama file unik
                    }),
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
                Tables\Columns\TextColumn::make('details_count')
                    ->label('Number of Images')
                    ->getStateUsing(function ($record) {
                        return $record->serviceDetail()->count(); // Mengambil jumlah service detail yang terkait
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'view' => Pages\ViewService::route('/{record}'),
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

    public static function infoList(Infolist $infoList): Infolist
    {
        return $infoList
            ->schema([
                ComponentsSection::make('Service Information')->schema([
                    TextEntry::make('serviceName'),
                    TextEntry::make('serviceCategory.serviceCategoryName'),
                    TextEntry::make('description'),
                    TextEntry::make('price'),
                    TextEntry::make('time'),
                ]),
                ComponentsSection::make('Service Image')->schema([
                    ImageEntry::make('serviceDetails.img')
                        ->label('Image')
                        ->getStateUsing(function ($record) {
                            return $record->serviceDetail
                                ->pluck('img')
                                ->map(function ($image) {
                                    return asset($image);
                                })
                                ->toArray();
                        })->disk('public')->width(250)->height(250),
                ]),
            ]);
    }
}
