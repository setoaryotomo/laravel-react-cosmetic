<?php

namespace App\Filament\Resources\BookingTransactions\Schemas;

use App\Filament\Resources\BookingTransactions\BookingTransactionResource;
use App\Models\Cosmetic;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;

class BookingTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                Wizard::make([
                    Step::make('Product and Price')
                        ->completedIcon('heroicon-m-hand-thumb-up')
                        ->description('Add your product items')
                        ->schema([

                            Grid::make(2)
                                ->schema([
                                    Repeater::make('transactionDetails')
                                        ->relationship('transactionDetails')
                                        ->schema([
                                            Select::make('cosmetic_id')
                                                ->relationship('cosmetic', 'name')
                                                ->searchable()
                                                ->preload()
                                                ->required()
                                                ->label('Select Product')
                                                ->live()
                                                ->afterStateUpdated(function ($state, callable $set) {
                                                    $cosmetic = Cosmetic::find($state);
                                                    $set('price', $cosmetic ? $cosmetic->price : 0);
                                                }),

                                            TextInput::make('price')
                                                ->required()
                                                ->numeric()
                                                ->readOnly()
                                                ->label('Price')
                                                ->hint('Filled Automatic'),

                                            TextInput::make('quantity')
                                                ->integer()
                                                ->default(1)
                                                ->required(),
                                        ])
                                        ->live()
                                        ->afterStateUpdated(function (Get $get, Set $set) {
                                            BookingTransactionResource::updateTotals($get, $set);
                                        })
                                        ->minItems(1)
                                        ->columnSpan('full')
                                        ->label('Choose Products')
                                ]),

                            Grid::make(4)
                                ->schema([
                                    TextInput::make('quantity')
                                        ->integer()
                                        ->label('Total Qty')
                                        ->readOnly()
                                        ->default(1)
                                        ->required(),
                                    TextInput::make('sub_total_amount')
                                        ->numeric()
                                        ->readOnly()
                                        ->label('Sub Total Amount'),
                                    TextInput::make('total_amount')
                                        ->numeric()
                                        ->readOnly()
                                        ->label('Total Amount'),
                                    TextInput::make('total_tax_amount')
                                        ->numeric()
                                        ->readOnly()
                                        ->label('Total Tax (11%)'),
                                ])
                        ]),

                    Step::make('Customer Information')
                        ->completedIcon('heroicon-m-hand-thumb-up')
                        ->description('For our marketing')
                        ->schema([

                            Grid::make(2)
                                ->schema([
                                    TextInput::make('name')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('phone')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('email')
                                        ->required()
                                        ->maxLength(255),
                                ]),
                        ]),

                    Step::make('Delivery Information')
                        ->completedIcon('heroicon-m-hand-thumb-up')
                        ->description('Put your address')
                        ->schema([

                            Grid::make(2)
                                ->schema([
                                    TextInput::make('city')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('post_code')
                                        ->required()
                                        ->maxLength(255),
                                    Textarea::make('address')
                                        ->required()
                                        ->maxLength(255)
                                ]),
                        ]),

                    Step::make('Payment Information')
                        ->completedIcon('heroicon-m-hand-thumb-up')
                        ->description('Review your payment')
                        ->schema([

                            Grid::make(3)
                                ->schema([
                                    TextInput::make('booking_trx_id')
                                        ->required()
                                        ->maxLength(255),
                                    ToggleButtons::make('is_paid')
                                        ->label('Apakah sdh membayar?')
                                        ->boolean()
                                        ->grouped()
                                        ->icons([
                                            true => 'heroicon-o-pencil',
                                            false => 'heroicon-o-clock',
                                        ])
                                        ->required(),

                                    FileUpload::make('proof')
                                        ->image()
                                        ->required(),
                                ]),
                        ]),
                ])
                    ->columnSpan('full')
                    ->columns(1)
                    ->skippable()
            ]);
    }
}
