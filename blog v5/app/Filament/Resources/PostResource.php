<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Posty';

    protected static ?string $modelLabel = 'Post';

    protected static ?string $pluralModelLabel = 'Posty';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Podstawowe info')->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Tytuł')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) =>
                        $set('slug', Str::slug($state))
                    ),

                Forms\Components\TextInput::make('slug')
                    ->label('Slug (URL)')
                    ->required()
                    ->maxLength(255)
                    ->unique(Post::class, 'slug', ignoreRecord: true),

                Forms\Components\Select::make('category')
                    ->label('Kategoria')
                    ->options([
                        'technologia' => '💻 Technologia',
                        'zycie'       => '🌿 Życie',
                        'jedzenie'    => '🍕 Jedzenie',
                        'muzyka'      => '🎵 Muzyka',
                        'sport'       => '⚽ Sport',
                        'inne'        => '🎲 Inne',
                    ])
                    ->required(),
            ]),

            Forms\Components\Section::make('Treść')->schema([
                Forms\Components\Textarea::make('excerpt')
                    ->label('Krótki opis (zajawka)')
                    ->rows(3)
                    ->maxLength(500),

                Forms\Components\RichEditor::make('content')
                    ->label('Treść posta')
                    ->required()
                    ->columnSpanFull(),
            ]),

            Forms\Components\Section::make('Publikacja')->schema([
                Forms\Components\Toggle::make('published')
                    ->label('Opublikowany?')
                    ->default(false)
                    ->live(),

                Forms\Components\DateTimePicker::make('published_at')
                    ->label('Data publikacji')
                    ->default(now()),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Tytuł')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category')
                    ->label('Kategoria')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'technologia' => 'info',
                        'zycie'       => 'success',
                        'jedzenie'    => 'warning',
                        'muzyka'      => 'primary',
                        'sport'       => 'danger',
                        default       => 'gray',
                    }),

                Tables\Columns\IconColumn::make('published')
                    ->label('Opublikowany')
                    ->boolean(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Data')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('published')
                    ->label('Status publikacji'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit'   => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
