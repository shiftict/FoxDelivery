<?php

namespace App\Helpers;


class Actions
{

    public static function successResponse($data = null, $msg = 'OK', $status_code = 200, $status = true)
    {
        $response = [
            'status' => $status,
            'code' => $status_code,
            'message' => $msg,
        ];

        if (is_array($data)) {
            $response = array_merge($response, $data);
        } else {
            $response['data'] = $data;
        }

        return response()->json($response, 200);
    }

    public static function errorResponse($data = null, $msg = 'Bad Request', $status_code = 400, $status = false)
    {
        $response = [
            'status' => $status,
            'code' => $status_code,
            'message' => $msg,
        ];

        if (is_array($data)) {
            $response = array_merge($response, $data);
        } else {
            $response['data'] = $data;
        }
        return response()->json($response, 200);
    }
}
