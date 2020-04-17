<?php


namespace App\Console\Commands;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\TaskList;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GetTeamworkData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teamwork:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get available projects';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $projectIds = Project::orderBy('teamwork_id')->get()->pluck('teamwork_id')->toArray();
        array_unshift($projectIds, 'new project');
        $projectId = $this->choice('What project ID?', $projectIds, 0);
        if ($projectId == 'new project') {
            $projectId = $this->ask('What is the project ID?');
        }

        $project = $this->getJson("/projects/{$projectId}.json");
        Project::updateOrCreate(
            [
                'teamwork_id' => $project['project']['id'],
            ],
            [
                'name' => $project['project']['name'],
                'data' => $project['project']
            ]
        );

        $taskLists = $this->getJson("/projects/{$projectId}/tasklists.json");
        foreach ($taskLists['tasklists'] as $taskList) {
            TaskList::updateOrCreate(
                [
                    'teamwork_id' => $taskList['id']
                ],
                [
                    'name' => $taskList['name'],
                    'data' => $taskList
                ]
            );
        }

        $tasks = $this->getJson("/projects/{$projectId}/tasks.json?getFiles=true");
        foreach ($tasks['todo-items'] as $task) {
            Task::updateOrCreate(
                [
                    'teamwork_id' => $task['id']
                ],
                [
                    'task_list_id' => TaskList::where('teamwork_id', $task['todo-list-id'])->first()->id,
                    'name' => $task['content'],
                    'data' => $task
                ]
            );
        }

        Task::where('data->comments-count', '>', 0)->each(function ($task) {
            $taskComments = $this->getJson("/tasks/{$task->teamwork_id}/comments.json");
            foreach ($taskComments['comments'] as $comment) {
                $task = Task::where('teamwork_id', $comment['commentable-id'])->first();
                TaskComment::updateOrCreate(
                    [
                        'teamwork_id' => $comment['id']
                    ],
                    [
                        'task_id' => $task->id,
                        'data' => $comment
                    ]
                );
            }
        });
    }

    protected function getJson($route)
    {
        return Http::withBasicAuth(
            config('services.teamwork.key'),
            config('services.teamwork.key')
        )->get(config('services.teamwork.url') . $route)->json();
    }

    protected function getHeaders($route)
    {
        return Http::withBasicAuth(
            config('services.teamwork.key'),
            config('services.teamwork.key')
        )->get(config('services.teamwork.url') . $route)->headers();
    }
}
