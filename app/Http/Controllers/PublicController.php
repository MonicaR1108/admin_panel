<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home(Request $request)
    {
        return view('public.home', [
            'publicUserName' => $request->session()->get('public_user_name'),
            'publicGuestId' => $request->session()->get('public_guest_id'),
        ]);
    }

    public function setName(Request $request)
    {
        $validated = $request->validate([
            'user_name' => ['nullable', 'string', 'max:190'],
        ]);

        $name = trim((string) ($validated['user_name'] ?? ''));

        if ($name === '') {
            $request->session()->forget('public_user_name');
        } else {
            $request->session()->put('public_user_name', $name);
        }

        return redirect('/')->with('status', 'Saved.');
    }
}

