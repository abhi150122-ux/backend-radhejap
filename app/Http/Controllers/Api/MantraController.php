<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MantraController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate(['title' => ['required', 'string', 'max:255'], 'transliteration' => ['nullable', 'string', 'max:255']]);
        $mantra = $request->user()->mantras()->create(['title' => $data['title'], 'transliteration' => $data['transliteration'] ?: $data['title'], 'count_label' => 'Custom']);
        $request->user()->settings()->updateOrCreate([], ['selected_mantra_id' => $mantra->id]);
        return response()->json(['mantra' => $mantra], 201);
    }
}
