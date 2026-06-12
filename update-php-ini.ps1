# Path to the php.ini file
$phpIniPath = "C:\xampp\php\php.ini"

# Read the current content
$content = Get-Content -Path $phpIniPath -Raw

# Update curl.cainfo
if ($content -match "curl\.cainfo\s*=") {
    $content = $content -replace "curl\.cainfo\s*=.*", "curl.cainfo = `"C:\xampp\php\extras\ssl\cacert.pem`""
} else {
    # Add the setting if it doesn't exist
    $content += "`n[curl]`ncurl.cainfo = `"C:\xampp\php\extras\ssl\cacert.pem`"`n"
}

# Update openssl.cafile
if ($content -match "openssl\.cafile\s*=") {
    $content = $content -replace "openssl\.cafile\s*=.*", "openssl.cafile = `"C:\xampp\php\extras\ssl\cacert.pem`""
} else {
    # Add the setting if it doesn't exist
    $content += "`n[openssl]`nopenssl.cafile = `"C:\xampp\php\extras\ssl\cacert.pem`"`n"
}

# Write the updated content back to the file
$content | Set-Content -Path $phpIniPath

Write-Host "php.ini has been updated successfully."
