<?php

namespace App\Console\Commands;

use App\Bot\Bot;
use App\Models\Reminder;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class sendReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sendreminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder to user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $time = Carbon::now();
        $reminders = Reminder::where('remind_at', $time)->get();

        foreach ($reminders as $reminder) {
            $action = $reminder->action;
            $psid = $reminder->psid;
            $name = $reminder->first_name;

            Bot::sendMessage(__('bot.remind', compact('name', 'action')), $psid);
        }
    }
}
