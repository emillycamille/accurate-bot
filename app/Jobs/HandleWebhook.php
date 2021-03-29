<?php

namespace App\Jobs;

use App\Bot\Bot;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class HandleWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(array $entries)
    {
        $this->entries = $entries;
    }

    /**
     * Process each entry of the webhook event.
     */
    public function handle(): void
    {
        foreach ($this->entries as $entry) {
            $messagingEvent = $entry['messaging'][0];

            if ($psid = data_get($messagingEvent, 'sender.id')) {
                Bot::typingOn($psid);
            }

            if (array_key_exists('message', $messagingEvent)) {
                Bot::receivedMessage($messagingEvent);
            } elseif (array_key_exists('postback', $messagingEvent)) {
                Bot::receivedPostback($messagingEvent);
            }
        }
    }
}
