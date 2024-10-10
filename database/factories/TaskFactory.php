<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Task;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        $userIds = User::pluck('id')->toArray();
        $taskIds = Task::pluck('id')->toArray();
        $status = ['to-do','pending','completed'];
        $type = ['main','dependency','subtask'];
        return [
            'title' => $this->faker->text(255),
            'description' => $this->faker->text(255),
            'owner_id'  => $this->faker->randomElement($userIds),
            'attached_to'=>$this->faker->randomElement($taskIds),
            'status'=>$this->faker->randomElement($status),
            'type'=>$this->faker->randomElement($type),
        ];
    }
}
