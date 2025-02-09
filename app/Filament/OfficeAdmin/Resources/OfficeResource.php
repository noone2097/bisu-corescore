<?php

namespace App\Filament\OfficeAdmin\Resources;

use App\Filament\OfficeAdmin\Resources\OfficeResource\Pages;
use App\Filament\OfficeAdmin\Resources\OfficeResource\RelationManagers;
use App\Models\Office;
use App\Notifications\OfficePasswordSetup;
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

class OfficeResource extends Resource
{
    protected static ?string $model = Office::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = 'Account Management';
    protected static ?string $navigationLabel = 'Offices';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
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
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->label('Office Email'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('office_avatar')
                    ->disk('public')
                    ->visibility('public')
                    ->circular()
                    ->size(60)
                    ->defaultImageUrl(asset('images/bisu_logo.png')),
                Tables\Columns\TextColumn::make('office_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->getStateUsing(function ($record) {
                        if ($record->email_verified_at) {
                            return Carbon::parse($record->email_verified_at)->format('M j, Y : g:iA');
                        }
                        return null;
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
                            ->modalDescription('Are you sure you want to deactivate this office account? This will reset the password and require a new setup email.')
                            ->hidden(fn (Office $record): bool => $record->status === 'inactive')
                            ->action(function (Office $record): void {
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
                                    ->body('Office account has been deactivated')
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
                    ->hidden(fn (Office $record): bool =>
                        $record->email_verified_at !== null || $record->status === 'active'
                    )
                    ->action(function (Office $record): void {
                        // Generate new token
                        $token = Str::random(64);

                        $record->update([
                            'password_reset_token' => $token,
                            'password_reset_expires_at' => now()->addHours(24),
                        ]);

                        // Send notification
                        $record->notify(new OfficePasswordSetup($token));

                        // Show success notification
                        Notification::make()
                            ->success()
                            ->title('Setup Email Sent')
                            ->body('A password setup email has been sent to the office.')
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
            'index' => Pages\ListOffices::route('/'),
            'create' => Pages\CreateOffice::route('/create'),
            'edit' => Pages\EditOffice::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
