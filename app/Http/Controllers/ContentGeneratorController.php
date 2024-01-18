<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContentGeneratorRequest;
use App\Http\Requests\NameGeneratorRequest;
use App\Http\Requests\CompletionsRequest;
use Illuminate\Http\Request;

class ContentGeneratorController extends Controller
{
    public function getDescription(ContentGeneratorRequest $request)
    {
        $description = "You are a helpful assistant, which will provide a technical and comprehensive description for a product. We will provide you with the product name and a short description and you will have to deliver a description for it. We need 4 different descriptions. Maximum 50 words per description.";
        $params = "Product: ".$request->input('name')." Description: ".$request->input('description');
        $result = ChatGPTController::askToChatGpt($description, $params);

        return response()->json([ str_replace("\n\n", " ", $result) ]);
    }

    public function getNames(NameGeneratorRequest $request)
    {
        $description = "You are a helpful assistant, which will provide 25 creative and innovative Business Names returned in an array.  We will provide you with the business keyword and you will return the possible business names.";
        $result = ChatGPTController::askToChatGpt($description, $request->input('q'), 30);
        
        /* Fomated Result */
        $element = explode("\n", $result);
        $elementFormat = array_map(function ($element) {
            return trim(preg_replace('/^\d+\.\s+/', '', $element));
        }, $element);
        $elementFormated = array_filter($elementFormat);
        $jsonResult = json_encode($elementFormated);


        // return response(json_decode($jsonResult));
        return response()->json(json_decode($jsonResult));
    }

    public function completions(CompletionsRequest $request)
    {
        $result = ChatGPTController::askToChatGpt($request->input('system_content'), $request->input('user_content'), 30);
        return response()->json([ str_replace("\n\n", " ", $result) ]);
    }
}
