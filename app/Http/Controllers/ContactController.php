<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function show()
    {
        return view('public.contact');
    }

    public function store(ContactRequest $request)
    {
        Log::info('Contact form submission', $request->validated());

        return back()->with('success', 'Thank you! Your message has been received. We will get back to you shortly.');
    }
}
