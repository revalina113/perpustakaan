<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$users = User::where('role', 'siswa')->get();
$count = 0;
foreach ($users as $u) {
    $u->password = Hash::make($u->username);
    $u->must_change_password = true;
    $u->save();
    $count++;
}

echo "$count passwords reset (to username value)\n";
