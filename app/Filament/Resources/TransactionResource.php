<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
