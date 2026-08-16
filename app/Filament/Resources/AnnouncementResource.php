<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnnouncementResource\Pages;
use App\Filament\Resources\Concerns\RestrictsResourceAccess;
use App\Models\Announcement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AnnouncementResource extends Resource
{
    use RestrictsResourceAccess;

    protected static ?string $model = Announcement::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationGroup = 'Communication';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Announcement')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\RichEditor::make('content')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('audience')
                            ->options([
                                'all' => 'Everyone',
                                'students' => 'Students',
                                'trainers' => 'Trainers',
                            ])
                            ->default('all')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'published' => 'Published',
                                'draft' => 'Draft',
                            ])
                            ->default('published')
                            ->required(),
                        Forms\Components\DatePicker::make('published_at')
                            ->native(false)
                            ->displayFormat('d M Y'),

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
                Tables\Columns\TextColumn::make('audience')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'all' => 'success',
                        'students' => 'info',
                        'trainers' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('publisher.name')
                    ->label('Published By')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'published' ? 'success' : 'gray'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('audience')
                    ->options([
                        'all' => 'Everyone',
                        'students' => 'Students',
                        'trainers' => 'Trainers',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'published' => 'Published',
                        'draft' => 'Draft',
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
            ->defaultSort('published_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnnouncements::route('/'),
            'create' => Pages\CreateAnnouncement::route('/create'),
            'edit' => Pages\EditAnnouncement::route('/{record}/edit'),
        ];
    }
}
