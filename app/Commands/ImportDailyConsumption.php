<?php

namespace App\Commands;

use App\Connectors\ConsoAPI;
use Illuminate\Support\Carbon;
use App\Models\DailyConsumption;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class ImportDailyConsumption extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'app:import-daily-consumption';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $lastConsumption = DailyConsumption::orderBy('date', 'desc')->first();

        $lastConsumptionDate = $lastConsumption ? $lastConsumption->date->addDay() : Carbon::create(2022, 4, 1);

        $this->info('Last consumption date: ' . $lastConsumptionDate->format('Y-m-d'));

        $consumptions = ConsoAPI::getDailyConsumption($lastConsumptionDate);

        if(empty($consumptions)) {
            $this->info('No new consumption');
            return true;
        }

        $this->info('Consumptions count: ' . count($consumptions));

        collect($consumptions)->each(function ($consumption) {
            $this->info('Importing consumption for ' . $consumption['date']);
            DailyConsumption::updateOrCreate(
                ['date' => $consumption['date']],
                ['consumption' => $consumption['value']],
            );
        });

        return true;
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        $schedule->command(static::class)->dailyAt('21:53');
    }
}
