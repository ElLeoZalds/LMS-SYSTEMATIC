<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ContentsFilePathMigrationTest extends TestCase
{
    public function test_contents_table_has_file_path_column(): void
    {
        Schema::dropIfExists('contents');

        Artisan::call('migrate', [
            '--path' => 'database/migrations/2026_04_12_014345_create_contents_table.php',
            '--force' => true,
        ]);

        $this->assertTrue(Schema::hasColumn('contents', 'file_path'));
    }
}
