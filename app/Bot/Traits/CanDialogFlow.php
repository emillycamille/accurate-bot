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
        $template = $request->input('queryResult.fulfillmentMessages.0');

        return static::$action($params, $template);
    }
}
