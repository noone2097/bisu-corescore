<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GetFacultyEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'faculty:emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all faculty member emails';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $facultyEmails = DB::table('users')
            ->where('role', 'faculty')
            ->pluck('email')
            ->toArray();

        if (empty($facultyEmails)) {
            $this->error('No faculty members found.');
            return;
        }

        // Display emails in a table format
        $this->table(
            ['Faculty Emails'],
            collect($facultyEmails)->map(fn($email) => [$email])->toArray()
        );

        $this->info(sprintf('Found %d faculty member(s)', count($facultyEmails)));
    }
}