<?php

namespace App\Jobs;

use App\Http\Controllers\ChatGPTController;
use App\Models\Responses;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Termwind\Components\Dd;

class LongRewriter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $description;
    protected $params;
    protected $id;

    /**
     * Create a new job instance.
     */
    public function __construct($description, $params, $id)
    {
        $this->description = $description;
        $this->params = $params;
        $this->id = $id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $result = ChatGPTController::askToChatGpt($this->description, $this->params, 60);
        $this->saveResponse($result);
    }

    public function saveResponse($result){
        $response = Responses::find($this->id);
        $responseData = serialize($result);
        $response->response = $responseData;
        $response->save();

        return true;
    }
}
