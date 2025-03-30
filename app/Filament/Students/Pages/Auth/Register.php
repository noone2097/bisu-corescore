<?php

namespace App\Filament\Students\Pages\Auth;

use App\Models\Departments;
use App\Models\Students;
use App\Models\YearLevel;
use App\Models\Course;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use Filament\Actions\Action;

class Register extends BaseRegister
{
    protected static string $view = 'filament.pages.auth.register';

    public function getMaxWidth(): ?string
    {
        return '2xl';
    }

    public function mount(): void
    {
        parent::mount();
        
        // Pre-fill form with Google data if available
        if ($registrationData = session('registration_data')) {
            $this->form->fill([
                'name' => $registrationData['name'],
                'email' => $registrationData['email'],
            ]);
            session()->forget('registration_data');
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                    ])
                    ->schema([
                        // First Row [student id, name]
                        TextInput::make('studentID')
                            ->label('Student ID')
                            ->required()
                            ->unique('students', 'studentID')
                            ->mask('999999')
                            ->maxLength(6)
                            ->regex('/^[0-9]+$/')
                            ->extraInputAttributes([
                                'inputmode' => 'numeric',
                                'pattern' => '[0-9]*',
                                'oninput' => "this.value = this.value.replace(/[^0-9]/g, '')",
                            ])
                            ->columnSpan(['default' => 2, 'md' => 1]),
                        
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(['default' => 2, 'md' => 1]),
                            
                        // Second Row [gender, department]
                        Select::make('gender')
                            ->label('Gender')
                            ->options(['male' => 'Male', 'female' => 'Female'])
                            ->required()
                            ->columnSpan(['default' => 2, 'md' => 1]),

                        Select::make('department_id')
                            ->label('Department')
                            ->options(Departments::pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->native(true)
                            ->columnSpan(['default' => 2, 'md' => 1]),
 
                        // Third Row [program, year level]
                        TextInput::make('program')
                            ->label('Program')
                            ->helperText('e.g. BS in Computer Science')
                            ->required()
                            ->columnSpan(['default' => 2, 'md' => 1]),

                        Select::make('year_level_id')
                            ->label('Year Level')
                            ->options(YearLevel::pluck('name', 'id'))
                            ->required()
                            ->native(true)
                            ->columnSpan(['default' => 2, 'md' => 1]),
                            
                            // Fourth Row [email - student_type]
                        Select::make('student_type')
                            ->label('Student Type')
                            ->options(['regular' => 'Regular', 'irregular' => 'Irregular'])
                            ->required()
                            ->columnSpan(['default' => 2, 'md' => 1]),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique('students', 'email')
                            ->placeholder('student@bisu.edu.ph')
                            ->helperText('Use official student BISU email')
                            ->disabled(fn () => session()->has('registration_data'))
                            ->columnSpan(['default' => 2, 'md' => 1]),
                            
                        // Fifth Row [password, confirm password]
                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->required()
                            ->minLength(8)
                            ->same('passwordConfirmation')
                            ->columnSpan(['default' => 2, 'md' => 1]),
                        TextInput::make('passwordConfirmation')
                            ->password()
                            ->revealable()
                            ->label('Confirm Password')
                            ->required()
                            ->minLength(8)
                            ->columnSpan(['default' => 2, 'md' => 1]),
                    ]),
            ]);
    }

    protected function getFormAttributes(): array
    {
        return [
            'role' => 'student',
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('register')
                ->label('Register')
                ->submit('register'),
        ];
    }

    protected function hasFullWidthFormActions(): bool
    {
        return true;
    }

    public function register(): ?RegistrationResponse
    {
        $data = $this->form->getState();
        
        // Format email to include @bisu.edu.ph if not already included
        if (!str_ends_with($data['email'], '@bisu.edu.ph')) {
            $data['email'] = $data['email'] . '@bisu.edu.ph';
        }

        // Get Google data from session if exists
        $googleData = session('google_data', []);

        $student = Students::create([
            'studentID' => $data['studentID'],
            'name' => $data['name'],
            'gender' => $data['gender'],
            'email' => $data['email'],
            'department_id' => $data['department_id'],
            'year_level_id' => $data['year_level_id'],
            'program' => $data['program'],
            'password' => $data['password'],
            'is_active' => true,
            'provider' => $googleData['provider'] ?? null,
            'provider_id' => $googleData['provider_id'] ?? null,
            'provider_token' => $googleData['provider_token'] ?? null,
            'provider_refresh_token' => $googleData['provider_refresh_token'] ?? null,
        ]);

        // Clear the Google data from session after use
        session()->forget('google_data');

        event(new Registered($student));

        Auth::guard('students')->login($student);

        return app(RegistrationResponse::class);
    }

    public function getTitle(): string
    {
        return '';
    }

    public function getHeading(): string
    {
        return '';
    }

    protected static function getAuthGuard(): string
    {
        return 'students';
    }
}