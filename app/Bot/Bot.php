<?php

namespace App\Bot;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class Bot
{
    /**
     * Handle received message event.
     */
    public static function receivedMessage(array $event): void
    {
        // TODO: Create MessagingEvent interface.

        $message = $event['message']['text'];
        
        // Define which operation is called
        // Assign reply with the result

        if (strpos($message,"+")) {
            $message = preg_replace('/\s+/', '', $message);
            echo "tambah\n";
            $pieces = explode("+", $message);
            $reply = (int)$pieces[0] + (int)$pieces[1];
        }
        elseif (strpos($message,"-")) {
            $message = preg_replace('/\s+/', '', $message);
            echo "kurang\n";
            $pieces = explode("-", $message);
            $reply = $pieces[0] - $pieces[1];
        }
        elseif (strpos($message,"*") or strpos($message,"x")) {
            $message = preg_replace('/\s+/', '', $message);
            echo "kali\n";
            if (strpos($message,"*")) {
                $pieces = explode("*", $message);
        }
        elseif (strpos($message,"x")) {
            $message = preg_replace('/\s+/', '', $message);
            $pieces = explode("x", $message);
        }
            $reply = $pieces[0] * $pieces[1];
        }
        elseif (strpos($message,"/") or strpos($message, "÷") or strpos($message, ":")) {
            $message = preg_replace('/\s+/', '', $message);
            echo "bagi\n";
            if (strpos($message,"/")) {
                $pieces = explode("/", $message);
        }
            elseif (strpos($message, "÷")) {
                $message = preg_replace('/\s+/', '', $message);
                $pieces = explode("÷",$message);
        }
            elseif (strpos($message,":")) {
                $message = preg_replace('/\s+/', '', $message);
                $pieces = explode(":", $message);
        }
            $reply = $pieces[0] / $pieces[1];
        }
        elseif ($message === "jam") {
            // $current = Carbon::now()->format('H:i');
            $current = Carbon::now()->setTimezone('Asia/Bangkok')->format('H:i');
            // $current->setTimezone('Asia/Bangkok');
            $reply = $current;
        }
        elseif ($message === "hari") {
            $current = Carbon::now()->setTimezone('Asia/Bangkok')->format('l');
            $reply = $current;
        }
        elseif ($message === "hari jam") {
            $current = Carbon::now()->setTimezone('Asia/Bangkok')->format('l H:i');
            $reply = $current;
        }
        elseif ($message === "hari indo") {
            $current = Carbon::now()->setTimezone('Asia/Bangkok')->format('l');
            switch($current){
                case "Monday":
                    $day = "Senin";
                break;
                case "Tuesday":
                    $day = "Selasa";
                break;
                case "Wednesday":
                    $day = "Rabu";
                break;
                case "Thursday":
                    $day = "Kamis";
                break;
                case "Friday":
                    $day = "Jumat";
                break;
                case "Saturday":
                    $day = "Sabtu";
                break;
                case "Sunday":
                    $day = "Minggu";
                break;
                default:
                $day = "Error";
            }
            $reply = $day;
        }
        else {
            $reply = "I'm still learning, so I don't understand '$message' yet. Chat with me again in a few days!";
        }
        
        static::sendMessage($reply, $event['sender']['id']);
    }

    /**
     * Send $message to $recipient using Messenger Send API.
     * https://developers.facebook.com/docs/messenger-platform/send-messages/#send_api_basics.
     */
    public static function sendMessage(string $message, string $recipient): void
    {
        $data = [
            'messaging_type' => 'RESPONSE',
            'recipient' => ['id' => $recipient],
            'message' => ['text' => $message],
        ];

        Http::post(config('bot.fb_sendapi_url'), $data);
    }
}
