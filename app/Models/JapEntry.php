<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JapEntry extends Model
{
    protected $fillable = ['user_id', 'mantra_id', 'entry_date', 'count'];

    protected function casts(): array
    {
        return ['entry_date' => 'date', 'count' => 'integer'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mantra()
    {
        return $this->belongsTo(Mantra::class);
    }
}
