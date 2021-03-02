<?php

namespace App\Http\Controllers;

use App\Bot\Bot;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AuthController
{
    /**
     * Handle the redirect from Accurate, carrying Accurate code and user PSID.
     */
    public function callback(Request $request): RedirectResponse
    {
        if (! $request->has(['code', 'psid'])) {
            return abort(404);
        }

        Bot::getAccessToken($request->code, $request->psid);

        Bot::askWhichDb($request->psid);

        return redirect('https://www.messenger.com/closeWindow');
    }
}
