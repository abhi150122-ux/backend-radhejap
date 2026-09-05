<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JapEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class JapController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate(['mantra_id' => ['required', 'integer']]);
        $user = $request->user();
        $allowed = $user->mantras()->whereKey($data['mantra_id'])->exists() || \App\Models\Mantra::whereNull('user_id')->whereKey($data['mantra_id'])->exists();
        abort_unless($allowed, 404, 'Mantra not found.');
        $entry = DB::transaction(fn () => JapEntry::lockForUpdate()->firstOrCreate(['user_id' => $user->id, 'mantra_id' => $data['mantra_id'], 'entry_date' => Carbon::today()], ['count' => 0]));
        $entry->increment('count');
        return response()->json(['entry' => $entry->fresh('mantra')]);
    }
}
