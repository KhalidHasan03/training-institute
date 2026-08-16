<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Concerns\RestrictsResourceAccess;
use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Enrollment;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    use RestrictsResourceAccess;

    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Finance';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Payment')
                    ->schema([
                        Forms\Components\Select::make('student_id')
                            ->label('Student')
                            ->relationship('student', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Forms\Set $set) => $set('enrollment_id', null)),
                        Forms\Components\Select::make('enrollment_id')
                            ->label('Enrollment')
                            ->options(function (Forms\Get $get) {
                                if (! $get('student_id')) {
                                    return [];
                                }

                                return Enrollment::where('student_id', $get('student_id'))
                                    ->with('batch.course')
                                    ->get()
                                    ->mapWithKeys(fn (Enrollment $en) => [
                                        $en->id => $en->batch?->name.' — '.$en->batch?->course?->title,
                                    ]);
                            })
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('amount')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->prefix('৳'),
                        Forms\Components\DatePicker::make('payment_date')
                            ->default(today())
                            ->required(),
                        Forms\Components\Select::make('payment_method')
                            ->options(array_combine(Payment::METHODS, Payment::METHODS))
                            ->required(),
                        Forms\Components\TextInput::make('transaction_id')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('notes')
                            ->maxLength(255),
                        Forms\Components\Select::make('status')
                            ->options([
                                'completed' => 'Completed',
                                'pending' => 'Pending',
                                'failed' => 'Failed',
                            ])
                            ->default('completed')
                            ->required(),
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
                Tables\Columns\TextColumn::make('enrollment.batch.name')
                    ->label('Batch')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('BDT')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('transaction_id')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'pending' => 'warning',
                        default => 'danger',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_method')
                    ->options(array_combine(Payment::METHODS, Payment::METHODS)),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'completed' => 'Completed',
                        'pending' => 'Pending',
                        'failed' => 'Failed',
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
            ])
            ->defaultSort('payment_date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
