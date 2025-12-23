# Deployment Guide - Sistem Absensi RFID

## Prerequisites

### Server Requirements
- **PHP**: 7.4 or higher
- **MySQL**: 5.7 or higher
- **Apache/Nginx**: Web server with mod_rewrite enabled
- **Composer**: For PHP dependencies (optional)
- **Node.js & NPM**: For Tailwind CSS compilation (optional)

### Recommended Hosting
- Shared Hosting (cPanel)
- VPS (Ubuntu, CentOS)
- Cloud Hosting (AWS, DigitalOcean, Google Cloud)

---

## Installation Steps

### 1. Clone or Download Repository

```bash
# Via Git
git clone https://github.com/yduwima/absen-rfid-new.git
cd absen-rfid-new

# Or download and extract ZIP file
```

### 2. Database Setup

#### A. Create Database

```sql
CREATE DATABASE absensi_rfid CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### B. Import Database Schema

**Method 1: Using MySQL Command Line**
```bash
mysql -u root -p absensi_rfid < database.sql
```

**Method 2: Using phpMyAdmin**
1. Open phpMyAdmin
2. Select `absensi_rfid` database
3. Click "Import" tab
4. Choose `database.sql` file
5. Click "Go"

#### C. Verify Tables Created

Check that all 24 tables have been created:
- users
- siswa
- guru
- kelas
- tahun_ajaran
- semester
- mata_pelajaran
- jadwal_pelajaran
- wali_kelas
- guru_piket
- pengaturan_sekolah
- pengaturan_jam_kerja
- hari_kerja
- hari_libur
- absensi_harian
- absensi_mapel
- jurnal_guru
- izin_siswa
- wa_setting
- wa_template
- wa_queue
- wa_notif_kelas
- monitoring_bk
- surat_bk

### 3. Configuration

#### A. Copy Environment File

```bash
cp .env.example .env
```

#### B. Edit .env File

```bash
nano .env
```

Update the following values:

```env
# Database Configuration
DB_HOSTNAME=localhost
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
DB_DATABASE=absensi_rfid
DB_DRIVER=mysqli

# Base URL (IMPORTANT!)
BASE_URL=http://yourdomain.com/

# Or for subdirectory
BASE_URL=http://yourdomain.com/absen-rfid-new/

# Encryption Key (Generate random 32 characters)
ENCRYPTION_KEY=your-32-character-random-key-here

# WhatsApp API (Configure later)
WA_API_URL=https://api.whatsapp.com/send
WA_API_KEY=your-api-key-here
WA_SENDER=628123456789
```

#### C. Update CodeIgniter Config

Edit `application/config/config.php`:

```php
$config['base_url'] = 'http://yourdomain.com/';
$config['encryption_key'] = 'your-32-character-random-key-here';
```

Edit `application/config/database.php`:

```php
$db['default'] = array(
    'hostname' => 'localhost',
    'username' => 'your_db_user',
    'password' => 'your_db_password',
    'database' => 'absensi_rfid',
    'dbdriver' => 'mysqli',
    // ... other settings
);
```

### 4. Set File Permissions

```bash
# For Linux/Unix
chmod -R 755 application/
chmod -R 777 assets/uploads/
chmod -R 777 application/logs/
chmod -R 777 application/cache/

# Or using specific user (www-data for Apache)
chown -R www-data:www-data application/
chown -R www-data:www-data assets/
```

### 5. Apache Configuration

#### A. Enable mod_rewrite

```bash
# Ubuntu/Debian
sudo a2enmod rewrite
sudo systemctl restart apache2

# CentOS/RHEL
# Usually enabled by default
```

#### B. Update .htaccess

The `.htaccess` file is already included. Update `RewriteBase` if installing in subdirectory:

```apache
RewriteBase /
# Or for subdirectory:
RewriteBase /absen-rfid-new/
```

#### C. Virtual Host (Optional)

Create `/etc/apache2/sites-available/absensi.conf`:

```apache
<VirtualHost *:80>
    ServerName absensi.yourdomain.com
    DocumentRoot /var/www/html/absen-rfid-new
    
    <Directory /var/www/html/absen-rfid-new>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/absensi_error.log
    CustomLog ${APACHE_LOG_DIR}/absensi_access.log combined
</VirtualHost>
```

Enable and restart:

```bash
sudo a2ensite absensi.conf
sudo systemctl restart apache2
```

### 6. Nginx Configuration (Alternative)

Create `/etc/nginx/sites-available/absensi`:

```nginx
server {
    listen 80;
    server_name absensi.yourdomain.com;
    root /var/www/html/absen-rfid-new;
    
    index index.php index.html;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~ /\.ht {
        deny all;
    }
}
```

Enable and restart:

```bash
sudo ln -s /etc/nginx/sites-available/absensi /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### 7. Tailwind CSS Compilation (Optional)

If you want to customize Tailwind CSS:

```bash
# Install dependencies
npm install

# Build for production
npm run build

# Or watch for changes during development
npm run dev
```

The application will work fine with CDN version without compilation.

### 8. Test Installation

#### A. Access Application

Open browser and navigate to:
```
http://yourdomain.com/
```

You should see the login page.

#### B. Test Login

Use default admin account:
- **Username**: admin
- **Password**: password

#### C. Test RFID Page

Navigate to:
```
http://yourdomain.com/rfid
```

Test with sample UID: `RFID-SISWA-001`

---

## Post-Installation Setup

### 1. Change Default Passwords

**IMPORTANT**: Change all default passwords immediately!

```sql
-- Login to MySQL
mysql -u root -p absensi_rfid

-- Update admin password
-- Generate new hash with: password_hash('your_new_password', PASSWORD_DEFAULT)
UPDATE users SET password = '$2y$10$NEW_PASSWORD_HASH_HERE' WHERE username = 'admin';
```

