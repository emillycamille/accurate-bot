<?php

namespace App\Bot\Traits;

use Illuminate\Support\Str;
use MathParser\Exceptions\SyntaxErrorException;

trait CanDoMath
{
    public static function isMathExpression(string $message): bool
    {
        return Str::contains($message, ['+', '-', '*', '/', 'x', ':', '÷']);
    }

    public static function calculateMathExpression(string $message): string
    {
        try {
            $reply = math_eval($message);
        } catch (SyntaxErrorException $th) {
            $reply = 'This is not a valid math expression.';
        }

        return $reply;
    }
}
