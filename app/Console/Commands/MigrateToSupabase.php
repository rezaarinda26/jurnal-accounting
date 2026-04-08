<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateToSupabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:migrate-to-supabase';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate data from MySQL to Supabase PostgreSQL';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tables = [
            'users',
            'accounts',
            'bundles',
            'journals',
            'journal_entries',
        ];

        try {
            DB::connection('supabase')->getPdo();
            $this->info('Connected to Supabase successfully.');
        } catch (\Exception $e) {
            $this->error('Could not connect to Supabase: ' . $e->getMessage());
            return;
        }

        foreach ($tables as $table) {
            $this->info("Migrating table: {$table}");
            
            // Clear existing data in target table just in case, disable FK checks for Postgres is tricky, 
            // but we assume it's fresh schema.
            // DB::connection('supabase')->table($table)->truncate();
            
            $records = DB::connection('mysql')->table($table)->get();
            $count = count($records);
            
            if ($count === 0) {
                $this->warn("No records found in {$table}. Skipping.");
                continue;
            }

            $bar = $this->output->createProgressBar($count);
            $bar->start();

            $chunks = $records->chunk(200);
            foreach ($chunks as $chunk) {
                $data = $chunk->map(function($record) {
                    return (array) $record;
                })->toArray();
                
                DB::connection('supabase')->table($table)->insert($data);
                $bar->advance(count($data));
            }

            $bar->finish();
            $this->line('');

            // Important: Update PostgreSQL sequence since we inserted explicit IDs
            $maxId = DB::connection('supabase')->table($table)->max('id');
            if ($maxId) {
                DB::connection('supabase')->statement("SELECT setval('{$table}_id_seq', {$maxId})");
            }

            $this->info("Successfully migrated {$count} records for {$table}.\n");
        }

        $this->info('Data migration completed successfully!');
    }
}
