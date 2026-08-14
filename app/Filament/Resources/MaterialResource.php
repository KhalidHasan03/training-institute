<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Concerns\AllowsTrainerAccess;
use App\Filament\Resources\Concerns\ScopesToTrainersBatches;
use App\Filament\Resources\MaterialResource\Pages;
use App\Models\Material;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MaterialResource extends Resource
{
    use AllowsTrainerAccess;
    use ScopesToTrainersBatches;

    protected static ?string $model = Material::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationGroup = 'Learning';

    public static function getEloquentQuery(): Builder
    {
        return self::scopeQueryToTrainerBatches(parent::getEloquentQuery());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Material')
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
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Content')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->options([
                                'document' => 'Document (PDF, DOCX)',
                                'video' => 'Video',
                                'link' => 'External Link',
                                'archive' => 'Archive (ZIP)',
                            ])
                            ->default('document')
                            ->live()
                            ->required(),
                        Forms\Components\FileUpload::make('file_path')
                            ->label('File')
                            ->directory('materials')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/zip',
                                'application/x-zip-compressed',
                                'application/vnd.rar',
                                'video/mp4',
                            ])
                            ->maxSize(10240)
                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['document', 'video', 'archive'])),
                        Forms\Components\TextInput::make('external_url')
                            ->url()
                            ->placeholder('https://...')
                            ->visible(fn (Forms\Get $get) => $get('type') === 'link'),
                        Forms\Components\Toggle::make('is_published')
                            ->label('Published')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('type')
                    ->icon(fn (string $state): string => match ($state) {
                        'document' => 'heroicon-o-document-text',
                        'video' => 'heroicon-o-video-camera',
                        'link' => 'heroicon-o-link',
                        'archive' => 'heroicon-o-archive-box',
                        default => 'heroicon-o-document',
                    }),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                Tables\Columns\TextColumn::make('batch.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'document' => 'info',
                        'video' => 'warning',
                        'archive' => 'gray',
                        default => 'success',
                    }),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('batch_id')
                    ->label('Batch')
                    ->relationship('batch', 'name'),
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'document' => 'Document',
                        'video' => 'Video',
                        'link' => 'External Link',
                        'archive' => 'Archive',
                    ]),
                Tables\Filters\TernaryFilter::make('is_published'),
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
            'index' => Pages\ListMaterials::route('/'),
            'create' => Pages\CreateMaterial::route('/create'),
            'edit' => Pages\EditMaterial::route('/{record}/edit'),
        ];
    }
}