<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Task;

use  App\Notifications\TaskNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class TaskService
{
    //create task, subtask & affect dependencies
    public function store(Request $request){
        //need to create validator class
        try {
        // we need to create a transaction
        DB::beginTransaction(); // <= Starting the transaction

        // we create the main task
        $mainTasks= $this->createTask($request,null,'main');
        $this->notifyUser($request->ownerId,$mainTasks);
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
        DB::commit(); // <= Commit the changes

        return response()->json($mainTasks);

        } catch (\Throwable $th) {
            DB::rollBack(); // <= Rollback in case of an exception
            throw $th;
        }
    }

    // create a single Task
    public function createTask($request, $taskId, String $type ){
         $taskId =DB::table('tasks')->insertGetId([
            "title"=>$request['title'],
            "description"=>$request['description'],
            "owner_id"=>$request['ownerId'],
            "attached_to"=>$taskId,
            "type"=> $type,
            "created_at"=> now(),
            "updated_at"=> now()
        ]);
        $task=DB::select('select * from tasks where id = ?', [$taskId]);
        return $task[0];
    }

    //find a user and notify him
    public function notifyUser(int $id,$task,$mailDelay =1 , $smsDelay=5){
        // find user by Id
        $user = User::where('id',$id)
                ->select('email')
                ->first();
        // send mail to the assigned user
        // we need to put the mail in queue to increase performance
        //$user->notify((new TaskNotification($task))->delay($mailDelay));
        $user->notify((new TaskNotification($task)));


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
        ->with('owner')
        ->get();
    }

    //check if Task has dependency Task with Status <> from completed
    public function dependencyTask(int $id){
        return Task::where('type','dependency')
        ->where('attached_to',$id)
        ->where('status','<>','completed')
        ->count();
    }

    //update task status
    public function updateTaskStatus(int $id , $status,int $authId){
        // check if $id is the same as owner_id
        Task::where('id',$id)
        ->where('owner_id',$authId)
        ->firstOrFail();

        // check if the task has dependency and it status is completed or not
        $countDependencyTask =$this->dependencyTask($id);
        if($countDependencyTask > 0){
            return response()->json("task has Dependency", 403);
        }
        Task::where('id',$id)
        ->update([
            'status'=>$status
        ]);
         return response()->json("task has been modified with success ", 200);
    }

}
