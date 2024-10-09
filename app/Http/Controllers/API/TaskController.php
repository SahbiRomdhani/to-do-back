<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Task;
use App\Models\User;

use Illuminate\Support\Facades\DB;
use App\Mail\TaskMailNotification;
use  App\Notifications\TaskNotification;
use App\Services\TaskService;

class TaskController extends Controller
{
    private $taskService;
    public function __construct(TaskService $taskService) {
        $this->taskService = $taskService;
    }

    public function store(Request $request){
        return $this->taskService->store($request);
    }

    public function index(Request $request){
        return $this->taskService->tasks($request);
    }

    public function show($id){
        return $this->taskService->displayTask($id);
    }
}
