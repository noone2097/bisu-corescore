<?php

namespace App\Filament\OfficeAdmin\Resources;

use App\Filament\OfficeAdmin\Resources\AddOfficeAccountsResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;

class AddOfficeAccountsResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $modelLabel = 'Office Account';
    protected static ?string $pluralModelLabel = 'Office Accounts';

    protected static ?string $navigationGroup = 'Account Management';
    protected static ?string $navigationLabel = 'Add Office Account';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'mdi-office-building-plus';
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Add New Office Account')
                    ->icon('heroicon-o-plus-circle')
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\FileUpload::make('avatar')
                                    ->image()
                                    ->disk('public')
                                    ->directory('avatars')
                                    ->label('Office Avatar')
                                    ->helperText('Upload a circle image for best results')
                                    ->imageResizeMode('cover')
                                    ->imageCropAspectRatio('1:1')
                                    ->imageEditor()
                                    ->avatar()
                                    ->circleCropper()
                                    ->imagePreviewHeight('200')
                                    ->columnSpan(4),
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->maxLength(255)
                                            ->label('Office Name')
                                            ->placeholder('e.g. MIS Office')
                                            ->helperText('Enter the complete name of the office')
                                            ->prefixIcon('heroicon-m-building-office')
                                            ->autocapitalize('words'),
                                            
                                        Forms\Components\TextInput::make('email')
                                            ->email()
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(255)
                                            ->label('Email Address')
                                            ->placeholder('office@bisu.edu.ph')
                                            ->helperText('This email will be used for account access')
                                            ->prefixIcon('heroicon-m-envelope')
                                            ->hint('Must be a valid institutional email address'),

                                        Forms\Components\Hidden::make('role')
                                            ->default('office'),

                                        Forms\Components\Hidden::make('is_active')
                                            ->default(false),
                                            
                                        Forms\Components\Hidden::make('password')
                                            ->default(null),
                                    ])
                                    ->columnSpan(8),
                            ])->columns(12),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\CreateOfficeAccounts::route('/'),
        ];
    }

    public static function canViewAny(): bool
    {
        return true;
    }
}