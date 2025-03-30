<?php

namespace App\Filament\ResearchAdmin\Resources;

use App\Filament\ResearchAdmin\Resources\AddDepartmentAccountsResource\Pages;
use App\Models\Departments;
use App\Models\User;
use App\Notifications\AccountSetupInvitation;
use App\Traits\HasBackUrl;
use Filament\Forms;
use Filament\Actions\Action;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;

class AddDepartmentAccountsResource extends Resource
{
    use HasBackUrl;

    protected static ?string $model = User::class;

    protected static ?string $modelLabel = 'Department Account';

    protected static ?string $navigationLabel = 'Add Department Account';

    protected static ?string $navigationGroup = 'Department Accounts';

    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role', 'department');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\Section::make('Profile Picture')
                            ->description('Upload a professional photo for your profile')
                            ->columnSpan(1)
                            ->schema([
                                Forms\Components\FileUpload::make('avatar')
                                    ->label('')
                                    ->image()
                                    ->disk('public')
                                    ->directory('avatars/research-admin')
                                    ->imageResizeMode('cover')
                                    ->imageCropAspectRatio('1:1')
                                    ->imageEditor()
                                    ->avatar()
                                    ->circleCropper()
                                    ->maxSize(2048)
                                    ->uploadingMessage('Uploading image...')
                                    ->label('Profile Picture')
                                    ->helperText('Upload a high-quality photo. This will be displayed on your profile.')
                                    ->alignCenter(),
                            ]),
                        Forms\Components\Section::make('Account Information')
                            ->description('Manage your personal details and security')
                            ->columnSpan(1)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Account Name')
                                    ->helperText('Enter your complete name as it should appear on your profile')
                                    ->placeholder('Enter your full name'),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255)
                                    ->helperText('This email will be used for account setup and communications')
                                    ->placeholder('Enter your email address'),
                                Forms\Components\Select::make('department_id')
                                    ->label('Department')
                                    ->options(Departments::all()->pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->relationship('department', 'name')
                                    ->preload()
                                    ->helperText('Select which department this account belongs to'),
                            ]),
                    ]),
                Forms\Components\Hidden::make('role')
                    ->default('department'),
                Forms\Components\Hidden::make('is_active')
                    ->default(false),
            ]);
    }

    protected function getCreatedNotification(): ?Notification
    {
        return new AccountSetupInvitation('department');
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

    protected function hasConfiguredCancelFormAction(): bool
    {
        return false;
    }
    
}