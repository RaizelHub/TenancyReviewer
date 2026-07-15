#!/bin/bash

# Exit immediately if a command exits with a non-zero status
set -e

# Logging functions
log_info() {
    echo -e "\e[34m[INFO]\e[0m $1"
}

log_success() {
    echo -e "\e[32m[SUCCESS]\e[0m $1"
}

log_error() {
    echo -e "\e[31m[ERROR]\e[0m $1"
}

# Run as root check
if [ "$EUID" -ne 0 ]; then
    log_error "Please run this script as root or with sudo."
    exit 1
fi

log_info "Starting Oracle Cloud Always Free VPS (Ubuntu 22.04/24.04) Setup"

# 1. Update and Upgrade System
log_info "Updating system packages..."
apt-get update -y
apt-get upgrade -y

# Install common utilities
apt-get install -y software-properties-common curl git unzip zip ufw iptables-persistent

# 2. Add PHP Ondřej Surý PPA
log_info "Adding PHP repository..."
add-apt-repository ppa:ondrej/php -y
apt-get update -y

# 3. Install PHP 8.3 & Extensions
log_info "Installing PHP 8.3 and extensions..."
apt-get install -y php8.3 php8.3-cli php8.3-fpm php8.3-mysql php8.3-curl \
    php8.3-xml php8.3-mbstring php8.3-zip php8.3-bcmath php8.3-sqlite3 \
    php8.3-gd php8.3-intl php8.3-opcache php8.3-soap

# 4. Install Nginx
log_info "Installing Nginx..."
apt-get install -y nginx

# 5. Install MySQL Server
log_info "Installing MySQL Server..."
apt-get install -y mysql-server

# 6. Install Node.js & NPM (NodeSource LTS)
log_info "Installing Node.js (V20 LTS)..."
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt-get install -y nodejs

# 7. Install Composer globally
log_info "Installing Composer..."
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

# 8. Install Certbot (for SSL certificates)
log_info "Installing Certbot..."
apt-get install -y certbot python3-certbot-nginx

# 9. Install Supervisor (for Laravel Queue worker)
log_info "Installing Supervisor..."
apt-get install -y supervisor

# 10. Configure MySQL Databases and Users
log_info "Configuring MySQL database and credentials..."
DB_PASS=$(openssl rand -base64 16)
DB_USER="reviewer_prod"
DB_NAME="reviewer_central"

# Run MySQL commands
mysql -e "CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -e "CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';"
mysql -e "GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'localhost';"
# Crucial for multi-tenancy: Allow user to create dynamic tenant databases
mysql -e "GRANT CREATE, DROP, ALTER, REFERENCES, INDEX ON *.* TO '${DB_USER}'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"

# 11. Open Ports in Local OS Firewall (CRITICAL ORACLE VPS GOTCHA)
log_info "Configuring Oracle OS Firewall (iptables & UFW)..."

# Allow HTTP and HTTPS in UFW
ufw allow 'Nginx Full'
ufw allow 22/tcp

# Force opening ports in iptables (since Oracle Ubuntu defaults blocks external incoming traffic)
iptables -I INPUT -p tcp --dport 80 -j ACCEPT
iptables -I INPUT -p tcp --dport 443 -j ACCEPT

# Save iptables rules so they persist on reboot
netfilter-persistent save
netfilter-persistent reload

# 12. Create Project Directory
log_info "Preparing project directories..."
mkdir -p /var/www/reviewer
chown -R www-data:www-data /var/www/reviewer
chmod -R 775 /var/www/reviewer

# Output Setup Summary
echo ""
echo "====================================================================="
echo "                SETUP COMPLETED SUCCESSFULLY"
echo "====================================================================="
echo "You can now configure your application. Here are your credentials:"
echo ""
echo "  MySQL Host:     127.0.0.1"
echo "  MySQL Central DB: ${DB_NAME}"
echo "  MySQL User:     ${DB_USER}"
echo "  MySQL Password: ${DB_PASS}"
echo ""
echo "  PHP Version:    PHP 8.3"
echo "  Web Directory:  /var/www/reviewer"
echo "====================================================================="
echo "Next Steps:"
echo "1. Set up ingress rules (ports 80 & 443) in your Oracle Cloud Console dashboard."
echo "2. Clone your repository into /var/www/reviewer"
echo "3. Copy /var/www/reviewer/scripts/deploy/nginx-wildcard.conf to /etc/nginx/sites-available/"
echo "4. Create a symbolic link: ln -s /etc/nginx/sites-available/nginx-wildcard.conf /etc/nginx/sites-enabled/"
echo "5. Run Certbot to generate wildcard SSL certificates."
echo "====================================================================="
