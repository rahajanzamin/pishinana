<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /** فرم «ایده تو ذهن داری؟» */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:150'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        ContactMessage::create([
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'subject' => 'ایده‌ی جدید',
            'message' => $data['message'],
        ]);

        return back()->with('success', 'ایده‌ات برامون رسید! ممنونیم 🌷');
    }
}
