<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DetailResource\Pages;
use App\Models\Detail;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DetailResource extends Resource
{
    protected static ?string $model = Detail::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';
    protected static ?string $navigationGroup = 'Orders';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required(),
                Select::make('invoice_id')
                    ->relationship('invoice', 'id')
                    ->required(),
                TextInput::make('product_name')
                    ->required(),
                TextInput::make('discount')
                    ->numeric(),
                TextInput::make('price')
                    ->numeric()
                    ->required(),
                TextInput::make('sub_total')
                    ->numeric()
                    ->required(),
                TextInput::make('qty')
                    ->numeric()
                    ->required(),
                TextInput::make('total_qty')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product_id'),
                TextColumn::make('invoice_id'),
                TextColumn::make('product_name'),
                TextColumn::make('discount'),
                TextColumn::make('price'),
                TextColumn::make('sub_total'),
                TextColumn::make('qty'),
                TextColumn::make('total_qty')
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
            'index' => Pages\ListDetails::route('/'),
            'create' => Pages\CreateDetail::route('/create'),
            'edit' => Pages\EditDetail::route('/{record}/edit'),
        ];
    }
}
