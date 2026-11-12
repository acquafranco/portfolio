<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\password;

#[Signature('portfolio:admin
    {email : Email used to log in at /admin}
    {--name=Franco Acqua : Display name}
    {--password= : Password (if omitted, it is asked without echoing it)}')]
#[Description('Create an administrator for /admin, or reset the password of an existing one')]
class CreatePortfolioAdmin extends Command
{
    public function handle(): int
    {
        $email = mb_strtolower(trim((string) $this->argument('email')));
        $password = $this->option('password');

        if ($password === null) {
            if (! $this->input->isInteractive()) {
                $this->components->error('Pass --password when running non-interactively.');

                return self::FAILURE;
            }

            $password = password(label: 'Password', required: true, hint: 'Minimum 10 characters.');
        }

        $validator = Validator::make(
            ['email' => $email, 'password' => $password],
            ['email' => ['required', 'email'], 'password' => ['required', 'string', 'min:10']],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->components->error($error);
            }

            return self::FAILURE;
        }

        $user = User::query()->firstOrNew(['email' => $email]);
        $existed = $user->exists;

        $user->forceFill([
            'name' => $user->name ?: (string) $this->option('name'),
            'password' => $password,
            'is_admin' => true,
            'email_verified_at' => $user->email_verified_at ?? now(),
        ])->save();

        $this->components->info($existed
            ? "Password updated and admin access granted for {$email}."
            : "Administrator {$email} created. Log in at ".url('/admin'));

        return self::SUCCESS;
    }
}
