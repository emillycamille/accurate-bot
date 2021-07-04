<?php

namespace App\Bot\Traits;

use Illuminate\Http\Request;

trait CanDialogFlow
{
    /**
     * Fulfill DialogFlow request by returning an array as response payload.
     */
    public static function fulfill(Request $request): array
    {
        $action = $request->input('queryResult.action');
        $params = $request->input('queryResult.parameters');
        $template = $request->input('queryResult.fulfillmentMessages.0.text.text.0');
        $params['psid'] = $request->input('originalDetectIntentRequest.payload.data.sender.id');

        $response = static::$action($params, $template);

        return is_string($response)
            ? [ // If response is string
                'fulfillmentMessages' => [[
                    'text' => [
                        'text' => [
                            $response,
                        ],
                    ],
                ]],
            ] : [ // If response is array
                'fulfillmentMessages' => [[
                    'payload' => [
                        'facebook' => $response,
                    ],
                ]],
            ];
    }
}
