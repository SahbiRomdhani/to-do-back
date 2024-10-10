<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TaskType;
use App\Models\User;

class Task extends Model
{
    // to make it better we should create table for subTask
    // and we should create manuel pivot table to put dependency (Task) with attached to (Task)

    use HasFactory;
    protected $fillable = [
        "title", "description", "status","owner_id","attached_to","type",
   ];
   protected $with = ['owner_id'];

   protected $cast= ['type'=> TaskType::class,];


  /**
    * Get the User that owns the Task.
   */
   public function owner_id()
   {
       return $this->belongsTo(User::class,'owner_id');
   }

}
