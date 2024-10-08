<?php

namespace App\Enums;

enum TaskType: string
{
   case SUBTASK = 'subtask';
   case DEPENDENCY = 'dependency';
   case MAIN = 'main';
}


enum TaskStatus:string {
    case TODO = 'to-do';
    case PENDING = 'pending';
    case COMPLETED = 'completed';
}
