<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mantra;
use App\Models\JapEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function sync(Request $request)
    {
        $data = $request->validate([
            'settings' => ['required', 'array'],
            'settings.goal' => ['required', 'integer', 'min:1', 'max:1000000'],
            'settings.language' => ['required', 'in:english,hinglish,hindi'],
            'settings.haptics' => ['required', 'boolean'],
            'settings.sound' => ['required', 'boolean'],
            'settings.reminders' => ['required', 'boolean'],
            'settings.selected_mantra_id' => ['nullable', 'integer'],
            'custom_mantras' => ['array'],
            'custom_mantras.*.title' => ['required', 'string', 'max:255'],
            'custom_mantras.*.transliteration' => ['required', 'string', 'max:255'],
            'entries' => ['array'],
            'entries.*.mantra_id' => ['nullable', 'integer'],
            'entries.*.mantra_title' => ['required', 'string', 'max:255'],
            'entries.*.entry_date' => ['required', 'date_format:Y-m-d'],
            'entries.*.count' => ['required', 'integer', 'min:0'],
        ]);

        $user = $request->user();
        DB::transaction(function () use ($data, $user) {
            $mantraIds = [];
            foreach ($data['custom_mantras'] ?? [] as $custom) {
                $mantra = $user->mantras()->updateOrCreate(['title' => $custom['title']], ['transliteration' => $custom['transliteration'], 'count_label' => 'Custom']);
                $mantraIds[$mantra->title] = $mantra->id;
            }
            Mantra::whereNull('user_id')->get()->each(fn ($mantra) => $mantraIds[$mantra->title] = $mantra->id);
            $user->settings()->updateOrCreate([], $data['settings']);
            foreach ($data['entries'] ?? [] as $entry) {
                $mantraId = $entry['mantra_id'] ?? $mantraIds[$entry['mantra_title']] ?? null;
                if (!$mantraId) continue;
                JapEntry::updateOrCreate(['user_id' => $user->id, 'mantra_id' => $mantraId, 'entry_date' => $entry['entry_date']], ['count' => $entry['count']]);
            }
        });

        return response()->json(['status' => 'synced']);
    }
}
