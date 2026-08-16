<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssignmentResource\Pages;
use App\Filament\Resources\Concerns\RestrictsResourceAccess;
use App\Models\Assignment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AssignmentResource extends Resource
{
    use RestrictsResourceAccess;

    protected static ?string $model = Assignment::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $navigationGroup = 'Learning';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Assignment')
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
                        Forms\Components\Textarea::make('description')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Details')
                    ->schema([
                        Forms\Components\FileUpload::make('attachment')
                            ->directory('assignments')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/zip',
                                'image/png',
                                'image/jpeg',
                            ])
                            ->maxSize(10240),
                        Forms\Components\TextInput::make('total_marks')
                            ->numeric()
                            ->minValue(1)
                            ->default(100)
                            ->required(),
                        Forms\Components\DateTimePicker::make('deadline')
                            ->seconds(false),
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'closed' => 'Closed',
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
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                Tables\Columns\TextColumn::make('batch.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_marks')
                    ->sortable(),
                Tables\Columns\TextColumn::make('deadline')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->color(fn (Assignment $record): string => $record->is_overdue ? 'danger' : 'gray'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'active' ? 'success' : 'gray'),
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('batch_id')
                    ->label('Batch')
                    ->relationship('batch', 'name'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'closed' => 'Closed',
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
            'index' => Pages\ListAssignments::route('/'),
            'create' => Pages\CreateAssignment::route('/create'),
            'edit' => Pages\EditAssignment::route('/{record}/edit'),
        ];
    }
}
