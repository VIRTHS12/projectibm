<?php

namespace App\Services;

use Exception;
use SabatinoMasala\Replicate\Replicate;

class ReplicateServices
{
    protected $replicate;

    public function __construct()
    {
        $token = env('REPLICATE_TOKEN');
        if (!$token){
            throw new Exception('Token Tidak Ditemukan');
        }
        $this->replicate=new Replicate($token);
    }


    public function getReply(string $prompt) : string
    {
        $output = $this->replicate->run('ibm-granite/granite-3.3-8b-instruct', [
            'prompt' => $prompt
        ]);

        return is_array($output) ? implode("\n", $output) : $output;
    }
}
