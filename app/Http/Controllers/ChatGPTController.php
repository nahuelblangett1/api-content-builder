<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use PhpParser\Node\Stmt\Return_;

class ChatGPTController extends Controller
{
    protected $httpClient;
   
    public static function askToChatGpt($system, $user, $timeout = null)
    {
        try {
            $httpClient = new Client([
                'base_uri' => 'https://api.openai.com/v1/',
                'headers' => [
                    'Authorization' => 'Bearer ' . env('CHATGPT_API_KEY'),
                    'Content-Type' => 'application/json',
                ],
                'timeout' => $timeout ?? 15,
            ]);
    
            $response = $httpClient->post('chat/completions', [
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' => $system],
                        ['role' => 'user', 'content' => $user],
                    ],
                ],
            ]);
    
            return json_decode($response->getBody(), true)['choices'][0]['message']['content'];
        } catch (\Throwable $e) {
            return response([ 
                'error' => 'Something went wrong. Please try again later.'
            ]);
        }
    }
}