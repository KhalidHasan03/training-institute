<?php

namespace App\Filament\Resources\TrainerResource\Pages;

use App\Filament\Resources\TrainerResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

class CreateTrainer extends CreateRecord
{
    protected static string $resource = TrainerResource::class;

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
            $user->assignRole('trainer');

            $this->record->update(['user_id' => $user->id]);
        }
    }
}
