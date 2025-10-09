<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;
use Carbon\Carbon;

class UpdatePropertyStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'properties:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically update property status based on sale dates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting automatic property status update...');
        $this->info('Current time: ' . now()->format('Y-m-d H:i:s'));

        $updatedCount = 0;
        $properties = Property::needsStatusUpdate()->get();

        $this->info("Found {$properties->count()} properties that need status update");

        foreach ($properties as $property) {
            $oldStatus = $property->status;

            $this->line("Checking Property ID {$property->id} - '{$property->title}':");
            $this->line("  Current status: {$oldStatus}");
            $this->line("  Sale start: {$property->sale_start_date}");
            $this->line("  Sale end: {$property->sale_end_date}");

            if ($property->updateStatusAutomatically()) {
                $updatedCount++;
                $this->info("  ✅ Updated from {$oldStatus} to {$property->status}");
            } else {
                $this->line("  ➡️  No update needed");
            }
        }

        if ($updatedCount > 0) {
            $this->info("✅ Total properties updated: {$updatedCount}");
        } else {
            $this->info("ℹ️  No properties need status update at this time.");
        }

        return Command::SUCCESS;
    }
}
