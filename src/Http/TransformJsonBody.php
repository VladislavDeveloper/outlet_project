<?php

namespace App\Http;

use Symfony\Component\HttpFoundation\Request;

class TransformJsonBody
{
    static public function handleRequest(Request $request): Request
    {
        $data = json_decode($request->getContent(), true);

        if($data === null){
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }
}