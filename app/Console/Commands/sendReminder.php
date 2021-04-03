<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Bot\Bot;

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
        Bot::sendMessage('halo adiet', '5196920073666570');
        echo 'hello world';
    }
}
