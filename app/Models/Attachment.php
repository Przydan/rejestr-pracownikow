<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'file_path', 'file_name'])]
class Attachment extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
