<?php

namespace App\Console\Commands\Utilities;

use App\Models\v1\Reservation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileNotFoundException;
use Mockery\Exception;

class ExportReservationPics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:reservation-pics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a zip folder of all reservation profile pics.';

    private $reservation;

    /**
     * Create a new command instance.
     *
     * @param Reservation $reservation
     */
    public function __construct(Reservation $reservation)
    {
        parent::__construct();
        $this->reservation = $reservation;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $reservations = $this->reservation
            ->with('trip.campaign', 'squads.team')
            ->get();

        $bar = $this->output->createProgressBar(count($reservations));

        $reservations->each(function ($reservation) use ($bar) {

            $newFilePath = strtolower(
                sprintf("%s/%s/%s",
                    'export/pics',
                    camel_case($reservation->trip->campaign->name),
                    $reservation->squads()->first() ? $reservation->squads()->team->name : 'unassigned'
                )
            );

            $newFilename = strtolower(
                sprintf("%s_%s_%s",
                    snake_case(teamRole($reservation->desired_role)),
                    snake_case($reservation->given_names),
                    $reservation->surname
                )
            );

            try {
                Storage::disk('s3')->copy(
                    $reservation->getAvatar()->source,
                    $newFilePath . '/' . $newFilename
                );
            } catch(FileNotFoundException $e) {
                $this->error($e->getMessage());
            }

            $bar->advance();
        });

        $bar->finish();
    }
}
