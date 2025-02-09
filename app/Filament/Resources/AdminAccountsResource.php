<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminAccountsResource\Pages;
use App\Filament\Resources\AdminAccountsResource\RelationManagers;
use App\Models\AdminAccounts;
use App\Notifications\AdminPasswordSetup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;

class AdminAccountsResource extends Resource
{
    protected static ?string $model = AdminAccounts::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Account Management';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
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
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->label('Admin Email'),
                Forms\Components\Select::make('role')
                    ->required()
                    ->options([
                        'Research Admin' => 'Research Admin',
                        'Office Admin' => 'Office Admin',
                    ])
                    ->label('Admin Role'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('admin_avatar')
                    ->disk('public')
                    ->visibility('public')
                    ->circular()
                    ->size(60)
                    ->defaultImageUrl(asset('images/bisu_logo.png')),
                Tables\Columns\TextColumn::make('admin_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('admin_email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Research Admin' => 'info',
                        'Office Admin' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('email_verified_at')
                ->getStateUsing(function ($record) {
                    // Ensure the email_verified_at field is not null
                    if ($record->email_verified_at) {
                        // Parse the datetime value and format it
                        return Carbon::parse($record->email_verified_at)->format('M j, Y : g:i A');
                    }
                    return null; // Return null if the field is null
                })
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->badge()
                    ->color(fn (string $state): string => $state === 'active' ? 'success' : 'danger')
                    ->icon(fn (string $state): string => $state === 'active' ? 'heroicon-m-check-circle' : 'heroicon-m-x-circle')
                    ->action(
                        Tables\Actions\Action::make('deactivateAccount')
                            ->requiresConfirmation()
                            ->modalIcon('heroicon-o-exclamation-triangle')
                            ->modalHeading('Deactivate Account')
                            ->modalDescription('Are you sure you want to deactivate this account? This will reset the password and require a new setup email.')
                            ->hidden(fn (AdminAccounts $record): bool => $record->status === 'inactive')
                            ->action(function (AdminAccounts $record): void {
                                $record->update([
                                    'status' => 'inactive',
                                    'password' => bcrypt('temporary-' . Str::random(16)),
                                    'password_reset_token' => null,
                                    'password_reset_expires_at' => null,
                                    'email_verified_at' => null
                                ]);

                                Notification::make()
                                    ->success()
                                    ->title('Status Updated')
                                    ->body('Account has been deactivated')
                                    ->send();
                            })
                    ),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('send_setup_email')
                    ->label('Send Email')
                    ->icon('heroicon-o-envelope')
                    ->color('success')
                    ->requiresConfirmation()
                    ->hidden(fn (AdminAccounts $record): bool =>
                        $record->email_verified_at !== null || $record->status === 'active'
                    )
                    ->action(function (AdminAccounts $record): void {
                        // Generate new token
                        $token = Str::random(64);

                        $record->update([
                            'password_reset_token' => $token,
                            'password_reset_expires_at' => now()->addHours(24),
                        ]);

                        // Send notification
                        $record->notify(new AdminPasswordSetup($token));

                        // Show success notification
                        Notification::make()
                            ->success()
                            ->title('Setup Email Sent')
                            ->body('A password setup email has been sent to the admin.')
                            ->send();
                    }),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAdminAccounts::route('/'),
            'create' => Pages\CreateAdminAccounts::route('/create'),
            'edit' => Pages\EditAdminAccounts::route('/{record}/edit'),
        ];
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
