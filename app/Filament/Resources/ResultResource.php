<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Concerns\AllowsTrainerAccess;
use App\Filament\Resources\ResultResource\Pages;
use App\Models\Result;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ResultResource extends Resource
{
    use AllowsTrainerAccess;

    protected static ?string $model = Result::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Assessments';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = auth()->user();
        if ($user && $user->isTrainer() && $user->trainer) {
            $query->whereHas('exam.batch', fn (Builder $q) => $q->where('trainer_id', $user->trainer->id));
        }

        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Result')
                    ->schema([
                        Forms\Components\Select::make('exam_id')
                            ->label('Exam')
                            ->relationship('exam', 'title')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('student_id', null)),
                        Forms\Components\Select::make('student_id')
                            ->label('Student')
                            ->options(function (Forms\Get $get) {
                                $exam = $get('exam_id') ? \App\Models\Exam::with('batch.enrollments.student')->find($get('exam_id')) : null;
                                if (! $exam) {
                                    return [];
                                }
                                return $exam->batch->enrollments
                                    ->where('status', 'active')
                                    ->mapWithKeys(fn ($en) => [$en->student_id => $en->student?->name]);
                            })
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('marks')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->live(),
                        Forms\Components\Placeholder::make('grade_preview')
                            ->label('Grade')
                            ->content(function (Forms\Get $get) {
                                $marks = (float) $get('marks');
                                $total = (float) \App\Models\Exam::find($get('exam_id'))?->total_marks ?? 0;
                                $pct = $total > 0 ? ($marks / $total) * 100 : 0;
                                return $marks !== 0.0 ? \App\Services\ResultService::gradeForPercentage($pct) : '—';
                            })
                            ->dehydrated(false)
                            ->hidden(fn (Forms\Get $get) => ! $get('exam_id')),
                        Forms\Components\TextInput::make('remarks')
                            ->maxLength(255),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('exam.title')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('exam.batch.name')
                    ->label('Batch')
                    ->sortable(),
                Tables\Columns\TextColumn::make('student.student_id')
                    ->label('Student ID')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('student.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('marks')
                    ->label('Marks / Total')
                    ->state(fn (Result $record) => "{$record->marks} / {$record->exam->total_marks}"),
                Tables\Columns\TextColumn::make('percentage')
                    ->label('Percent')
                    ->state(fn (Result $record) => "{$record->percentage}%"),
                Tables\Columns\TextColumn::make('grade')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'A+', 'A', 'B' => 'success',
                        'C', 'D' => 'warning',
                        default => 'danger',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('exam_id')
                    ->label('Exam')
                    ->relationship('exam', 'title'),
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
            'index' => Pages\ListResults::route('/'),
            'create' => Pages\CreateResult::route('/create'),
            'edit' => Pages\EditResult::route('/{record}/edit'),
        ];
    }
}