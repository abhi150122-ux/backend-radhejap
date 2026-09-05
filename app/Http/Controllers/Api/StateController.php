<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mantra;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $settings = $user->settings()->firstOrCreate([], ['goal' => 108, 'language' => 'hinglish', 'haptics' => true, 'sound' => true, 'reminders' => true]);
        $mantras = Mantra::whereNull('user_id')->get()->concat($user->mantras()->get())->values();
        return response()->json(['user' => $user, 'settings' => $settings, 'mantras' => $mantras, 'entries' => $user->japEntries()->with('mantra')->orderBy('entry_date')->get()]);
    }

    public function update(Request $request)
    {
        $data = $request->validate(['goal' => ['sometimes', 'integer', 'min:1', 'max:1000000'], 'language' => ['sometimes', 'in:english,hinglish,hindi'], 'haptics' => ['sometimes', 'boolean'], 'sound' => ['sometimes', 'boolean'], 'reminders' => ['sometimes', 'boolean'], 'selected_mantra_id' => ['sometimes', 'nullable', 'integer']]);
        return response()->json(['settings' => $request->user()->settings()->updateOrCreate([], $data)]);
    }
}
