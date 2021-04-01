<?php

namespace App\Bot\Traits;

use Illuminate\Support\Str;

trait CanRemind
{
    /**
     * Determine whether $message is a math expression.
     */
    public static function isAskingToRemind(string $message): bool
    {
        return Str::contains(strtolower($message), ['ingatkan', 'remind']);
    }

    /**
     * Calculate the given math expression.
     */
    public static function remindUser(string $message): void
    {
        $message = strtolower($message);

        foreach (['ingatkan', 'remind'] as $needle) {
            if (Str::contains($message, $needle)) {

                // Explode message to grab the information
                $information = '';

                // Change time to now() or Carbon format

                // If there are no information (e. g. only "ingatkan")
                if (!$information) {
                    // Reply with remind format
                }

                // Save information to database

            }
        }

        // Return confirmation message
    }
}
