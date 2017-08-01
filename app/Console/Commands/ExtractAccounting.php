<?php

namespace App\Console\Commands;

use App\Models\v1\Fund;
use Illuminate\Console\Command;
use App\Models\v1\AccountingItem;
use App\Models\v1\AccountingClass;

class ExtractAccounting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'accounting:extract';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extract all accounting classes and items to their own table.';

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
        $funds = Fund::all();

        $bar = $this->output->createProgressBar(count($funds));

        $funds->each(function ($fund) use ($bar) {
            $class = AccountingClass::firstOrCreate(['name' => $fund->class]);
            $item = AccountingItem::firstOrCreate(['name' => $fund->item]);
            $fund->class_id = $class->id;
            $fund->item_id = $item->id;
            $fund->save();

            $bar->advance();
        });

        $bar->finish();

        $this->info('done');
    }
}
