<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function store(Request $request){
        //need to create validator class
        // we need to create a transaction
        $mainTasks= Task::create([
                "title"=>$request->title,
                "description"=>$request->description,
                "owner_id"=>$request->ownerId //need to put par default auth Id
            ]);

            //Check subTask & create subTask
            if ($request->has('subtasks')) {
                foreach ($request->subtasks as $subtaskValue) {
                    Task::create([
                        "title"=>$subtaskValue['title'],
                        "description"=>$subtaskValue['description'],
                        "owner_id"=>$subtaskValue['ownerId'],
                        "attached_to"=>$mainTasks->id,
                        "type"=> "subtask"
                    ]);
                }
            }
            //Check & update dependencies
            if ($request->has('dependencies')) {
                foreach ($request->dependencies as $dependencyValue) {
                    //update an existing Task with the mainTask id & type
                    Task::where('id', $dependencyValue['id'])
                    ->update([
                        "attached_to"=>$mainTasks->id,
                        "type"=> "dependency"
                    ]);

                }
            }
            return response()->json($mainTasks);
    }

    public function index(Request $request){

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

    public function show($id){

        $tasks = Task::where("id",$id)
        ->orWhere("attached_to",$id)
        ->get();

        $mainTask = null;
        $subtasks = [];
        $dependencies =[];

        foreach ($tasks as $task) {
            // Main Task
            if ($task->id == $id) {
                $mainTask = $task;
            }
            //
            if($task->type == "subtask") {
                $subtasks[] = [
                    'title' => $task->title,
                    'description' => $task->description,
                    'ownerId' => $task->owner_id,
                    'status'=>$task->status,
                    'created_at'=>$task->created_at,
                    'updated_at'=>$task->updated_at,
                ];
            }
            if($task->type == "dependency" && $task->id != $id) {
                $dependencies[] = [
                    'title' => $task->title,
                    'description' => $task->description,
                    'ownerId' => $task->owner_id,
                    'status'=>$task->status,
                    'created_at'=>$task->created_at,
                    'updated_at'=>$task->updated_at,
                ];
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
}
