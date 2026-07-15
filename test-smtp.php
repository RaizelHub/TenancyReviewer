<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;

echo "Testing SMTP with settings:\n";
echo "Host: " . config('mail.mailers.smtp.host') . "\n";
echo "Port: " . config('mail.mailers.smtp.port') . "\n";
echo "Username: " . config('mail.mailers.smtp.username') . "\n";
echo "Encryption: " . config('mail.mailers.smtp.encryption') . "\n";
echo "From: " . config('mail.from.address') . "\n";
echo "Verify Peer: " . (config('mail.mailers.smtp.verify_peer') ? 'true' : 'false') . "\n\n";

try {
    Mail::raw('Test email from ReviewerCentral SMTP test script.', function ($message) {
        $message->to('raizelulquira@gmail.com')
                ->subject('SMTP Test - ReviewerCentral');
    });
    echo "SUCCESS: Mail sent successfully!\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "\nFull trace:\n" . $e->getTraceAsString() . "\n";
}
