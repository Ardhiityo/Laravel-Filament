<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Customer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\CustomerResource\Pages;
use Filament\Tables\Columns\TextColumn;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;
    protected static ?string $navigationIcon = 'heroicon-o-user';
    // protected static ?string $navigationLabel = 'Pelanggan';
    protected static ?string $navigationGroup = 'Users';
    // protected static ?string $slug = 'pelanggan';
    // protected static ?string $modelLabel = 'Pelanggan';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    // ->label('Fullname')
                    ->required()
                    ->placeholder('Your fullname...'),
                TextInput::make('address')
                    ->required(),
                TextInput::make('phone')
                    // ->numeric()
                    ->required(),
                TextInput::make('customer_code')
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {

        return $table
            ->columns([
                TextColumn::make('name')
                    // ->label('Fullname')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('address')
                    ->copyable()
                    ->copyMessage('Address is copied'),
                TextColumn::make('phone'),
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
