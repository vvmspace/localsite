<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Creator;

class LaraGit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laragit {source} {domain}';

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
        $source = $this->argument('source');
        $domain = $this->argument('domain');
        exec('cd /var/www/ && git clone ' . $source . ' ' . $domain . ' && cd ' . $domain . ' && cp .env.example .env && composer install && php artisan key:generate && chown www-data ./ -R');
        $conf =  Creator::GetConfig($domain, $type);
        file_put_contents('/etc/apache2/sites-available/' . $domain . '.conf', $conf);
        exec('a2ensite ' . $domain);
        exec('service apache2 reload');
        Creator::CreateHost($domain);
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
