<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AddAdminAccountsResource\Pages;
use App\Models\AdminAccounts;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;

class AddAdminAccountsResource extends Resource
{
    protected static ?string $model = AdminAccounts::class;

    protected static ?string $navigationGroup = 'Account Management';
    protected static ?string $navigationLabel = 'Add New Admin';
    protected static ?int $navigationSort = 1;
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Add New Admin Account')
                    ->description('Create a new administrator account')
                    ->schema([
                        Forms\Components\FileUpload::make('admin_avatar')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('admin-avatars')
                            ->label('Profile Picture'),
                            
                        Forms\Components\TextInput::make('admin_name')
                            ->required()
                            ->maxLength(255)
                            ->label('Admin Name'),
                            
                        Forms\Components\TextInput::make('admin_email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->label('Email Address'),

                        Forms\Components\Select::make('role')
                            ->required()
                            ->options([
                                'Research Admin' => 'Research Admin',
                                'Office Admin' => 'Office Admin',
                            ])
                            ->label('Admin Role'),

                        Forms\Components\Hidden::make('status')
                            ->default('inactive'),
                    ])->columns(2),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\CreateAddAdminAccounts::route('/'),
        ];
    }

    public static function canViewAny(): bool
    {
        return true;
    }
}
