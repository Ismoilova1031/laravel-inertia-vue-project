<?php
// app/Console/Commands/MakeAdmin.php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Domain\User\UserRole;
use App\Infrastructure\Eloquent\UserModel;
use Illuminate\Console\Command;

final class MakeAdmin extends Command
{
    protected $signature = 'app:make-admin {username} {password} {--name=Teacher}';
    protected $description = 'Birinchi o\'qituvchi/admin foydalanuvchini yaratadi';

    public function handle(): int
    {
        if (UserModel::where('username', $this->argument('username'))->exists()) {
            $this->error('Bu username band.');

            return self::FAILURE;
        }

        UserModel::create([
            'name' => $this->option('name'),
            'username' => $this->argument('username'),
            'password' => $this->argument('password'),   
            'role' => UserRole::cases()[0],              
        ]);

        $this->info('Yaratildi.');

        return self::SUCCESS;
    }
}