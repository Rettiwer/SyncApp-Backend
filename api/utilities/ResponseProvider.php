<?php
/**
 * Created by PhpStorm.
 * User: rettiwer
 * Date: 29.07.2019
 * Time: 23:06
 */

namespace App\Utilities;


class ResponseProvider
{
    public function withOkData($response, $message, $data)
    {
        return $response->withJson(['success' => true, 'message' => $message, 'data' => $data]);
    }

    public function withOk($response, $message)
    {
        return $response->withJson(['success' => true, 'message' => $message]);
    }

    public function withError($response, $message, $statusCode)
    {
        return $response->withJson(['success' => false, 'message' => $message], $statusCode);
    }
}