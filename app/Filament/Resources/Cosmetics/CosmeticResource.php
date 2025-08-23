<?php

namespace App\Filament\Resources\Cosmetics;

use App\Filament\Resources\Cosmetics\Pages\CreateCosmetic;
use App\Filament\Resources\Cosmetics\Pages\EditCosmetic;
use App\Filament\Resources\Cosmetics\Pages\ListCosmetics;
use App\Filament\Resources\Cosmetics\Schemas\CosmeticForm;
use App\Filament\Resources\Cosmetics\Tables\CosmeticsTable;
use App\Models\Cosmetic;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CosmeticResource extends Resource
{
    protected static ?string $model = Cosmetic::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return CosmeticForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CosmeticsTable::configure($table);
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
            'index' => ListCosmetics::route('/'),
            'create' => CreateCosmetic::route('/create'),
            'edit' => EditCosmetic::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
