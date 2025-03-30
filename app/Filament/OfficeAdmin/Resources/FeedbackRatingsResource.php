<?php

namespace App\Filament\OfficeAdmin\Resources;

use App\Filament\OfficeAdmin\Resources\FeedbackRatingsResource\Pages;
use App\Models\Feedback;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class FeedbackRatingsResource extends Resource
{
    protected static ?string $model = Feedback::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationGroup = 'Ratings';

    protected static ?string $navigationLabel = 'Feedback Ratings';

    protected static ?int $navigationSort = 2;

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->description('Note: The highest possible rating for all rating columns is 5')
            ->columns([
                TextColumn::make('office.office_name')
                    ->label('Office')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('visitor.first_name')
                    ->label('Visitor Name')
                    ->formatStateUsing(fn ($state, $record) => $record->visitor->first_name . ' ' . $record->visitor->last_name)
                    ->sortable()
                    ->searchable(),
                TextColumn::make('responsiveness')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 2) : 'N/A')
                    ->sortable(),
                TextColumn::make('reliability')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 2) : 'N/A')
                    ->sortable(),
                TextColumn::make('access_facilities')
                    ->label('Access & Facilities')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 2) : 'N/A')
                    ->sortable(),
                TextColumn::make('communication')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 2) : 'N/A')
                    ->sortable(),
                TextColumn::make('costs')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 2) : 'N/A')
                    ->sortable(),
                TextColumn::make('integrity')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 2) : 'N/A')
                    ->sortable(),
                TextColumn::make('assurance')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 2) : 'N/A')
                    ->sortable(),
                TextColumn::make('outcome')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 2) : 'N/A')
                    ->sortable(),
                TextColumn::make('average_rating')
                    ->label('Average Rating')
                    ->state(fn (Feedback $record) => number_format($record->average_rating, 2))
                    ->sortable(),
            ])
            ->paginated([10, 25, 50, 100]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFeedbackRatings::route('/')
        ];
    }
}