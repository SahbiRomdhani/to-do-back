<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TaskType;
use App\Models\User;

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
   public function owner()
   {
       return $this->belongsTo(User::class,'owner_id');
   }
}
