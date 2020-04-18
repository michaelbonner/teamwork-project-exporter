<?php


namespace App\Console\Commands;

use App\Models\File;
use App\Models\Message;
use App\Models\MessageReply;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\TaskList;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

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
        // $projectIds = Project::orderBy('teamwork_id')->get()->pluck('teamwork_id')->toArray();
        // array_unshift($projectIds, 'new project');
        // $projectId = $this->choice('What project ID?', $projectIds, 0);
        // if ($projectId == 'new project') {
        //     $projectId = $this->ask('What is the project ID?');
        // }

        $projectId = 421958;

        // Project
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

        // Task Lists
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

        // Tasks
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

        // Tasks Comments
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

        // Messages
        $messages = $this->getJson("/projects/{$projectId}/posts.json");
        foreach ($messages['posts'] as $message) {
            if ($message['attachments-count']) {
                $message = $this->getJson("/posts/{$message['id']}.json")['post'];
            }
            Message::updateOrCreate(
                [
                    'teamwork_id' => $message['id']
                ],
                [
                    'name' => $message['title'],
                    'data' => $message
                ]
            );
        }

        // Message Replies
        Message::each(function ($message) {
            $replies = $this->getJson("/messages/{$message->teamwork_id}/replies.json");
            foreach ($replies['messageReplies'] as $reply) {
                MessageReply::updateOrCreate(
                    [
                        'teamwork_id' => $reply['id']
                    ],
                    [
                        'message_id' => $message->id,
                        'data' => $reply
                    ]
                );
            }
        });

        // Files
        $files = $this->getJson("/projects/{$projectId}/files.json");
        foreach ($files['project']['files'] as $file) {
            $teamworkFile = $this->getJson("/files/{$file['id']}.json");

            $file = File::updateOrCreate(
                [
                    'teamwork_id' => $teamworkFile['file']['id']
                ],
                [
                    'name' => $teamworkFile['file']['name'],
                    'data' => $teamworkFile['file']
                ]
            );

            if (!Storage::exists($file->filesystemPath)) {
                dd(
                    'File does not exist',
                    $file->filesystemPath,
                    $teamworkFile['file']
                );
            }
        }
    }

    protected function getJson($route, $cache = true)
    {
        if ($cache) {
            return Cache::remember($route, Carbon::now()->addDays(15), function () use ($route) {
                return $this->makeRequest($route)->json();
            });
        }

        return $this->makeRequest($route)->json();
    }

    protected function getHeaders($route)
    {
        return $this->makeRequest($route)->headers();
    }

    protected function makeRequest($route)
    {
        return Http::withBasicAuth(
            config('services.teamwork.key'),
            config('services.teamwork.key')
        )->get(config('services.teamwork.url') . $route);
    }
}
