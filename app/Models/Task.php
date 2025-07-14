<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'task_name',
        'name',
        'email',
        'status',
        'deadline',
    ];

     /**
     * The user assigned to this task.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
