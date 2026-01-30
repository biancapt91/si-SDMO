# Deployment Checklist

## Sebelum Deploy

- [ ] Set `APP_ENV=production` di .env
- [ ] Set `APP_DEBUG=false` di .env
- [ ] Generate APP_KEY baru: `php artisan key:generate`
- [ ] Update APP_URL dengan domain production
- [ ] Setup database production
- [ ] Test semua fitur di local
- [ ] Backup database

## Optimasi Production

```bash
# Install dependencies production
composer install --optimize-autoloader --no-dev

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize
php artisan optimize
```

## File .env Production

```env
APP_NAME="Career Map"
APP_ENV=production
APP_KEY=base64:xxx # Generate baru
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=daily
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=careermap_production
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

## Security

- [ ] Gunakan HTTPS/SSL
- [ ] Set permission yang benar (755 untuk folders, 644 untuk files)
- [ ] Protect .env file
- [ ] Disable directory listing
- [ ] Setup firewall
- [ ] Backup rutin database

## Setelah Deploy

- [ ] Test semua endpoint
- [ ] Test login/register
- [ ] Test upload file
- [ ] Test import/export Excel
- [ ] Monitor error logs
- [ ] Setup monitoring (optional)

## Update/Maintenance

```bash
# Pull changes
git pull origin main

# Update dependencies
composer install --no-dev

# Run migrations
php artisan migrate --force

# Clear & rebuild cache
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Troubleshooting

### Error 500
- Cek file permissions
- Cek .env configuration
- Cek error logs: `storage/logs/laravel.log`

### Database Connection Error
- Verifikasi credentials di .env
- Cek apakah database sudah dibuat
- Test connection dari server

### Assets tidak load
- Run: `php artisan storage:link`
- Cek APP_URL di .env
- Build assets: `npm run build` (jika ada)
