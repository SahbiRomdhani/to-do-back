<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TaskType;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        "title", "description", "status","owner_id","attached_to","type",
   ];

   protected $cast= ['type'=> TaskType::class,];


  /**
    * Get the User that owns the Task.
   */
   public function owner(): BelongsTo
   {
       return $this->belongsTo(User::class);
   }
}
