<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Enums\StoragePath;
use App\Filament\Resources\ProfileResource\Pages;
use App\Filament\Resources\ProfileResource\RelationManagers;
use App\Models\Profile as ProfileModel;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


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
                Section::make()
                    ->schema([
                        TextInput::make("full_name"),
                        TextInput::make("short_title"),
                        RichEditor::make("introduction"),
                        RichEditor::make("about_me"),
                    ])->columnSpan(2),
                Section::make()
                    ->schema([
                        FileUpload::make("image")
                            ->image()
                            ->rules([
                                'mimetypes:image/jpeg,image/png',
                                'max:512'
                            ])
                            ->maxSize(512)
                            ->acceptedFileTypes(['image/jpeg', 'image/png'])
                            ->disk('public')
                            ->directory(StoragePath::PROFILE_IMAGE->value),
                        FileUpload::make("resume")
                            ->rules([
                                'mimetypes:application/pdf',
                                'max:10240'
                            ])
                            ->maxSize(10240)
                            ->acceptedFileTypes(['application/pdf'])
                            ->disk('public')
                            ->directory(StoragePath::RESUME->value),
                        TagsInput::make("worked_technologies"),
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
