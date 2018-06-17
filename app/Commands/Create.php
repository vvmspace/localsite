<?php

namespace App\Commands;

use App;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class Create extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create {domain} {type=default}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Usage: create {domain} {type=default}';

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
    public function handle(): void
    {
        $type = $this->argument('type');
        $domain = $this->argument('domain');
        $conf =  App\Creator::GetConfig($domain, $type);
        file_put_contents('/etc/apache2/sites-available/' . $domain . '.conf', $conf);
        exec('a2ensite ' . $domain);
        exec('service apache2 reload');
        App\Creator::CreateHost($domain);
        // echo $conf;
    }

    /**
	 * Define the command's schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule $schedule
	 *
	 * @return void
	 */
	public function schedule(Schedule $schedule): void
	{
		// $schedule->command(static::class)->everyMinute();
	}
}
