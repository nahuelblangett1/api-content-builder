<?php

namespace App\Http\Controllers;

use App\Http\Requests\LongRewriterRequest;
use App\Http\Requests\PlaraphyRequest;
use App\Http\Requests\RetrieveLongRewriterRequest;
use App\Http\Requests\SentimentRequest;
use App\Http\Requests\SummarizerRequest;
use App\Jobs\LongRewriter;
use App\Models\Responses;
use Ramsey\Uuid\Uuid;

class ApiPlaraphyController extends Controller
{
    
    public function getShortRewriter(PlaraphyRequest $request)
    {
        $description = "You are a paraphrasing tool that will paraphrase any text we will deliver to you. You will  receive 3 inputs. First, the text to paraphrase. Second, the 'unique' parameter (either true or false), this parameter forces you to rewrite in a way that passes online plagiarism tests. Third, the speaking mode. This has several options 'normal, fluent, standard, creative";
        $params = "text: ".$request->input('text').". unique: ".$request->input('unique').". mode: ".$request->input('mode');
        
        $result = ChatGPTController::askToChatGpt($description, $params, 20);

        return response()->json([ str_replace("\n\n", " ", $result) ]);
    }

    public function getSentiment(SentimentRequest $request)
    {
        $description = "You are a sentiment analyzing tool that will deliver sentiment analysis based any text we will deliver to you. You will receive the 'text'. Deliver the recognized sentiment and it's score with 5 decimals. In a json object";
        $params = "Text: ".$request->input('text');
        
        $result = ChatGPTController::askToChatGpt($description, $params, 4);

        if ($result !== null) {
            $data = json_decode($result);
        
            if ($data !== null && isset($data->sentiment) && isset($data->score)) {
                return response()->json([
                    "sentiment" => $data->sentiment,
                    "score" => $data->score
                ]);
            }
        } else {
            return response()->json([
                "success" => false,
                "message" => 'error'
            ]);
        }
    }

    public function getSummarizer(SummarizerRequest $request)
    {
        if ($request->input('output_percentage') < 10 || $request->input('output_percentage') > 100) {
            return response()->json([
                "success" => false,
                "message" => 'output_percentage must be between 10 and 100'
            ]);
        }
        
        $description = "You are a summarizing tool that will summarize any text we will deliver to you. You will receive the 'text' and, also, the 'output_percentage', that is an integer that will tell you what percent of the original text will be returned. Values range from 10 to 100.";
        $params = "Text: ".$request->input('text').". output_percentage: ".$request->input('output_percentage');
        
        $result = ChatGPTController::askToChatGpt($description, $params);
        
        return response()->json([ str_replace("\n\n", " ", $result) ]);
    }

    public function getLongRewriter(LongRewriterRequest $request)
    {
        try {
            $response = new Responses();
            $response->job_id = Uuid::uuid4()->toString();
            $response->user_id = \Auth::user()->id;
            $response->save();
            
            $description = "You are a paraphrasing tool that will paraphrase any text we will deliver to you. You will  receive 3 inputs. First, the text to paraphrase. Second, the 'unique' parameter (either true or false), this parameter forces you to rewrite in a way that passes online plagiarism tests. Third, the speaking mode. This has several options 'normal, fluent, standard, creative";
            $params = "Text: ".$request->input('text')." unique: ".$request->input('unique')." mode: ".$request->input('mode');
        
            LongRewriter::dispatch($description, $params, $response->id);
    
            return response()->json([
                "success" => true,
                "job_id" => $response->job_id,
                "status" => "processing"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => 'error'
            ]);
        }
    }

    public function getRetrieveLongRewriter(RetrieveLongRewriterRequest $request)
    {
        $result = Responses::where([
            ['user_id', \Auth::user()->id],
            ['job_id', $request->input('job_id')]
            ])->first();

        if (!$result) {
            return response()->json([
                 "success" => false,
                 "message" => 'job_id not exists'
             ]);
        }

        return response()->json([
            'job_id' => $request->input('job_id'),
            'text' => unserialize($result->response)
        ]);
    }
}
