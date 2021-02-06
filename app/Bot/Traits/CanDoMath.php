<?php

namespace App\Bot\Traits;

use Illuminate\Support\Str;

trait CanDoMath
{
    public static function isMathExpression(string $message): bool
    {
        return Str::contains($message, ['+', '-', '*', '/', 'x', ':', '÷']);
    }

    public static function calculateMathExpression(string $message): string
    {
        // Trim whitespace.
        $message = preg_replace('/\s+/', '', $message);

        // Extract the operator out of the message.
        foreach (['+', '-', '*', '/', 'x', ':', '÷'] as $sign) {
            if (Str::contains($message, $sign)) {
                $operator = $sign;

                break;
            }
        }

        // Find the 2 numbers to calculate.
        $pieces = explode($operator, $message);

        // Calculate according to the operator.
        switch ($operator) {
            case '+':
                return $pieces[0] + $pieces[1];

            case '-':
                return $pieces[0] - $pieces[1];

            case '*':
            case 'x':
                return $pieces[0] * $pieces[1];

            case '/':
            case ':':
            case '÷':
                return $pieces[0] / $pieces[1];
        }
    }
}
