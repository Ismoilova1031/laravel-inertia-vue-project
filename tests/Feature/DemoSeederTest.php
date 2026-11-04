<?php
declare(strict_types=1);

namespace Tests\Feature;

use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class DemoSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_seeder_builds_a_consistent_dataset(): void
    {
        Storage::fake('local');

        $this->seed(DemoSeeder::class);

        $this->assertDatabaseCount('courses', 5);
        $this->assertDatabaseCount('students', 4);
        $this->assertDatabaseHas('users', ['username' => 'teacher']);

        $this->assertSame(3, DB::table('courses')->where('status', 1)->count());
        $this->assertSame(1, DB::table('courses')->where('status', 0)->count());
        $this->assertSame(1, DB::table('courses')->where('status', 2)->count());

        $this->assertDatabaseHas('certificates', ['grade' => 5, 'percent' => 100]);
        $this->assertDatabaseCount('course_reviews', 1);

        $this->assertDatabaseHas('course_student', ['status' => 0]);
        $this->assertGreaterThanOrEqual(2, DB::table('task_submissions')->where('status', 0)->count());

        $this->assertGreaterThanOrEqual(3, DB::table('notifications')->count());
    }
}