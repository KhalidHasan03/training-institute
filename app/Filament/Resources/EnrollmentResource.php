<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Concerns\RestrictsResourceAccess;
use App\Filament\Resources\EnrollmentResource\Pages;
use App\Models\Batch;
use App\Models\Enrollment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EnrollmentResource extends Resource
{
    use RestrictsResourceAccess;

    protected static ?string $model = Enrollment::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Learning';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Enrollment')
                    ->schema([
                        Forms\Components\Select::make('student_id')
                            ->label('Student')
                            ->relationship('student', 'name', fn (Builder $q) => $q->where('status', 'active'))
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('batch_id')
                            ->label('Batch')
                            ->relationship('batch', 'name', fn (Builder $q) => $q->where('status', 'active'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->afterStateUpdated(function (Forms\Set $set, ?string $state) {
                                if ($state) {
                                    $batch = Batch::with('course')->find($state);
                                    $set('course_fee', $batch?->course?->fee ?? 0);
                                    $set('final_fee', $batch?->course?->fee ?? 0);
                                }
                            }),
                        Forms\Components\DatePicker::make('enrollment_date')
                            ->default(today()),
                    ])
                    ->columns(3),
                Forms\Components\Section::make('Fees')
                    ->schema([
                        Forms\Components\TextInput::make('course_fee')
                            ->numeric()
                            ->prefix('৳')
                            ->live()
                            ->required(),
                        Forms\Components\TextInput::make('discount')
                            ->numeric()
                            ->prefix('৳')
                            ->default(0)
                            ->live()
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                $set('final_fee', max(0, (float) $get('course_fee') - (float) $get('discount')));
                            }),
                        Forms\Components\TextInput::make('final_fee')
                            ->numeric()
                            ->prefix('৳')
                            ->dehydrated(true)
                            ->readOnly()
                            ->required(),
                    ])
                    ->columns(3),
                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Select::make('payment_status')
                            ->options([
                                'unpaid' => 'Unpaid',
                                'partial' => 'Partial',
                                'paid' => 'Paid',
                            ])
                            ->default('unpaid'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'completed' => 'Completed',
                            ])
                            ->default('active'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.student_id')
                    ->label('Student ID')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('student.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('batch.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('batch.course.title')
                    ->label('Course')
                    ->toggleable()
                    ->limit(25),
                Tables\Columns\TextColumn::make('enrollment_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('final_fee')
                    ->label('Fee')
                    ->money('BDT')
                    ->sortable(),
                Tables\Columns\TextColumn::make('paid')
                    ->label('Paid')
                    ->money('BDT')
                    ->getStateUsing(fn (Enrollment $record) => $record->paid),
                Tables\Columns\TextColumn::make('due')
                    ->label('Due')
                    ->money('BDT')
                    ->color(fn (Enrollment $record): string => $record->due > 0 ? 'danger' : 'success')
                    ->getStateUsing(fn (Enrollment $record) => $record->due),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'partial' => 'warning',
                        default => 'danger',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'active' ? 'success' : 'gray'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('batch_id')
                    ->label('Batch')
                    ->relationship('batch', 'name'),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'unpaid' => 'Unpaid',
                        'partial' => 'Partial',
                        'paid' => 'Paid',
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
            'index' => Pages\ListEnrollments::route('/'),
            'create' => Pages\CreateEnrollment::route('/create'),
            'edit' => Pages\EditEnrollment::route('/{record}/edit'),
        ];
    }
}
