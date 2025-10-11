<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Enums\StoragePath;
use App\Models\Profile as ProfileModel;
use App\Models\Contact;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;


class Profile extends Page implements HasForms
{

    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $model = ProfileModel::class;

    protected static string $view = 'filament.pages.profile';

    public ?ProfileModel $record = null;
    public ?Contact $contact = null;
    public array $data = [];


    public function mount(): void
    {
        $this->record = ProfileModel::first() ?? new ProfileModel();
        $this->contact = Contact::first() ?? new Contact();

        $profileData = $this->record->toArray();
        $contactData = $this->contact->toArray();

        // Merge the data, with contact data taking precedence for overlapping fields
        $this->data = array_merge($profileData, $contactData);
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $this->callHook('beforeFill');

        if ($this->record->exists || $this->contact->exists) {
            $profileData = $this->record->exists ? $this->record->attributesToArray() : [];
            $contactData = $this->contact->exists ? $this->contact->attributesToArray() : [];

            // Merge data with contact taking precedence for overlapping fields
            $mergedData = array_merge($profileData, $contactData);
            $this->form->fill($mergedData);
        } else {
            $this->form->fill();
        }

        $this->callHook('afterFill');
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Section::make('Basic Information')
                    ->schema([
                        TextInput::make("full_name")->required(),
                        TextInput::make("short_title")->required(),
                        RichEditor::make("introduction")->required(),
                        RichEditor::make("about_me")->required(),
                    ])->columnSpan(2),
                Section::make('Files & Skills')
                    ->schema([
                        FileUpload::make("image")
                            ->image()
                            ->previewable()
                            ->rules([
                                'mimetypes:image/jpeg,image/png',
                                'max:512'
                            ])
                            ->maxSize(512)
                            ->imageEditor()
                            ->acceptedFileTypes(['image/jpeg', 'image/png'])
                            ->disk('public')
                            ->directory(StoragePath::PROFILE_IMAGE->value)
                            ->required(),
                        FileUpload::make("resume")
                            ->rules([
                                'mimetypes:application/pdf',
                                'max:10240'
                            ])
                            ->maxSize(10240)
                            ->acceptedFileTypes(['application/pdf'])
                            ->disk('public')
                            ->directory(StoragePath::RESUME->value)->required(),
                        TagsInput::make("worked_technologies")->required(),
                    ])->columnSpan(1),
                Section::make('Contact Information')
                    ->schema([
                        TextInput::make("email")
                            ->email()
                            ->label('Email Address'),
                        TextInput::make("phone")
                            ->tel()
                            ->label('Phone Number'),
                        TextInput::make("location")
                            ->label('Location'),
                        TagsInput::make("availability")
                            ->label('Available For')
                            ->placeholder('Add services you offer')
                            ->helperText('Services you are available for (e.g., Fullstack Development, Consulting)'),
                    ])->columnSpan(2),
                Section::make('Social Links')
                    ->schema([
                        TextInput::make("github")
                            ->url()
                            ->label('GitHub URL'),
                        TextInput::make("linkedin")
                            ->url()
                            ->label('LinkedIn URL'),
                        TextInput::make("twitter")
                            ->url()
                            ->label('Twitter/X URL'),
                        TextInput::make("facebook")
                            ->url()
                            ->label('Facebook URL'),
                        // TextInput::make("instagram")
                        //     ->url()
                        //     ->label('Instagram URL'),
                        TextInput::make("youtube")
                            ->url()
                            ->label('YouTube URL'),
                    ])->columnSpan(1),
            ])->columns(3);
    }


    public function save(): void
    {
        $validatedData = $this->form->getState();

        // Fields that belong to Profile model
        $profileFields = [
            'full_name',
            'short_title',
            'introduction',
            'about_me',
            'image',
            'resume',
            'worked_technologies',
            'availability'
        ];

        // Fields that belong to both models (will be saved in both)
        $sharedFields = [
            'email',
            'phone',
            'location',
            'github',
            'linkedin',
            'twitter'
        ];

        // Fields that belong only to Contact model
        $contactOnlyFields = ['facebook', 'instagram', 'youtube'];

        // Prepare Profile data
        $profileData = array_intersect_key(
            $validatedData,
            array_flip(array_merge($profileFields, $sharedFields))
        );

        // Prepare Contact data
        $contactData = array_intersect_key(
            $validatedData,
            array_flip(array_merge($sharedFields, $contactOnlyFields))
        );

        // Save Profile
        $this->record->fill($profileData);
        $this->record->save();

        // Save Contact
        $this->contact->fill($contactData);
        $this->contact->save();

        Notification::make()
            ->title("Profile and Contact information saved successfully")
            ->success()
            ->send();
    }


    public function getFormActions(): array
    {
        return [
            Action::make("save")
                ->label(_("Save"))
                ->action('save')
        ];
    }
}
