<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

// Get all domains with port number
$domains = DB::table('domains')->where('domain', 'like', '%.localhost:8000')->get();

foreach ($domains as $domain) {
    $newDomain = str_replace(':8000', '', $domain->domain);
    DB::table('domains')
        ->where('id', $domain->id)
        ->update(['domain' => $newDomain]);
    
    echo "Updated domain: {$domain->domain} -> {$newDomain}\n";
}

echo "Done!\n"; 