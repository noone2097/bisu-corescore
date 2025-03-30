<?php

namespace App\Filament\ResearchAdmin\Resources;

use App\Filament\ResearchAdmin\Resources\DepartmentAccountsResource\Pages;
use App\Filament\ResearchAdmin\Resources\DepartmentAccountsResource\RelationManagers;
use App\Models\Departments;
use App\Models\User;
use App\Notifications\AccountSetupInvitation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DepartmentAccountsResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $modelLabel = 'Department Account';

    protected static ?string $navigationLabel = 'Department Accounts';

    protected static ?string $navigationGroup = 'Department Accounts';

    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role', 'department')
        ->withTrashed();
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
                                    ->label('Full Name')
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->label('Profile')
                    ->circular()
                    ->defaultImageUrl(url('/images/default_pfp.svg')),
                Tables\Columns\TextColumn::make('name')
                    ->label('Account Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->label('Department')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('is_active')
                    ->formatStateUsing(fn ($state): string => $state ? 'Active' : 'Inactive')
                    ->badge()
                    ->label('Account Status')
                    ->color(fn ($state): string => $state ? 'success' : 'danger')
                    ->icon(fn ($state): string => $state ? 'heroicon-m-check-circle' : 'heroicon-m-x-circle')
                    ->action(
                        Tables\Actions\Action::make('deactivateAccount')
                            ->requiresConfirmation()
                            ->modalIcon('heroicon-o-exclamation-triangle')
                            ->modalHeading('Deactivate Department Account')
                            ->modalDescription('Are you sure you want to deactivate this department account? This will reset the password and require a new setup email.')
                            ->hidden(fn (User $record): bool => !$record->is_active)
                            ->action(function (User $record): void {
                                $record->update([
                                    'is_active' => false,
                                    'password' => bcrypt('temporary-' . Str::random(16)),
                                    'password_reset_token' => null,
                                    'password_reset_expires_at' => null,
                                    'email_verified_at' => null
                                ]);

                                Notification::make()
                                    ->success()
                                    ->title('Status Updated')
                                    ->body('Department account has been deactivated')
                                    ->send();
                            })
                    ),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M j, Y : g:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('department')
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Filter by Department'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->actions([
                Tables\Actions\Action::make('sendSetupEmail')
                    ->icon('heroicon-o-envelope')
                    ->label('Send Email')
                    ->requiresConfirmation()
                    ->action(function (User $record) {
                        $record->notify(new AccountSetupInvitation('department'));
                        
                        Notification::make()
                            ->title('Setup email sent successfully')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (User $record): bool => !$record->password),
                    Tables\Actions\ActionGroup::make([
                        Tables\Actions\EditAction::make(),
                        Tables\Actions\ForceDeleteAction::make()
                        ->label('Delete')
                        ->requiresConfirmation()
                        ->modalHeading('Proceed with caution')
                        ->modalDescription('Are you sure you want to delete this department account? This action cannot be undone.'),
                    ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListDepartmentAccounts::route('/'),
            'create' => Pages\CreateDepartmentAccounts::route('/create'),
            'edit' => Pages\EditDepartmentAccounts::route('/{record}/edit'),
        ];
    }
}
