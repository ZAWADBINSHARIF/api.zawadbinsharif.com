<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExperienceResource\Pages;
use App\Models\Experience;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ExperienceResource extends Resource
{
    protected static ?string $model = Experience::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    
    protected static ?string $navigationLabel = 'Work Experience';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Experience Details')
                    ->schema([
                        Forms\Components\TextInput::make('role')
                            ->label('Job Title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('company')
                            ->label('Company Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('location')
                            ->label('Location')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., New York, NY or Remote'),
                    ])->columns(3),
                    
                Forms\Components\Section::make('Period')
                    ->schema([
                        Forms\Components\TextInput::make('period_from')
                            ->label('Start Date')
                            ->required()
                            ->placeholder('e.g., Jan 2022')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('period_to')
                            ->label('End Date')
                            ->placeholder('e.g., Dec 2023')
                            ->maxLength(255)
                            ->disabled(fn ($get): bool => $get('is_current'))
                            ->dehydrated()
                            ->default(null),
                        Forms\Components\Toggle::make('is_current')
                            ->label('Currently Working Here')
                            ->reactive()
                            ->afterStateUpdated(fn ($set) => $set('period_to', null)),
                    ])->columns(3),
                    
                Forms\Components\Section::make('Description')
                    ->schema([
                        Forms\Components\Repeater::make('description')
                            ->label('Job Responsibilities & Achievements')
                            ->schema([
                                Forms\Components\TextInput::make('item')
                                    ->label('Achievement/Responsibility')
                                    ->required()
                                    ->placeholder('e.g., Led development of microservices architecture')
                            ])
                            ->defaultItems(1)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['item'] ?? null)
                            ->addActionLabel('Add Achievement'),
                    ]),
                    
                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Show on Portfolio')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('role')
                    ->label('Job Title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location')
                    ->searchable(),
                Tables\Columns\TextColumn::make('period')
                    ->label('Period')
                    ->getStateUsing(fn (Experience $record): string => 
                        $record->period_from . ' - ' . ($record->is_current ? 'Present' : $record->period_to)
                    )
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_current')
                    ->label('Current')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order', 'asc')
            ->reorderable('sort_order')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListExperiences::route('/'),
            'create' => Pages\CreateExperience::route('/create'),
            'edit' => Pages\EditExperience::route('/{record}/edit'),
        ];
    }
}
