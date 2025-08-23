<?php

namespace App\Filament\Resources\Cosmetics\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

class CosmeticForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                Fieldset::make('Details')
                ->columnSpanFull()
                ->schema([
                    TextInput::make('name')
                    ->maxLength(255)
                    ->required(),

                    FileUpload::make('thumbnail')
                    ->required()
                    ->image(),

                    TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('IDR'),

                    TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->prefix('Qtys'),
                ]),

                Fieldset::make('Additionals')
                ->columnSpanFull()
                ->schema([
                    Repeater::make('benefits')
                    ->relationship('benefits')
                    ->schema([
                        TextInput::make('name')
                        ->required(),
                    ]),

                    Repeater::make('photos')
                    ->relationship('photos')
                    ->schema([
                        FileUpload::make('photo')
                        ->image()
                        ->required(),
                    ]),

                    Textarea::make('about')
                    ->required(),

                    Select::make('is_popular')
                    ->options([
                        true => 'Popular',
                        false => 'Not Popular',
                    ])
                    ->required(),

                    Select::make('category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                    Select::make('brand_id')
                    ->relationship('brand','name')
                    ->searchable()
                    ->preload()
                    ->required(),
                ]),


            ]);
    }
}
