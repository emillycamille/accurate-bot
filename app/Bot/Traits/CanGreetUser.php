<?php

namespace App\Bot\Traits;

use Illuminate\Support\Str;

trait CanGreetUser
{
    /**
     * Determine whether the $message is asking time.
     */
    public static function isSayingHello(string $message): bool
    {
        return Str::contains($message, ['Halo', 'halo', 'Hi', 'hi']);
    }

    /**
     * Tell the current time, as requested in $message.
     */
    public static function greetUser(string $message, $userID): string
    {
        // $fbPageToken = env('FB_PAGE_TOKEN');
        $json = json_decode(file_get_contents("https://graph.facebook.com/v3.2/{$userID}?access_token=EAACVBIrdOPMBAATZBa4bqr5ZBWIFUecMGnRqw4E5usQlPXQdjZCfkt2ZAkEZALx8RtabnG6BDajKHYh2CcvOhZBxLQoM67ZB1ZB3U2nmATIPeVOOKJgK4qVP5Cjv6TUpLtHKtB8tWW5MaI53QK2c3NWKnuhdjWLYPmyrNDZCOycbvnuRB3ZC1PHHOk"), true);
        $name = $json['name'];
        $nameSplit = preg_split('/\s+/',$name);
        $nickname = $nameSplit[0];
        return "Halo {$nickname}!";

    }
}
