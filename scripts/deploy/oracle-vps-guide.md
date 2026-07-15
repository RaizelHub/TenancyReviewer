# Deployment Guide: Oracle Cloud Always Free VPS (Ubuntu)

This guide walks you through setting up and deploying this multi-tenant Laravel application to an **Oracle Cloud Infrastructure (OCI) "Always Free" VM instance**.

---

## Step 1: Sign up & Provision the OCI VM Instance

1. Sign up for an [Oracle Cloud Always Free Tier account](https://www.oracle.com/cloud/free/).
2. Once logged in to the Oracle Cloud Console, navigate to **Compute > Instances** and click **Create Instance**.
3. **Configure the Instance**:
   - **Name**: e.g., `reviewer-production`
   - **Placement**: Default Active Domain.
   - **Image**: Click *Edit* and select **Ubuntu 22.04 LTS** (or **Ubuntu 24.04**).
   - **Shape**: Click *Edit* and choose:
     - **Ampere (ARM)**: Recommended. Up to 4 OCPUs and 24 GB RAM are available in the Always Free tier.
     - *Alternative*: **AMD (Micro)**: `VM.Standard.E2.1.Micro` (1 OCPU, 1 GB RAM).
   - **Networking**: Keep defaults (this creates a Virtual Cloud Network/VCN and public IP).
   - **SSH Keys**: **Download** the generated Private Key file (`.key` or `.pem`). **Do not skip this step!**
   - **Boot Volume**: Default settings.
4. Click **Create** and wait for the Instance status to turn to **Running**. Copy the **Public IP Address**.

---

## Step 2: Configure Oracle Cloud Security Lists (Ingress Rules)

Oracle Cloud blocks all incoming traffic to your server by default (except SSH on port 22). You must allow web traffic in the cloud dashboard:

1. In the Instance details page, click on the link next to **Virtual Cloud Network** (e.g., `vcn-xxxx`).
2. In the subnet list, click on your public subnet (e.g., `subnet-xxxx`).
3. Click on the **Default Security List** for your VCN.
4. Click **Add Ingress Rules** and add the following two rules:

### Ingress Rule for HTTP (Port 80)
- **Source Type**: `CIDR`
- **Source CIDR**: `0.0.0.0/0`
- **IP Protocol**: `TCP`
- **Source Port Range**: `All`
- **Destination Port Range**: `80`
- **Description**: `Allow HTTP traffic`

### Ingress Rule for HTTPS (Port 443)
- **Source Type**: `CIDR`
- **Source CIDR**: `0.0.0.0/0`
- **IP Protocol**: `TCP`
- **Source Port Range**: `All`
- **Destination Port Range**: `443`
- **Description**: `Allow HTTPS traffic`

Click **Add Ingress Rules**.

---

## Step 3: Connect to the Server & Run the Setup Script

1. Open a terminal on your computer and set correct permissions for your downloaded private SSH key:
   ```bash
   chmod 400 /path/to/ssh-key.key
   ```
2. Connect to the VPS via SSH:
   ```bash
   ssh -i /path/to/ssh-key.key ubuntu@<YOUR_SERVER_PUBLIC_IP>
   ```
3. Upload the setup script `setup-oracle-vps.sh` from this project to the server, or copy its contents and save it in a file:
   ```bash
   nano setup-oracle-vps.sh
   # [Paste the script contents here and save: Ctrl+O, Enter, Ctrl+X]
   ```
4. Make the script executable and run it as root:
   ```bash
   chmod +x setup-oracle-vps.sh
   sudo ./setup-oracle-vps.sh
   ```
5. **CRITICAL**: The script will automatically install Nginx, PHP 8.3, MySQL, Node.js/NPM, Composer, and Supervisor. It will also unblock ports in the server's internal OS-level firewall (`iptables`). 
6. **Note the MySQL Database details printed at the end of the script!** You will need them for your `.env` file.

---

## Step 4: Configure Wildcard DNS

Multi-tenancy uses subdomains to separate tenant spaces. You must configure wildcard DNS records:

In your DNS provider (e.g., Cloudflare, GoDaddy, Namecheap):
1. Create an **A Record** pointing the root domain to your server:
   - **Type**: `A`
   - **Name**: `@`
   - **Value**: `<YOUR_SERVER_PUBLIC_IP>`
2. Create an **A Record** pointing wildcard subdomains to your server:
   - **Type**: `A`
   - **Name**: `*`
   - **Value**: `<YOUR_SERVER_PUBLIC_IP>`

---

## Step 5: Deploy the Code to `/var/www/reviewer`

1. On the server, clone your repository directly into the target folder (which was created and permissions-prepped by the setup script):
   ```bash
   sudo git clone https://github.com/your-username/your-repo.git /var/www/reviewer
   ```
2. Navigate to the project root:
   ```bash
   cd /var/www/reviewer
   ```
3. Set folder ownership to the web server user:
   ```bash
   sudo chown -R www-data:www-data /var/www/reviewer
   ```

---

## Step 6: Configure Environment & Install Dependencies

1. Create your production environment file:
   ```bash
   cp .env.example .env
   ```
2. Edit `.env` and fill out your details:
   ```bash
   nano .env
   ```
   **Key Configurations**:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL=https://yourdomain.com` (Use your actual domain name)
   - Database credentials (from Step 3 setup output):
     - `DB_DATABASE=reviewer_central`
     - `DB_USERNAME=reviewer_prod`
     - `DB_PASSWORD=<GENERATED_PASSWORD>`
   - Save and exit.

3. Install PHP Dependencies:
   ```bash
   composer install --no-dev --optimize-autoloader
   ```
4. Generate the Application Encryption Key:
   ```bash
   php artisan key:generate
   ```
5. Install Frontend Dependencies & compile assets:
   ```bash
   npm install
   npm run build
   ```
6. Run Central Database Migrations:
   ```bash
   php artisan migrate --force
   ```
7. Create the storage symlink:
   ```bash
   php artisan storage:link
   ```
8. Enforce folder permissions for storage and caches:
   ```bash
   sudo chmod -R 775 storage bootstrap/cache
   sudo chown -R www-data:www-data storage bootstrap/cache
   ```

---

## Step 7: Configure Nginx Wildcard Site

1. Copy the provided Nginx configuration to Nginx's sites-available:
   ```bash
   sudo cp scripts/deploy/nginx-wildcard.conf /etc/nginx/sites-available/reviewer.conf
   ```
2. Open `/etc/nginx/sites-available/reviewer.conf` and change `example.com` and `*.example.com` to your actual domain name:
   ```bash
   sudo nano /etc/nginx/sites-available/reviewer.conf
   ```
3. Enable the configuration and restart Nginx:
   ```bash
   sudo ln -s /etc/nginx/sites-available/reviewer.conf /etc/nginx/sites-enabled/
   sudo nginx -t # Test configuration syntax
   sudo systemctl restart nginx
   ```

---

## Step 8: Get a Wildcard SSL Certificate (HTTPS)

For wildcard domains, Let's Encrypt requires **DNS-01 verification** (verifying ownership by creating a TXT record in your DNS settings) instead of the standard HTTP challenge:

1. Run Certbot to request a wildcard certificate:
   ```bash
   sudo certbot certonly --manual --preferred-challenges=dns -d "yourdomain.com" -d "*.yourdomain.com"
   ```
2. Follow the prompt instructions:
   - Certbot will output one or more **TXT records** that you must add to your DNS provider (e.g. Host name: `_acme-challenge.yourdomain.com`, Value: `long_hash_string`).
   - Wait 1-2 minutes after adding the DNS TXT record for propagation, then press Enter in the terminal to verify.
3. Once generated, open your Nginx config to point to the new SSL certificates:
   ```bash
   sudo nano /etc/nginx/sites-available/reviewer.conf
   ```
   Uncomment and insert/modify the SSL rules (Certbot usually creates a separate block or can assist with this, or you can manually add the standard ssl server block referencing the generated files under `/etc/letsencrypt/live/yourdomain.com/fullchain.pem` and `privkey.pem`).
4. Test and restart Nginx:
   ```bash
   sudo nginx -t
   sudo systemctl restart nginx
   ```

---

## Step 9: Configure Supervisor for Queue Workers

1. Copy the Supervisor configuration file to the supervisor directory:
   ```bash
   sudo cp supervisor-queue.conf /etc/supervisor/conf.d/laravel-queue.conf
   ```
2. Edit `/etc/supervisor/conf.d/laravel-queue.conf` to configure absolute paths:
   ```bash
   sudo nano /etc/supervisor/conf.d/laravel-queue.conf
   ```
   Update `/path/to/your/project/` with `/var/www/reviewer/`:
   ```ini
   command=php /var/www/reviewer/artisan queue:work --sleep=3 --tries=3 --max-time=3600
   stdout_logfile=/var/www/reviewer/storage/logs/worker.log
   ```
3. Update and run Supervisor:
   ```bash
   sudo supervisorctl reread
   sudo supervisorctl update
   sudo supervisorctl start laravel-queue:*
   ```

---

## Step 10: Run the Central Seeder (Optional)

If you have a seeder to pre-populate roles or central configuration, run it:
```bash
php artisan db:seed
```

Your Oracle Cloud production deployment is now complete and online!
