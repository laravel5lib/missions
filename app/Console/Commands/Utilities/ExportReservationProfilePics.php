<?php

namespace App\Console\Commands\Utilities;

use App\Models\v1\Reservation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileExistsException;
use League\Flysystem\FileNotFoundException;

class ExportReservationProfilePics extends Command
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
    protected $description = 'Export and save a directory of all reservation pics organized by region, squad, and role.';
    /**
     * @var Reservation
     */
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
        $reservations = $this->reservation->current()->get();

        $bar = $this->output->createProgressBar(count($reservations));

        foreach ($reservations as $reservation) {
            if ($reservation->avatar) {
                $filename = sprintf(
                    "%s_%s_%s",
                    snake_case(teamRole($reservation->desired_role)),
                    snake_case($reservation->given_names),
                    snake_case($reservation->surname)
                );

                $squad = $reservation->squads->first();

                if ($squad) {
                    $region = $squad->team->regions ? snake_case($squad->team->regions()->where('country_code', '<>', 'us')->first()->name) : 'unassigned';
                    $team = snake_case($squad->team->callsign);
                    $squad = snake_case($squad->callsign);
                    $path = $region . '/' . $team . '/' . $squad;
                } else {
                    $path = 'unassigned';
                }

                try {
                    Storage::disk('s3')->copy(
                        $reservation->avatar->source,
                        '/export/reservations/images/' . $path . '/' . $filename.'.jpg'
                    );
                } catch (FileExistsException $e) {
                } catch (FileNotFoundException $e) {
                }
            }

            $bar->advance();
        }

        $bar->finish();
    }
}
