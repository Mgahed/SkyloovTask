<?php

function customResponse($result = null, $status = 200, $additional = [])
{

    if ($status >= 200 && $status < 300) {
        $success = true;
        $Key = 'result';
    } else {
        $success = false;
        $Key = 'error';
    }

    if (is_string($result)) {
        $result = [
            'message' => $result,
        ];
    }

    $data = [
        'status' => $status,
        'success' => $success,
        $Key => $result,
    ];

    return response()->json(array_merge($data, $additional), $status);
}
