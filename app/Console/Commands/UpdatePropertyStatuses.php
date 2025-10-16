<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;
use Carbon\Carbon;

class UpdatePropertyStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'properties:update-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update property statuses based on current conditions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating property statuses based on current conditions...');

        $now = Carbon::now();

        $pendingDrawProperties = Property::where('status', 'active')
            ->where('sale_end_date', '<', $now)
            ->whereHas('coupons')
            ->whereDoesntHave('raffles')
            ->get();

        foreach ($pendingDrawProperties as $property) {
            $property->update(['status' => 'pending_draw']);
            $this->info("Updated property to pending_draw: {$property->title} (ID: {$property->id})");
        }

        $completedProperties = Property::whereHas('raffles', function ($q) {
            $q->where('status', 'completed');
        })->where('status', '!=', 'completed')->get();

        foreach ($completedProperties as $property) {
            $property->update(['status' => 'completed']);
            $this->info("Updated property to completed: {$property->title} (ID: {$property->id})");
        }

        $this->info('Property statuses updated successfully!');
        $this->info("Pending draw: {$pendingDrawProperties->count()}");
        $this->info("Completed: {$completedProperties->count()}");

        return Command::SUCCESS;
    }
}
