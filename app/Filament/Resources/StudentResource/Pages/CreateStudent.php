<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Models\User;
use App\Services\StudentIdService;
use Filament\Resources\Pages\CreateRecord;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['student_id'] = app(StudentIdService::class)->generate();

        return $data;
    }

    protected function afterCreate(): void
    {
        $data = $this->form->getState();

        if (filled($data['password'] ?? null) && ! User::where('email', $data['email'])->exists()) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => $data['password'],
                'status' => $data['status'] ?? 'active',
            ]);
            $user->assignRole('student');

            $this->record->update(['user_id' => $user->id]);
        }
    }
}
