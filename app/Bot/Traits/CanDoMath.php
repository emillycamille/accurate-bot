<?php

namespace App\Bot\Traits;

use Illuminate\Support\Str;
use MathParser\Exceptions\DivisionByZeroException;
use MathParser\Exceptions\ParenthesisMismatchException;
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
            return math_eval($message);
        } catch (SyntaxErrorException $e) {
            return 'This is not a valid math expression.';
        } catch (DivisionByZeroException $e) {
            return 'You can not divide by zero.';
        } catch (ParenthesisMismatchException $e) {
            return 'The expression contains mismatching parenthesis.';
        }
    }
}
