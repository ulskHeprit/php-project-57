<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Label extends Model
{
    use HasFactory;

    public $fillable = ['name', 'description'];

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'task_label');
    }
}
