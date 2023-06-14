<?php

namespace App\Console\Commands;

use App\Models\Coin;
use Illuminate\Console\Command;

class CoinCollectCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coin:collect';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command To Collect Coin from coingecko';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Coin::collect();
    }
}
