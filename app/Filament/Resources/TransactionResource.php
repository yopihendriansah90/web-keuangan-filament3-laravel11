<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->preload()
                    ->searchable()
                    ->required(),
                Forms\Components\DatePicker::make('date_transaction')
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->prefix('Rp. ')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('note')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->formatStateUsing(function ($record) {
                        return Str::title($record->name);
                    }),
                Tables\Columns\TextColumn::make('category.name')
                    ->numeric()
                    ->formatStateUsing(fn($record) => Str::title($record->category->name))
                    ->sortable()
                    ->tooltip(fn($record):string=> $record->category->name)
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.is_expense')
                    ->label('Transaction Type')
                    ->getStateUsing(function ($record) {
                        if ($record->category->is_expense) {
                            return 'Pengeluaran';
                        }
                        return 'Pemasukan';
                    })
                    ->badge()
                    ->icon(fn($record) => $record->category->is_expense ?  'heroicon-o-arrow-down-right': 'heroicon-o-arrow-up-right')
                    ->color(function ($record) {
                        if ($record->category->is_expense) {
                            return 'danger';
                        }
                        return 'success';
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_transaction')
                    ->date("d/m/Y")
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('IDR',locale:'id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('note')
                    ->limit(10)
                    ->tooltip(fn($record):string=> $record->note)
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image')
                    ->visibility('public'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                
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
