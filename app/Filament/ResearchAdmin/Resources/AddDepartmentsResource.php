<?php

namespace App\Filament\ResearchAdmin\Resources;

use App\Filament\ResearchAdmin\Resources\AddDepartmentsResource\Pages;
use App\Models\Departments;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;

class AddDepartmentsResource extends Resource
{
    protected static ?string $model = Departments::class;

    protected static ?string $modelLabel = 'Department';

    protected static ?string $navigationLabel = 'Add Department';

    protected static ?string $navigationGroup = 'Department';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Department Information')
                    ->description('Add a new academic department')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Department Name')
                            ->required()
                            ->unique()
                            ->placeholder('e.g. Department of Computer Science')
                            ->helperText('Enter the complete name of the department'),
                        Forms\Components\TextInput::make('code')
                            ->label('Code')
                            ->required()
                            ->unique()
                            ->placeholder('e.g. DCS')
                            ->helperText('Enter a unique code identifier for the department'),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\Create::route('/'),
        ];
    }

    public static function canViewAny(): bool
    {
        return true;
    }
}