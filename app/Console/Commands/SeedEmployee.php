<?php

namespace App\Console\Commands;

use Database\Seeders\EmployeeSeeder;
use Illuminate\Console\Command;

class SeedEmployee extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:employee {index? : The index of the employee to seed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed a specific employee by index or all employees if no index is provided';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $index = $this->argument('index');
        
        if ($index !== null) {
            $this->info("Seeding employee at index: {$index}");
            (new EmployeeSeeder())->run((int) $index);
        } else {
            $this->info("Seeding all employees...");
            (new EmployeeSeeder())->run();
        }

        return Command::SUCCESS;
    }
}