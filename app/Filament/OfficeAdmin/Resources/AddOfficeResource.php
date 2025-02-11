<?php

namespace App\Filament\OfficeAdmin\Resources;

use App\Filament\OfficeAdmin\Resources\AddOfficeResource\Pages;
use App\Models\Office;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;

class AddOfficeResource extends Resource
{
    protected static ?string $model = Office::class;

    protected static ?string $navigationGroup = 'Account Management';
    protected static ?string $navigationLabel = 'Add Office';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'mdi-office-building-plus';
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Add New Office Account')
                    ->description('Create a new office account')
                    ->schema([
                        Forms\Components\FileUpload::make('office_avatar')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('office-avatars')
                            ->label('Office Avatar'),
                            
                        Forms\Components\TextInput::make('office_name')
                            ->required()
                            ->maxLength(255)
                            ->label('Office Name'),
                            
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->label('Email Address'),

                        Forms\Components\Hidden::make('status')
                            ->default('inactive'),
                    ])->columns(2),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\CreateOffice::route('/'),
        ];
    }

    public static function canViewAny(): bool
    {
        return true;
    }
}