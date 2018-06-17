<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App;

class Lara extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel {domain}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $type = 'laravel';
        $domain = $this->argument('domain');
        exec('cd /var/www/ && laravel new ' . $domain . ' && cd ' . $domain . ' && chown www-data ./storage - R');
        $conf =  App\Creator::GetConfig($domain, $type);
        file_put_contents('/etc/apache2/sites-available/' . $domain . '.conf', $conf);
        exec('a2ensite ' . $domain);
        exec('service apache2 reload');
        App\Creator::CreateHost($domain);
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
