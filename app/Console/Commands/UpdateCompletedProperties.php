<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;

class UpdateCompletedProperties extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'properties:update-completed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update property status to completed for properties that have completed raffles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating property statuses...');

        // Find properties that have completed raffles but status is not completed
        $properties = Property::whereHas('raffles', function ($q) {
            $q->where('status', 'completed');
        })->where('status', '!=', 'completed')->get();

        $this->info("Found {$properties->count()} properties to update");

        foreach ($properties as $property) {
            $property->update(['status' => 'completed']);
            $this->info("Updated property: {$property->title} (ID: {$property->id})");
        }

        $this->info('Property statuses updated successfully!');

        return Command::SUCCESS;
    }
}
