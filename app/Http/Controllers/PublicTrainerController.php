<?php

namespace App\Http\Controllers;

use App\Models\Trainer;

class PublicTrainerController extends Controller
{
    public function index()
    {
        $trainers = Trainer::withCount('batches')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('public.trainers', compact('trainers'));
    }
}