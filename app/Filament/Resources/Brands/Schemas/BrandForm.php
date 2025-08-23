<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                TextInput::make('name')
                ->required()
                ->maxLength(255),

                FileUpload::make('photo')
                ->image()
                ->required(),
            ]);
    }
}
