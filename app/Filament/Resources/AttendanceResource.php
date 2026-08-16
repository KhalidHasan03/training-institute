<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\Concerns\RestrictsResourceAccess;
use App\Models\Attendance;
use App\Models\ClassSession;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AttendanceResource extends Resource
{
    use RestrictsResourceAccess;

    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Learning';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Attendance Record')
                    ->schema([
                        Forms\Components\Select::make('batch_id')
                            ->label('Batch')
                            ->relationship('batch', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Forms\Set $set) => $set('class_session_id', null)),
                        Forms\Components\Select::make('class_session_id')
                            ->label('Class Session')
                            ->options(function (Forms\Get $get) {
                                if (! $get('batch_id')) {
                                    return [];
                                }

                                return ClassSession::where('batch_id', $get('batch_id'))
                                    ->orderByDesc('date')
                                    ->get()
                                    ->mapWithKeys(fn (ClassSession $s) => [
                                        $s->id => $s->date->format('d M Y').' — '.($s->topic ?: 'Untitled'),
                                    ]);
                            })
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $state) {
                                $session = $state ? ClassSession::find($state) : null;
                                if ($session) {
                                    $set('date', $session->date->toDateString());
                                }
                            }),
                        Forms\Components\Select::make('student_id')
                            ->label('Student')
                            ->relationship('student', 'name', fn (Builder $q) => $q->where('status', 'active'))
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\DatePicker::make('date')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'present' => 'Present',
                                'absent' => 'Absent',
                                'late' => 'Late',
                            ])
                            ->default('present')
                            ->required(),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('student.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('student.student_id')
                    ->label('Student ID')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('batch.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('class_session.topic')
                    ->label('Session')
                    ->limit(30),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'present' => 'success',
                        'late' => 'warning',
                        default => 'danger',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('batch_id')
                    ->label('Batch')
                    ->relationship('batch', 'name'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'present' => 'Present',
                        'absent' => 'Absent',
                        'late' => 'Late',
                    ]),
                Tables\Filters\SelectFilter::make('date')
                    ->label('When')
                    ->options([
                        'today' => 'Today',
                        'upcoming' => 'Upcoming',
                        'past' => 'Past',
                    ])
                    ->query(fn (Builder $query, array $data) => match ($data['value'] ?? null) {
                        'today' => $query->whereDate('date', today()),
                        'upcoming' => $query->where('date', '>=', today()),
                        'past' => $query->where('date', '<', today()),
                        default => $query,
                    }),
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
            ->defaultSort('date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
