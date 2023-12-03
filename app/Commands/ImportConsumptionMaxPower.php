<?php

namespace App\Commands;

use App\Connectors\ConsoAPI;
use Illuminate\Support\Carbon;
use App\Models\ConsumptionMaxPower;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class ImportConsumptionMaxPower extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'app:import-consumption-max-power';

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
        $lastConsumption = ConsumptionMaxPower::orderBy('date', 'desc')->first();
        $lastConsumptionDate = $lastConsumption ? $lastConsumption->date->addDay() : Carbon::create(2022, 4, 1);

        $this->info('Last consumption date: ' . $lastConsumptionDate->format('Y-m-d'));
        $consumptions = ConsoAPI::getConsumptionMaxPower($lastConsumptionDate);

        if(empty($consumptions)) {
            $this->info('No new consumption');
            return true;
        }

        $this->info('Consumptions count: ' . count($consumptions));

        collect($consumptions)->each(function ($consumption) {
            $this->info('Importing consumption max power for ' . $consumption['date']);
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $consumption['date']);
            ConsumptionMaxPower::updateOrCreate(
                ['date' => $date->format('Y-m-d')],
                [
                    'consumption' => $consumption['value'],
                    'time' => $date->format('H:i:s'),
                ],
            );
        });
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        $schedule->command(static::class)->dailyAt('21:47');
    }
}
