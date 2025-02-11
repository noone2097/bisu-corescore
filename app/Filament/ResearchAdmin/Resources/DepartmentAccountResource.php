<?php

namespace App\Filament\ResearchAdmin\Resources;

use App\Filament\ResearchAdmin\Resources\DepartmentAccountResource\Pages;
use App\Models\DepartmentAccount;
use App\Models\DepartmentEntity;
use App\Notifications\DepartmentPasswordSetup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;

class DepartmentAccountResource extends Resource
{
    protected static ?string $model = DepartmentAccount::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Department Management';
    protected static ?string $navigationLabel = 'Department Accounts';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('department_avatar')
                    ->image()
                    ->imageEditor()
                    ->disk('public')
                    ->directory('department-avatars')
                    ->label('Department Avatar'),
                Forms\Components\Select::make('department_entity_id')
                    ->label('Department')
                    ->relationship('departmentEntity', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('code')
                            ->required()
                            ->maxLength(50)
                            ->unique(DepartmentEntity::class),
                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535),
                    ])
                    ->required(),
                Forms\Components\TextInput::make('department_name')
                    ->required()
                    ->maxLength(255)
                    ->label('Account Name'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->label('Account Email'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('department_avatar')
                    ->disk('public')
                    ->visibility('public')
                    ->circular()
                    ->size(35)
                    ->defaultImageUrl(asset('images/bisu_logo.png')),
                Tables\Columns\TextColumn::make('departmentEntity.name')
                    ->label('Department')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('department_name')
                    ->label('Account Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                    Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime('M j, Y : g:i A')
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
                            ->modalHeading('Deactivate Department Account')
                            ->modalDescription('Are you sure you want to deactivate this department account? This will reset the password and require a new setup email.')
                            ->hidden(fn (DepartmentAccount $record): bool => $record->status === 'inactive')
                            ->action(function (DepartmentAccount $record): void {
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
                                    ->body('Department account has been deactivated')
                                    ->send();
                            })
                    ),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M j, Y : g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('department_entity')
                    ->relationship('departmentEntity', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Department'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive'
                    ])
            ])
            ->actions([
                Tables\Actions\Action::make('send_setup_email')
                    ->label('Send Email')
                    ->icon('heroicon-o-envelope')
                    ->color('success')
                    ->requiresConfirmation()
                    ->hidden(fn (DepartmentAccount $record): bool =>
                        $record->email_verified_at !== null || $record->status === 'active'
                    )
                    ->action(function (DepartmentAccount $record): void {
                        // Generate new token
                        $token = Str::random(64);

                        $record->update([
                            'password_reset_token' => $token,
                            'password_reset_expires_at' => now()->addHours(24),
                        ]);

                        // Send notification
                        $record->notify(new DepartmentPasswordSetup($token));

                        // Show success notification
                        Notification::make()
                            ->success()
                            ->title('Setup Email Sent')
                            ->body('A password setup email has been sent to the department.')
                            ->send();
                    }),
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
            'create' => Pages\CreateDepartmentAccount::route('/create'),
            'edit' => Pages\EditDepartmentAccount::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}