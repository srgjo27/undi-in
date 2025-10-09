<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;
use Illuminate\Support\Str;

class ShowPropertyStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'properties:show-status {--limit=10 : Number of properties to show}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show properties with their current status and sale dates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limit = $this->option('limit');
        $properties = Property::with('seller')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        if ($properties->isEmpty()) {
            $this->info('No properties found.');
            return Command::SUCCESS;
        }

        $this->info("Showing {$properties->count()} properties:");
        $this->info('Current time: ' . now()->format('Y-m-d H:i:s'));
        $this->newLine();

        $headers = ['ID', 'Title', 'Status', 'Sale Start', 'Sale End', 'Should Be Active?', 'Should Be Pending?'];
        $rows = [];

        foreach ($properties as $property) {
            $rows[] = [
                $property->id,
                Str::limit($property->title, 30),
                $property->status,
                $property->sale_start_date ? $property->sale_start_date->format('Y-m-d H:i') : 'N/A',
                $property->sale_end_date ? $property->sale_end_date->format('Y-m-d H:i') : 'N/A',
                $property->shouldBeActive() ? '✅ Yes' : '❌ No',
                $property->shouldBePendingDraw() ? '✅ Yes' : '❌ No',
            ];
        }

        $this->table($headers, $rows);

        return Command::SUCCESS;
    }
}
