<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Enums\StoragePath;
use App\Models\Profile as ProfileModel;
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
    public array $data = [];


    public function mount(): void
    {

        $this->record = ProfileModel::first() ?? new ProfileModel();
        $this->data = $this->record->toArray();
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $this->callHook('beforeFill');

        if ($this->record->exists)
            $this->form->fill($this->record->attributesToArray());
        else
            $this->form->fill();

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
                            ->label('GitHub URL')
                            ->prefix('https://'),
                        TextInput::make("linkedin")
                            ->url()
                            ->label('LinkedIn URL')
                            ->prefix('https://'),
                        TextInput::make("twitter")
                            ->url()
                            ->label('Twitter/X URL')
                            ->prefix('https://'),
                    ])->columnSpan(1),
            ])->columns(3);
    }


    public function save(): void
    {
        $validatedData = $this->form->getState();

        $this->record->fill($validatedData);
        $this->record->save();

        Notification::make()
            ->title("Profile Saved successfully")
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
