<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@dtfstore13.test');
        $password = env('ADMIN_PASSWORD');
        $tienePasswordEntorno = (bool) $password;
        $generada = null;

        if (! $password) {
            $generada = Str::password(14);
            $password = $generada;
        }

        $admin = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Administrador DTF Store 13',
                'role' => 'admin',
                'password' => Hash::make($password),
            ]
        );

        if ($tienePasswordEntorno && ! $admin->wasRecentlyCreated) {
            $admin->update(['password' => Hash::make($password)]);
            $this->command?->info('Contrasena del administrador actualizada desde ADMIN_PASSWORD.');
        }

        if ($generada && $admin->wasRecentlyCreated) {
            $this->command?->info('Administrador creado. Guarda estas credenciales:');
            $this->command?->info('Correo: '.$email);
            $this->command?->info('Contrasena: '.$generada);
        }
    }
}
