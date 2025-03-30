<?php

namespace App\Filament\Students\Pages;

use App\Models\Students;
use App\Models\Department;
use App\Models\YearLevel;
use Filament\Pages\Page;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static string $view = 'filament.pages.students.profile';
    protected static ?int $navigationSort = 90;
    protected static ?string $title = 'My Profile';
    
    public ?array $data = [];
    
    public function mount(): void
    {
        $user = Auth::guard('students')->user();
        $this->data = $user->only([
            'avatar', 'name', 'email',
            'department_id', 'program', 'year_level_id', 'student_type'
        ]);
        $this->form->fill($this->data);
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->model(Students::class)
            ->schema([
                \Filament\Forms\Components\Tabs::make('Profile')
                    ->tabs([
                        \Filament\Forms\Components\Tabs\Tab::make('Personal Details')
                            ->icon('heroicon-m-user')
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
                                                    ->directory('avatars/students')
                                                    ->imageResizeMode('cover')
                                                    ->imageCropAspectRatio('1:1')
                                                    ->imageEditor()
                                                    ->avatar()
                                                    ->circleCropper()
                                                    ->maxSize(2048)
                                                    ->uploadingMessage('Uploading image...')
                                                    ->label('Profile Picture')
                                                    ->helperText('Upload a high-quality photo of yourself. This will be displayed on your profile.')
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
                                                    ->placeholder('Juan Dela Cruz'),
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
                            ]),
                        \Filament\Forms\Components\Tabs\Tab::make('Academic Details')
                            ->icon('heroicon-m-academic-cap')
                            ->schema([
                                \Filament\Forms\Components\Section::make('Academic Information')
                                    ->description('Update your academic details')
                                    ->schema([
                                        \Filament\Forms\Components\Select::make('department_id')
                                            ->relationship(
                                                name: 'department',
                                                titleAttribute: 'name',
                                                modifyQueryUsing: fn ($query) => $query->orderBy('name')
                                            )
                                            ->required()
                                            ->label('Department')
                                            ->helperText('Select your academic department'),
                                        \Filament\Forms\Components\TextInput::make('program')
                                            ->required()
                                            ->maxLength(255)
                                            ->label('Program')
                                            ->helperText('Enter your degree program')
                                            ->placeholder('Bachelor of Science in Information Technology'),
                                        \Filament\Forms\Components\Select::make('year_level_id')
                                            ->relationship(
                                                name: 'yearLevel',
                                                titleAttribute: 'name',
                                                modifyQueryUsing: fn ($query) => $query->orderBy('name')
                                            )
                                            ->required()
                                            ->label('Year Level')
                                            ->helperText('Select your current year level'),
                                        \Filament\Forms\Components\Select::make('student_type')
                                            ->options([
                                                'regular' => 'Regular',
                                                'irregular' => 'Irregular'
                                            ])
                                            ->required()
                                            ->label('Student Type')
                                            ->helperText('Select your student classification'),
                                    ]),
                            ]),
                    ])
                    ->persistTabInQueryString('profile-section')
                    ->id('student-profile-tabs'),
            ])
            ->statePath('data');
    }
    
    public function save(): void
    {
        $user = Auth::guard('students')->user();
        $data = $this->form->getState();
        
        $updateData = [
            'name' => $data['name'],
            'avatar' => $data['avatar'],
            'department_id' => $data['department_id'],
            'program' => $data['program'],
            'year_level_id' => $data['year_level_id'],
            'student_type' => $data['student_type'],
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
                ->title('Profile, academic information, and password updated successfully')
                ->send();

            $this->redirect('/students');
            return;
        }
        
        $user->update($updateData);
        
        Notification::make()
            ->success()
            ->title('Profile and academic information updated successfully')
            ->send();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->guard('students')->check();
    }
}