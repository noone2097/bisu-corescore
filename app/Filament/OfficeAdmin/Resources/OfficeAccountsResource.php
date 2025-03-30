<?php

namespace App\Filament\OfficeAdmin\Resources;

use App\Filament\OfficeAdmin\Resources\OfficeAccountsResource\Pages;
use App\Models\User;
use App\Notifications\AccountSetupInvitation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;

class OfficeAccountsResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $modelLabel = 'Office Account';
    protected static ?string $pluralModelLabel = 'Office Accounts';

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = 'Account Management';
    protected static ?string $navigationLabel = 'Office Accounts';
    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role', 'office');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Edit Office Account')
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
                                            ->maxLength(255)
                                            ->unique(ignoreRecord: true)
                                            ->label('Office Email')
                                            ->placeholder('office@bisu.edu.ph')
                                            ->helperText('This email will be used for account access')
                                            ->prefixIcon('heroicon-m-envelope')
                                            ->hint('Must be a valid institutional email address'),
                                    ])
                                    ->columnSpan(8),
                            ])->columns(12),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->description('Click any row to edit the office account. Click the status badge to deactivate an active account.')
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(asset('images/default_pfp.svg'))
                    ->size(40)
                    ->ring(2)
                    ->stacked(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->getStateUsing(function ($record) {
                        if ($record->email_verified_at) {
                            return Carbon::parse($record->email_verified_at)->format('M j, Y : g:i A');
                        }
                        return null;
                    })
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->action(
                        Tables\Actions\Action::make('toggleActive')
                            ->requiresConfirmation()
                            ->modalIcon('heroicon-o-exclamation-triangle')
                            ->modalHeading(fn ($record) => $record->is_active ? 'Deactivate Account' : 'Activate Account')
                            ->modalDescription(fn ($record) => $record->is_active 
                                ? 'Are you sure you want to deactivate this office account?' 
                                : 'Are you sure you want to activate this office account?')
                            ->action(function (User $record): void {
                                $record->update([
                                    'is_active' => !$record->is_active,
                                ]);

                                Notification::make()
                                    ->success()
                                    ->title('Status Updated')
                                    ->body('Office account status has been updated')
                                    ->send();
                            })
                    ),
                Tables\Columns\TextColumn::make('created_at')
                    ->getStateUsing(function ($record) {
                        if ($record->created_at) {
                            return Carbon::parse($record->created_at)->format('M j, Y : g:i A');
                        }
                        return null;
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->getStateUsing(function ($record) {
                        if ($record->updated_at) {
                            return Carbon::parse($record->updated_at)->format('M j, Y : g:i A');
                        }
                        return null;
                    })
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
                    ->hidden(fn (User $record): bool =>
                        $record->email_verified_at !== null || $record->is_active
                    )
                    ->action(function (User $record): void {
                        $record->notify(new AccountSetupInvitation('office'));

                        Notification::make()
                            ->success()
                            ->title('Setup Email Sent')
                            ->body('A setup email has been sent to the office.')
                            ->send();
                    }),
                    ActionGroup::make([
                        EditAction::make(),
                        // DeleteAction::make(),
                    ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListOfficeAccounts::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getEloquentQuery()->count();
    }
}
