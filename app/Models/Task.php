<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        "title", "description", "status"
   ];


  /**
    * Get the User that owns the Task.
   */
   public function owner(): BelongsTo
   {
       return $this->belongsTo(User::class);
   }
}
