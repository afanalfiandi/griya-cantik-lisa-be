<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customerID')->relationship('customers', 'firstName'),
                Forms\Components\Select::make('slotID')->relationship('slot', 'time'),
                Forms\Components\Select::make('paymentStatus')->relationship('paymentStatus', 'paymentStatusName'),
                Forms\Components\Select::make('transactionStatus')->relationship('transactionStatus', 'transactionStatus'),
                Forms\Components\Select::make('paymentMethod')->relationship('paymentMethod', 'paymentMethodName'),
                Forms\Components\Select::make('specialist')->relationship('specialist', 'specialistName'),
                Forms\Components\DatePicker::make('bookingDate'),
                Forms\Components\DatePicker::make('dateFor'),
                Forms\Components\TextInput::make('notes'),
                Forms\Components\TextInput::make('subtotal')->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transactionNumber'),
                Tables\Columns\TextColumn::make('bookingDate'),
                Tables\Columns\TextColumn::make('dateFor')->label('Service date'),
                Tables\Columns\TextColumn::make('customers.firstName'),
                Tables\Columns\TextColumn::make('paymentStatus.paymentStatusName'),
                Tables\Columns\TextColumn::make('transactionStatus.transactionStatus'),
                Tables\Columns\TextColumn::make('paymentMethod.paymentMethodName'),
                Tables\Columns\TextColumn::make('specialist.specialistName'),
                Tables\Columns\TextColumn::make('notes'),
                Tables\Columns\TextColumn::make('subtotal'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
            'view' => Pages\ViewTransaction::route('/{record}'),
        ];
    }

    public static function infoList(Infolist $infoList): Infolist
    {
        return $infoList
            ->schema([
                Section::make('Transaction Information')->schema([
                    TextEntry::make('transactionNumber'),
                    TextEntry::make('customers.firstName'),
                    TextEntry::make('slot.time'),
                    TextEntry::make('paymentStatus.paymentStatus'),
                    TextEntry::make('transactionStatus.transactionStatus'),
                    TextEntry::make('paymentMethod.bank'),
                    TextEntry::make('specialist.specialistName'),
                    TextEntry::make('vaNumber'),
                    TextEntry::make('bookingDate'),
                    TextEntry::make('dateFor'),
                    TextEntry::make('notes'),
                    TextEntry::make('subtotal'),
                ]),
                Section::make('Service')->schema([
                    TextEntry::make('serviceName')
                        ->label('Service Name')
                        ->getStateUsing(function ($record) {
                            // Mengambil transaksi detail pertama
                            $transactionDetail = $record->transactionDetail->first(); // Ambil detail transaksi pertama

                            // Cek jika ada detail transaksi dan layanan terkait
                            $service = $transactionDetail ? $transactionDetail->services : null;
                            return $service ? $service->serviceName : 'No Service'; // Menampilkan nama service atau fallback
                        }),
                ]),

            ]);
    }
}
