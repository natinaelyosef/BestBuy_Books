<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::where('email', 'superadmin@bookhub.com')->first();
if (! $user) {
    echo "Superadmin not found\n";
    exit(1);
}

$user->password = Illuminate\Support\Facades\Hash::make('Admin@123');
$user->save();

echo "Superadmin password reset to Admin@123\n";
