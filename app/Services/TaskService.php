<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Task;

use  App\Notifications\TaskNotification;
use Illuminate\Http\Request;


class TaskService
{
    //create task, subtask & affect dependencies
    public function store(Request $request){
        //need to create validator class
        // we need to create a transaction
        $delay = now()->addMinutes(10);
        // we create the main task
        $mainTasks= $this->createTask($request,null,'main');
        //$this->notifyUser($request->ownerId,$mainTasks);

        //Check subTask & create subTask
        if ($request->has('subtasks')) {
            foreach ($request->subtasks as $subtaskValue) {
                $this->createTask($subtaskValue,$mainTasks->id,"subtask");
                //$this->notifyUser($subtaskValue->ownerId,$mainTasks);
            }
        }
            //Check & update dependencies
        if ($request->has('dependencies')) {
            foreach ($request->dependencies as $dependencyValue) {
            //update an existing Task with the mainTask id & type
            Task::where('id', $dependencyValue)
                ->update([
                    "attached_to"=>$mainTasks->id,
                    "type"=> "dependency"
                ]);

            }
        }
            return response()->json($mainTasks);
    }

    // create a single Task
    public function createTask($request, $taskId, String $type ){
        return Task::create([
            "title"=>$request['title'],
            "description"=>$request['description'],
            "owner_id"=>$request['ownerId'],
            "attached_to"=>$taskId,
            "type"=> $type
        ]);
    }

    //find a user and notify him
    public function notifyUser(int $id,$task){
        // find user by Id
        $user = User::where('id',$id)
                ->select('email')
                ->first();
        // send mail to the assigned user
        // we need to put the mail in queue to increase performance
        $user->notify(new TaskNotification($task));

    }

    //get list of task with pagination
    public function tasks(Request $request){
                //paginate
                $limit = $request->query('limit', 10); // number of records per page
                $page = $request->query('page', 1);
                $order = $request->query('order','DESC');
                $offset = ($page - 1) * $limit; // calculate offset

                $tasks = Task::where("type", '<>',"subtask")
                ->orderBy('id', $order)
                ->offset($offset)
                ->limit($limit)
                ->get();

                $total = Task::where("type", '<>',"subtask")->count();

                return response()->json([
                    'data' => $tasks,
                    'total' => $total,
                    'page' => (int) $page,
                    'limit' => $limit,
                ]);

    }

    //get task with specific format
    public function displayTask(int $id){
        $tasks = $this->taskById($id);

        $mainTask = null;
        $subtasks = [];
        $dependencies =[];

        foreach ($tasks as $task) {
            // Main Task
            if ($task->id == $id) {
                $mainTask = $task;
            }

            if($task->type == "subtask") {
                $subtasks[] = $this->formationTask($task);
            }

            if($task->type == "dependency" && $task->id != $id) {
                $dependencies[] = $this->formationTask($task);
            }
        }

        // Structurer la réponse JSON avec la tâche principale et les sous-tâches
        $taskFormatted = [
            'title' => $mainTask->title,
            'description' => $mainTask->description,
            'ownerId' => $mainTask->owner_id,
            'status'=>$mainTask->status,
            'created_at'=>$mainTask->created_at,
            'updated_at'=>$mainTask->updated_at,
            'subtasks' => $subtasks,
            'dependencies' => $dependencies
        ];

        return response()->json($taskFormatted);
    }

    //formation task
    public function formationTask($task){
        return [
            'title' => $task->title,
            'description' => $task->description,
            'ownerId' => $task->owner_id,
            'status'=>$task->status,
            'created_at'=>$task->created_at,
            'updated_at'=>$task->updated_at,
        ];
    }

    //get task by id
    public function taskById(int $id){
        return Task::where("id",$id)
        ->orWhere("attached_to",$id)
        ->get();
    }

}
