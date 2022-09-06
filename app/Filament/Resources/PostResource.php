<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Blog';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required(),
                TextInput::make('published_at')
                    ->placeholder('2022-09-14 09:41:00')
                    ->helperText('Please enter the published at date in the format Y-m-d H:i:s')
                    ->afterStateHydrated(fn (?Post $record, TextInput $component) => $component->state($record->published_at?->format('Y-m-d H:i:s'))),
                Textarea::make('content')
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('published_at')
                    ->getStateUsing(function (Post $record) {
                        if ($record->published_at === null) {
                            return '-';
                        }

                        return $record->published_at->toDateTimeString();
                    })
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->enum([
                        'draft' => 'Draft',
                        'scheduled' => 'Scheduled',
                        'published' => 'Published',
                    ])
                    ->colors([
                        'success' => 'published',
                    ]),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->reorder()
            ->orderBy(new Expression("CASE WHEN published_at IS NULL THEN 0 ELSE 1 END"))
            ->orderByDesc('published_at');
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
