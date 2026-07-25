# 🚀 Panduan Deploy MTI ke Hostinger

## Info Server
- **Domain:** https://mti.hvmdigital.id
- **Folder Project:** /home/u664715641/domains/mti.hvmdigital.id/public_html/
- **PHP:** 8.2 (SSH), 8.3 (Web)
- **Database:** u664715641_MTI

## ⚠️ Penting Sebelum Deploy
- Password DB mengandung `#`, wajib pakai tanda kutip: `DB_PASSWORD="#Ilhammaulana23"`
- PHP SSH default 8.2, pakai flag `--ignore-platform-reqs` saat composer
- Project ada di `domains/mti.hvmdigital.id/public_html/` BUKAN `public_html/`
- Document root Hostinger mengarah ke folder `public/`, perlu `.htaccess` redirect

## 📋 Langkah Deploy

### 1. Masuk folder project
```bash
cd /home/u664715641/domains/mti.hvmdigital.id/public_html/
```

### 2. Setup .env
```bash
nano .env
```
Isi dengan:
```env
APP_NAME="Musabaqah Tarikh Islam"
APP_ENV=production
APP_KEY=base64:fneoowFN6gszGuItvHNykV24IzNtUQqnKkiDbIunEmg=
APP_DEBUG=false
APP_URL=https://mti.hvmdigital.id
APP_TIMEZONE=Asia/Jakarta
APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_FAKER_LOCALE=id_ID
APP_MAINTENANCE_DRIVER=file
BCRYPT_ROUNDS=12
LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u664715641_MTI
DB_USERNAME=u664715641_MTI
DB_PASSWORD="#Ilhammaulana23"
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
CACHE_STORE=file
MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@musabaqahtarikhislam.com"
MAIL_FROM_NAME="${APP_NAME}"
VITE_APP_NAME="${APP_NAME}"
```

### 3. Install dependencies
```bash
composer install --no-dev --optimize-autoloader --ignore-platform-reqs
```

### 4. Setup .htaccess di root project
```bash
nano .htaccess
```
Isi:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### 5. Storage symlink
```bash
rm -f public/storage
ln -s /home/u664715641/domains/mti.hvmdigital.id/public_html/storage/app/public public/storage
```
# 5b. Fix permission storage
chmod -R 775 storage
chmod -R 775 public/storage

### 6. Import database
```bash
mysql -u u664715641_MTI -p"#Ilhammaulana23" u664715641_MTI < database/mi_raudlatul_ulum.sql
```

### 7. Jalankan artisan
```bash
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
php artisan db:seed --force
```

## ✅ Cek Akhir
- Buka https://mti.hvmdigital.id — website muncul
- Foto/gambar tampil (storage link benar)
- Login organizer bisa masuk
- Login peserta bisa masuk