<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    protected $fillable = ['user_id', 'goal', 'language', 'haptics', 'sound', 'reminders', 'selected_mantra_id'];

    protected function casts(): array
    {
        return ['goal' => 'integer', 'haptics' => 'boolean', 'sound' => 'boolean', 'reminders' => 'boolean'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function selectedMantra()
    {
        return $this->belongsTo(Mantra::class, 'selected_mantra_id');
    }
}
