<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Concerns\AllowsTrainerAccess;
use App\Filament\Resources\Concerns\ScopesToTrainersBatches;
use App\Filament\Resources\ExamResource\Pages;
use App\Models\Exam;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ExamResource extends Resource
{
    use AllowsTrainerAccess;
    use ScopesToTrainersBatches;

    protected static ?string $model = Exam::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Assessments';

    public static function getEloquentQuery(): Builder
    {
        return self::scopeQueryToTrainerBatches(parent::getEloquentQuery());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Exam')
                    ->schema([
                        Forms\Components\Select::make('batch_id')
                            ->label('Batch')
                            ->relationship('batch', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('exam_date')
                            ->required(),
                        Forms\Components\TextInput::make('total_marks')
                            ->numeric()
                            ->minValue(1)
                            ->default(100)
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                Tables\Columns\TextColumn::make('batch.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('exam_date')
                    ->date()
                    ->sortable()
                    ->color(fn (Exam $record): string => $record->exam_date->isPast() ? 'gray' : 'warning'),
                Tables\Columns\TextColumn::make('total_marks')
                    ->sortable(),
                Tables\Columns\TextColumn::make('results_count')
                    ->label('Results')
                    ->counts('results')
                    ->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('batch_id')
                    ->label('Batch')
                    ->relationship('batch', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('exam_date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExams::route('/'),
            'create' => Pages\CreateExam::route('/create'),
            'edit' => Pages\EditExam::route('/{record}/edit'),
        ];
    }
}