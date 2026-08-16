<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Concerns\RestrictsResourceAccess;
use App\Filament\Resources\CourseResource\Pages;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CourseResource extends Resource
{
    use RestrictsResourceAccess;

    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = 'Academics';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Course Details')
                    ->schema([
                        Forms\Components\FileUpload::make('thumbnail')
                            ->image()
                            ->directory('courses')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $state, string $operation) {
                                if ($operation === 'edit' && filled($get('slug'))) {
                                    return;
                                }
                                $set('slug', Str::slug($state));
                            }),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('short_description')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('description')
                            ->columnSpanFull(),
                        Forms\Components\Repeater::make('highlights')
                            ->label('What you will learn')
                            ->simple(
                                Forms\Components\TextInput::make('item')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g. Build a real-world portfolio project')
                            )
                            ->columnSpanFull()
                            ->collapsible(),
                        Forms\Components\TextInput::make('duration')
                            ->placeholder('e.g. 3 months')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('fee')
                            ->numeric()
                            ->prefix('৳')
                            ->required()
                            ->default(0),
                        Forms\Components\Select::make('level')
                            ->options([
                                'beginner' => 'Beginner',
                                'intermediate' => 'Intermediate',
                                'advanced' => 'Advanced',
                            ])
                            ->default('beginner')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ])
                            ->default('active')
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->square()
                    ->height(48),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('level')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'beginner' => 'success',
                        'intermediate' => 'warning',
                        'advanced' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('duration')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('fee')
                    ->money('BDT')
                    ->sortable(),
                Tables\Columns\TextColumn::make('active_batches_count')
                    ->label('Batches')
                    ->counts('activeBatches')
                    ->badge(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'active' ? 'success' : 'danger'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('level')
                    ->options([
                        'beginner' => 'Beginner',
                        'intermediate' => 'Intermediate',
                        'advanced' => 'Advanced',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