### 2. Configure School Settings

1. Login as admin
2. Go to **Pengaturan → Data Sekolah**
3. Update:
   - School name
   - Address
   - Phone/Email
   - Upload school logo
   - Principal name and ID

### 3. Configure Working Hours

1. Go to **Pengaturan → Jam Kerja**
2. Set:
   - Working hours (start/end time)
   - Late tolerance (in minutes)
   - Working days (Monday-Saturday)
   - Add national holidays

### 4. Setup Academic Year

1. Go to **Data Master → Tahun Ajaran**
2. Create new academic year (e.g., 2024/2025)
3. Activate it
4. Create semesters (Ganjil/Genap)

### 5. Add Master Data

Add in this order:
1. **Kelas** (Classes)
2. **Guru** (Teachers)
3. **Siswa** (Students)
4. **Mata Pelajaran** (Subjects)
5. **Jadwal Pelajaran** (Schedule)
6. **Wali Kelas** (Homeroom Teachers)

### 6. Configure WhatsApp Notifications

1. Sign up for WhatsApp Business API
2. Get API credentials
3. Update `.env` and `wa_setting` table
4. Configure message templates
5. Select classes for notifications

### 7. Setup Cron Jobs

Add to crontab:

```bash
crontab -e
```

Add these lines:

```cron
# Process WhatsApp queue every minute
* * * * * php /var/www/html/absen-rfid-new/index.php cron/process_wa_queue >> /var/log/wa_queue.log 2>&1

# Send notification for absent students at 9 AM
0 9 * * * php /var/www/html/absen-rfid-new/index.php cron/notif_siswa_alpha >> /var/log/notif_alpha.log 2>&1

# Daily database backup at 2 AM
0 2 * * * mysqldump -u root -pPASSWORD absensi_rfid > /backup/absensi_$(date +\%Y\%m\%d).sql
```

---

## RFID Hardware Setup

### Recommended Hardware

1. **RFID RC522 Module**
2. **ESP8266 NodeMCU** or **ESP32**
3. **RFID Cards/Tags** (13.56MHz)
4. **Power Supply** (5V)
5. **Jumper Wires**

### Wiring Diagram

```
RC522    ESP8266
-------------------
SDA   -> D8
SCK   -> D5
MOSI  -> D7
MISO  -> D6
IRQ   -> (Not connected)
GND   -> GND
RST   -> D0
3.3V  -> 3.3V
```

### Arduino Code

See `API_DOCUMENTATION.md` for complete Arduino code example.

### Upload to ESP8266

1. Install Arduino IDE
2. Add ESP8266 board support
3. Install MFRC522 library
4. Update WiFi credentials and server URL
5. Upload code

---

## SSL/HTTPS Setup (Recommended)

### Using Let's Encrypt (Free)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache

# Get certificate
sudo certbot --apache -d absensi.yourdomain.com

# Auto-renewal
sudo certbot renew --dry-run
```

Update `BASE_URL` in config to use HTTPS:
```php
$config['base_url'] = 'https://absensi.yourdomain.com/';
```

---

## Backup Strategy

### 1. Database Backup

**Manual:**
```bash
mysqldump -u root -p absensi_rfid > backup_$(date +%Y%m%d).sql
```

**Automated (Daily):**
```bash
# Add to crontab
0 2 * * * /usr/bin/mysqldump -u root -pPASSWORD absensi_rfid | gzip > /backup/db_$(date +\%Y\%m\%d).sql.gz
```

### 2. Files Backup

```bash
# Backup uploads directory
tar -czf uploads_backup_$(date +%Y%m%d).tar.gz assets/uploads/

# Full application backup
tar -czf app_backup_$(date +%Y%m%d).tar.gz /var/www/html/absen-rfid-new/
```

---

## Troubleshooting

### 1. Database Connection Error

**Error**: "Unable to connect to database"

**Solution**:
- Check database credentials in `config/database.php`
- Verify MySQL service is running: `sudo systemctl status mysql`
- Check database user permissions

### 2. Blank Page / 500 Error

**Solution**:
- Check PHP error logs: `/var/log/apache2/error.log`
- Enable error display temporarily:
  ```php
  // index.php
  define('ENVIRONMENT', 'development');
  ```
- Check file permissions

### 3. CSS Not Loading

**Solution**:
- Check `base_url` in config matches your domain
- Clear browser cache
- Check `.htaccess` is present
- Build Tailwind CSS: `npm run build`

### 4. RFID Not Working

**Solution**:
- Check CSRF exception in config: `csrf_exclude_uris`
- Test API endpoint with Postman
- Verify ESP8266 WiFi connection
- Check server URL in Arduino code

### 5. Login Redirects to Blank Page

**Solution**:
- Check session configuration
- Verify `sess_save_path` is writable
- Check cookies are enabled in browser

---

## Security Checklist

- [ ] Change all default passwords
- [ ] Generate unique encryption key
- [ ] Enable HTTPS/SSL
- [ ] Restrict database user permissions
- [ ] Disable directory listing
- [ ] Keep software updated
- [ ] Regular backups
- [ ] Implement rate limiting on RFID endpoint
- [ ] Monitor error logs
- [ ] Use strong passwords for all accounts

---

## Maintenance

### Regular Tasks

**Daily:**
- Check system logs
- Monitor disk space
- Verify backups completed

**Weekly:**
- Review error logs
- Check database integrity
- Test RFID system

**Monthly:**
- Update software packages
- Review user accounts
- Clean old logs

---

## Support

For issues and questions:
- GitHub Issues: https://github.com/yduwima/absen-rfid-new/issues
- Documentation: See README.md and API_DOCUMENTATION.md

---

**Last Updated:** December 23, 2024  
**Version:** 1.0.0
