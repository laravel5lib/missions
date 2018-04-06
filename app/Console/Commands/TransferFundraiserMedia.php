<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use App\Jobs\TransferFundraiserMedia as TransferMedia;

class TransferFundraiserMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:media';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer existing fundraiser media to new featured image format.';

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
     * @return mixed
     */
    public function handle()
    {   
        dispatch(new TransferMedia);
    }
}
