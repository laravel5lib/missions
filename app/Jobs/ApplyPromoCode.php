<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\v1\Promocode;
use App\Models\v1\Transaction;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApplyPromoCode extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $model;
    protected $promocode;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Model $model, string $promocode)
    {
        $this->model = $model;
        $this->code = $promocode;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Transaction $transaction, Promocode $promocode)
    {
        $code = $promocode->byCode($this->code)->fresh();

         if ($code->exists()) {
            $record = $code->first();

            $transaction->create([
                'type' => 'credit',
                'amount' => $record->promotional->reward/100,
                'fund_id' => $this->model->fund->id,
                'details' => ['reason' => $record->promotional->name.' Promotional Credit']
            ]);

            if ($affiliate = $record->rewardable) {
                $transaction->create([
                    'type' => 'credit',
                    'amount' => $record->promotional->reward/100,
                    'fund_id' => $affiliate->fund->id,
                    'details' => ['reason' => $record->promotional->name.' Promotional Credit']
                ]);
            }

         }
    }
}
