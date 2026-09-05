<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mantra extends Model
{
    protected $fillable = ['user_id', 'title', 'transliteration', 'count_label'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function japEntries()
    {
        return $this->hasMany(JapEntry::class);
    }
}
