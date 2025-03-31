<?php

namespace App\Filament\Department\Resources;

use App\Filament\Department\Resources\FacultyAccountsResource\Pages;
use App\Filament\Department\Resources\FacultyAccountsResource\RelationManagers;
use App\Models\Departments;
use App\Models\User;
use App\Models\EvaluationPeriod;
use App\Notifications\AccountSetupInvitation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class FacultyAccountsResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $modelLabel = 'Faculty Account';

    protected static ?string $navigationLabel = 'Faculty Accounts';

    protected static ?string $navigationGroup = 'Faculty Management';

    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('role', 'faculty')
            ->where('department_id', auth()->user()->department_id)
            ->with(['facultyCourses' => function($query) {
                $query->with(['course.yearLevel', 'evaluationPeriod'])
                    ->whereHas('evaluationPeriod', function($q) {
                        $q->where('status', 'Active');
                    });
            }]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\Section::make('Personal Information')
                            ->description('Basic faculty member details')
                            ->columnSpan(1)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Full Name')
                                    ->required()
                                    ->maxLength(255)
                                    ->helperText('Enter the complete name of the faculty member'),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->unique(ignoreRecord: true)
                                    ->required()
                                    ->maxLength(255)
                                    ->helperText('This email will be used for account setup'),
                                Forms\Components\Radio::make('gender')
                                    ->label('Gender')
                                    ->options([
                                        'Male' => 'Male',
                                        'Female' => 'Female',
                                    ])
                                    ->inline()
                                    ->required()
                                    ->helperText('Select the faculty member\'s gender'),
                            ]),
                        Forms\Components\Section::make('Profile Picture')
                            ->description('Upload a professional photo')
                            ->columnSpan(1)
                            ->schema([
                                Forms\Components\FileUpload::make('avatar')
                                    ->label('')
                                    ->image()
                                    ->disk('public')
                                    ->directory('avatars/faculty')
                                    ->imageResizeMode('contain')
                                    ->imageCropAspectRatio('1:1')
                                    ->imageEditor()
                                    ->avatar()
                                    ->circleCropper()
                                    ->maxSize(2048)
                                    ->uploadingMessage('Uploading image...')
                                    ->helperText('Upload an image, max 2MB. Will be resized to 300x300.')
                                    ->alignCenter(),
                            ]),
                    ]),
                Forms\Components\Hidden::make('role')
                    ->default('faculty'),
                Forms\Components\Hidden::make('is_active')
                    ->default(false),
                Forms\Components\Hidden::make('password')
                    ->default(null),
                Forms\Components\Hidden::make('department_id')
                    ->default(fn() => auth()->user()->department_id),
            ]);
    }

    protected function getCreatedNotification(): ?Notification
    {
        return new AccountSetupInvitation('faculty');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->circular()
                    ->disk('public')
                    ->defaultImageUrl(asset('images/default_pfp.svg')),
                Tables\Columns\TextColumn::make('name')
                    ->label('Account Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                    ->label('Gender')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->label('Department'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

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
                            ->modalHeading('Deactivate Faculty Account')
                            ->modalDescription('Are you sure you want to deactivate this faculty account? This will reset the password and require a new setup email.')
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
                                    ->body('Faculty account has been deactivated')
                                    ->send();
                            })
                    ),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M j, Y : g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->actions([
                Tables\Actions\Action::make('sendSetupEmail')
                ->icon('heroicon-o-envelope')
                ->requiresConfirmation()
                ->action(function (User $record) {
                    $record->notify(new AccountSetupInvitation('faculty'));
                    
                    Notification::make()
                        ->title('Setup email sent successfully')
                        ->success()
                        ->send();
                })
                ->visible(fn (User $record): bool => !$record->password || !$record->is_active),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    // Tables\Actions\DeleteAction::make(),
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
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFacultyAccounts::route('/'),
            'create' => Pages\CreateFacultyAccounts::route('/create'),
            'edit' => Pages\EditFacultyAccounts::route('/{record}/edit'),
        ];
    }
}
