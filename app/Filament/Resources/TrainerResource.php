<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Concerns\RestrictsResourceAccess;
use App\Filament\Resources\TrainerResource\Pages;
use App\Models\Trainer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TrainerResource extends Resource
{
    use RestrictsResourceAccess;

    protected static ?string $model = Trainer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Academics';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Profile')
                    ->schema([
                        Forms\Components\FileUpload::make('photo')
                            ->image()
                            ->imageEditor()
                            ->avatar()
                            ->directory('trainers')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(30),
                        Forms\Components\TextInput::make('expertise')
                            ->maxLength(255)
                            ->placeholder('e.g. Laravel, UI/UX, Data Science'),
                        Forms\Components\Textarea::make('bio')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Enrollment & Status')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->label('Login password')
                            ->password()
                            ->revealable()
                            ->dehydrated(false)
                            ->required(fn (string $operation) => $operation === 'create')
                            ->helperText(fn (string $operation) => $operation === 'create'
                                ? 'Choose a password — a login account will be created for this trainer.'
                                : 'Password cannot be changed here.'),
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
                Tables\Columns\ImageColumn::make('photo')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('expertise')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('batches_count')
                    ->label('Batches')
                    ->counts('batches')
                    ->badge(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'active' ? 'success' : 'danger'),
            ])
            ->filters([
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
            'index' => Pages\ListTrainers::route('/'),
            'create' => Pages\CreateTrainer::route('/create'),
            'edit' => Pages\EditTrainer::route('/{record}/edit'),
        ];
    }
}
