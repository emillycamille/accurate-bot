<?php

namespace App\Http\Controllers;

use App\Bot\Bot;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController
{
    /**
     * Handle the redirect from Accurate, carrying Accurate code and user PSID.
     */
    public function callback(Request $request): Response
    {
        if (! $request->has(['code', 'psid'])) {
            return response('', 404);
        }

        Bot::getAccessToken($request->code);

        return response();
    }
}

// Route::get('/accurate-callback', function () {
//     return redirect(
//         'https://www.messenger.com/closeWindow?'.http_build_query([
//             'image_url' => 'https://thebrag.com/wp-content/uploads/2021/02/Dogecoin-970x550-1.jpg',
//             'display_text' => 'Login successful',
//         ]),
//     );
// });
