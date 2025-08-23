<?php

namespace App\Filament\Resources\BookingTransactions;

use App\Filament\Resources\BookingTransactions\Pages\CreateBookingTransaction;
use App\Filament\Resources\BookingTransactions\Pages\EditBookingTransaction;
use App\Filament\Resources\BookingTransactions\Pages\ListBookingTransactions;
use App\Filament\Resources\BookingTransactions\Schemas\BookingTransactionForm;
use App\Filament\Resources\BookingTransactions\Tables\BookingTransactionsTable;
use App\Models\BookingTransaction;
use App\Models\Cosmetic;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class BookingTransactionResource extends Resource
{
    protected static ?string $model = BookingTransaction::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';
    protected static string|UnitEnum|null $navigationGroup = 'Customer';

    public static function updateTotals(Get $get, Set $set) : void {
        $selectedCosmetics = collect($get('transactionDetails'))->filter(fn($item)
        => !empty($item['cosmetic_id']) && !empty($item['quantity']));

        $prices = Cosmetic::find($selectedCosmetics->pluck('cosmetic_id'))->pluck('price','id');

        $subtotal = $selectedCosmetics->reduce(function ($subtotal, $item) use ($prices) {
            return $subtotal + ($prices[$item['cosmetic_id']] * $item['quantity']);
        }, 0);

        $total_tax_amount = round($subtotal * 0.11);

        $total_amount = round($subtotal * $total_tax_amount);

        $total_quantity = $selectedCosmetics->sum('quantity');

        $set('total_amount', number_format($total_amount, 0 , '.', ''));
        
        $set('total_tax_amount', number_format($total_tax_amount, 0 , '.', ''));
        
        $set('sub_total_amount', number_format($subtotal, 0 , '.', ''));
        $set('quantity', $total_quantity);

        
    }

    public static function form(Schema $schema): Schema
    {
        return BookingTransactionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BookingTransactionsTable::configure($table);
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
            'index' => ListBookingTransactions::route('/'),
            'create' => CreateBookingTransaction::route('/create'),
            'edit' => EditBookingTransaction::route('/{record}/edit'),
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
