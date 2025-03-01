<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\Invoice;
use App\Models\Product;
use Filament\Forms\Get;
use App\Models\Customer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Detail;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;
    protected static ?string $navigationGroup = 'Orders';
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('customer_id')
                    ->relationship(name: 'customer', titleAttribute: 'name')
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $customer = Customer::find($state);
                        if ($customer) {
                            $set('customer_code', $customer->customer_code);
                        }
                    })
                    ->required(),
                DatePicker::make('invoice_date')
                    ->required(),
                TextInput::make('customer_code')
                    ->readOnly(),
                TextArea::make('invoice_detail'),
                Repeater::make('details')
                    ->relationship()
                    ->schema([
                        Select::make('product_id')
                            ->relationship('product', 'name')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $product = Product::find($state);
                                if ($product) {
                                    $set('product_name', $product->name);
                                    $set('price', $product->price);
                                }
                            }),
                        TextInput::make('product_name')
                            ->required()
                            ->readOnly(),
                        TextInput::make('price')
                            ->numeric()
                            ->required(),
                        TextInput::make('qty')
                            ->numeric()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, Get $get) {
                                $total = $state * $get('price');
                                $set('total_qty', $total);
                            }),
                        TextInput::make('total_qty')
                            ->numeric()
                            ->required(),
                        TextInput::make('discount')
                            ->numeric()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, Get $get) {
                                if ($state == null) {
                                    $set('sub_total', 0);
                                } else {
                                    $subTotal = ($get('total_qty') / 100) * $state;
                                    $set('sub_total', $subTotal);
                                }
                            }),
                        TextInput::make('sub_total')
                            ->numeric()
                            ->required(),
                    ]),
                TextInput::make('total')
                    ->numeric()
                    ->required()
                    ->placeholder(function ($state, callable $set, Get $get) {
                        $total = collect($get('details'))->pluck('sub_total')->sum();
                        if ($total == null) {
                            $set('total', 0);
                        }
                        $set('total', $total);
                    }),
                TextInput::make('nominal_charge')
                    ->numeric()
                    ->required(),
                TextInput::make('charge')
                    ->numeric()
                    ->required(),
                TextInput::make('total_final')
                    ->numeric()
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name'),
                TextColumn::make('invoice_date'),
                TextColumn::make('invoice_detail'),
                TextColumn::make('total'),
                TextColumn::make('nominal_charge'),
                TextColumn::make('charge'),
                TextColumn::make('total_final')
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
