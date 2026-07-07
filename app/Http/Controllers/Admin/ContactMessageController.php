<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;

class ContactMessageController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(10);

        return view('admin.contact-messages.index', compact('messages'));
    }

    public function destroy($id)
    {
        ContactMessage::findOrFail($id)->delete();

        return back()->with('success', 'Message deleted successfully.');
    }
}
