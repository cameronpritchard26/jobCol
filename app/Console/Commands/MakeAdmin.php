<?php

namespace App\Console\Commands;

use App\Enums\AccountType;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MakeAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin {--promote= : Promote an existing user to admin by username}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user account or promote an existing user to admin';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($promote = $this->option('promote')) {
            return $this->promoteUser($promote);
        }

        return $this->createAdmin();
    }

    /**
     * Promote an existing user to admin.
     */
    protected function promoteUser(string $username): int
    {
        $user = User::where('username', $username)->first();

        if (! $user) {
            $this->error("User '{$username}' not found.");
            return Command::FAILURE;
        }

        if ($user->account_type === AccountType::Admin) {
            $this->warn("User '{$username}' is already an admin.");
            return Command::SUCCESS;
        }

        $user->update(['account_type' => AccountType::Admin]);
        $this->info("User '{$username}' has been promoted to admin.");

        return Command::SUCCESS;
    }

    /**
     * Create a new admin user.
     */
    protected function createAdmin(): int
    {
        $username = $this->ask('Username');
        $password = $this->secret('Password');
        $passwordConfirmation = $this->secret('Confirm password');

        $validator = Validator::make([
            'username' => $username,
            'password' => $password,
            'password_confirmation' => $passwordConfirmation,
        ], [
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return Command::FAILURE;
        }

        User::create([
            'username' => $username,
            'password' => $password,
            'account_type' => AccountType::Admin,
        ]);

        $this->info("Admin account '{$username}' created successfully.");

        return Command::SUCCESS;
    }
}
