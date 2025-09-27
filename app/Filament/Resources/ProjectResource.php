<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Project Information')
                    ->description('Basic information about the project')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Project Name'),

                        Textarea::make('introduction')
                            ->required()
                            ->maxLength(255)
                            ->rows(3)
                            ->placeholder('Brief project description'),
                    ])
                    ->columns(1),

                Section::make('Media')
                    ->description('Upload project thumbnail image')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        FileUpload::make('image')
                            ->required()
                            ->image()
                            ->directory('projects')
                            ->imageEditor()
                            ->maxSize(5120)
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->imageResizeTargetWidth(1920)
                            ->imageResizeTargetHeight(1080)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif']),
                    ]),

                Section::make('Technologies')
                    ->description('Technologies and tools used in this project')
                    ->icon('heroicon-o-code-bracket')
                    ->schema([
                        TagsInput::make('technology')
                            ->required()
                            ->placeholder('Add technologies used')
                            ->suggestions([
                                'Laravel',
                                'React',
                                'Vue.js',
                                'Next.js',
                                'TypeScript',
                                'JavaScript',
                                'PHP',
                                'Python',
                                'Node.js',
                                'Tailwind CSS',
                                'MySQL',
                                'PostgreSQL',
                                'MongoDB',
                                'Redis',
                                'Docker',
                                'AWS',
                                'Git',
                            ]),
                    ]),

                Section::make('External Links')
                    ->description('Repository and live demo links')
                    ->icon('heroicon-o-link')
                    ->schema([
                        TextInput::make('github_link')
                            ->url()
                            ->placeholder('github.com/username/repository')
                            ->maxLength(255),

                        TextInput::make('view_link')
                            ->url()
                            ->placeholder('project-demo.com')
                            ->maxLength(255),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->square()
                    ->size(60),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('introduction')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= 50) {
                            return null;
                        }

                        return $state;
                    }),

                TextColumn::make('technology')
                    ->badge()
                    ->separator(',')
                    ->limitList(3)
                    ->listWithLineBreaks()
                    ->bulleted(false)
                    ->expandableLimitedList(),

                TextColumn::make('github_link')
                    ->label('GitHub')
                    ->url(fn($record) => $record->github_link, true)
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->iconPosition('after')
                    ->placeholder('N/A'),

                TextColumn::make('view_link')
                    ->label('Live Demo')
                    ->url(fn($record) => $record->view_link, true)
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->iconPosition('after')
                    ->placeholder('N/A'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
