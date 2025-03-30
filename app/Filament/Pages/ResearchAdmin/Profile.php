<?php

namespace App\Filament\Pages\ResearchAdmin;

use Filament\Pages\Page;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Support\Facades\FilamentFacade;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;

    // protected static ?string $navigationIcon = '';
    protected static string $view = 'filament.pages.research-admin.profile';
    protected static ?string $navigationGroup = 'My Account';
    protected static ?int $navigationSort = 3;
    protected static ?string $title = 'Profile';
    
    public ?array $data = [];
    
    public function mount(): void
    {
        $user = Auth::user();
        $this->data = $user->only(['avatar', 'name', 'email']);
        $this->form->fill($this->data);
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Grid::make()
                    ->columns(2)
                    ->schema([
                        \Filament\Forms\Components\Section::make('Profile Picture')
                            ->description('Upload a professional photo for your profile')
                            ->columnSpan(1)
                            ->schema([
                                FileUpload::make('avatar')
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
                        \Filament\Forms\Components\Section::make('Account Information')
                            ->description('Manage your personal details and security')
                            ->columnSpan(1)
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Full Name')
                                    ->helperText('Enter your complete name as it should appear on your profile')
                                    ->placeholder('Enter your full name'),
                                \Filament\Forms\Components\TextInput::make('new_password')
                                    ->password()
                                    ->revealable()
                                    ->label('New Password')
                                    ->minLength(8)
                                    ->helperText('Must be at least 8 characters long. Use a mix of letters, numbers, and symbols for better security.')
                                    ->placeholder('••••••••')
                                    ->confirmed(),
                                \Filament\Forms\Components\TextInput::make('new_password_confirmation')
                                    ->password()
                                    ->revealable()
                                    ->label('Confirm New Password')
                                    ->helperText('Re-enter your new password to confirm')
                                    ->placeholder('••••••••'),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }
    
    protected ?bool $wasPasswordChanged = false;
    
    public function save(): void
    {
        $user = Auth::user();
        $data = $this->form->getState();
        
        $updateData = [
            'name' => $data['name'],
            'avatar' => $data['avatar'],
        ];
        
        if (!empty($data['new_password'])) {
            $updateData['password'] = \Hash::make($data['new_password']);
            $user->update($updateData);
            
            // Update the password hash in session to prevent logout
            session()->put([
                'password_hash_web' => $user->password
            ]);
            
            $this->form->fill([
                'new_password' => '',
                'new_password_confirmation' => ''
            ]);
            
            Notification::make()
                ->success()
                ->title('Profile and password updated successfully')
                ->send();

            $this->redirect('/research-admin');
            return;
        }
        
        $user->update($updateData);
        
        Notification::make()
            ->success()
            ->title('Profile updated successfully')
            ->send();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->role === 'research-admin';
    }
}