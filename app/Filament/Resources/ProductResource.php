<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Category;
use App\Models\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->label('Názov')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

            TextInput::make('slug')
                ->label('Slug')
                ->disabled()
                ->dehydrated(),

            TextInput::make('product_number')
                ->label('Číslo produktu')
                ->required()
                ->unique(ignoreRecord: true),

            TextInput::make('price')
                ->label('Cena')
                ->numeric()
                ->prefix('€')
                ->required(),

            MultiSelect::make('categories')
                ->label('Kategórie')
                ->relationship('categories', 'name')
                ->options(
                    Category::where('active', true)->pluck('name', 'id')
                )
                ->preload()
                ->required(),

            Placeholder::make('')->columnSpan(1),

            RichEditor::make('description')
                ->label('Popis'),

            SpatieMediaLibraryFileUpload::make('image')
                ->label('Obrázok')
                ->collection('product_images')
                ->image()
                ->preserveFilenames()
                ->maxFiles(1),



            Toggle::make('active')
                ->label('Aktívny'),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('slug')->limit(20),
                TextColumn::make('name')
                    ->label('Názov')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('product_number')
                    ->label('Číslo produktu')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('price')
                    ->label('Cena')
                    ->money('eur')
                    ->sortable(),

                ToggleColumn::make('active')
                    ->label('Aktívny')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                ViewAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Zmazať vybrané'),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
            'view' => Pages\ShowProduct::route('/{record}'),
        ];
    }
}
