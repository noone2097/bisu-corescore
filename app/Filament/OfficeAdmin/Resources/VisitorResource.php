<?php

namespace App\Filament\OfficeAdmin\Resources;

use App\Filament\OfficeAdmin\Resources\VisitorResource\Pages;
use App\Models\Visitor;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class VisitorResource extends Resource
{
    protected static ?string $model = Visitor::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Visitors';

    protected static ?string $navigationGroup = 'Ratings';

    protected static ?int $navigationSort = 3;

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                TextColumn::make('first_name')
                    ->label('First Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('last_name')
                    ->label('Last Name')
                    ->sortable()
                    ->searchable(),
                ImageColumn::make('signature')
                    ->label('Digital Signature')
                    ->height(50),
                TextColumn::make('feedback.client_type')
                    ->label('Client Type')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('feedback.region_of_residence')
                    ->label('Region')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('feedback.sex')
                    ->label('Sex')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('feedback.date_of_visit')
                    ->label('Date of Visit')
                    ->date('M d, Y')
                    ->sortable(),
                TextColumn::make('feedback.time_of_visit')
                    ->label('Time of Visit')
                    ->formatStateUsing(fn (string $state) => date('g:i A', strtotime($state)))
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50, 100]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVisitors::route('/')
        ];
    }
}