# 🚀 Deploy ke Railway.app (5 Menit!)

## Langkah 1: Persiapan (1 menit)

### Push ke GitHub
```bash
# Buat repository baru di GitHub, lalu:
git init
git add .
git commit -m "Initial commit"
git branch -M main
git remote add origin https://github.com/username/CareerMap.git
git push -u origin main
```

## Langkah 2: Deploy ke Railway (3 menit)

### A. Buat Akun & Project
1. Buka https://railway.app
2. Klik **"Start a New Project"**
3. Login dengan GitHub
4. Pilih **"Deploy from GitHub repo"**
5. Pilih repository **CareerMap**

### B. Tambah MySQL Database
1. Klik **"+ New"** → **"Database"** → **"Add MySQL"**
2. Railway akan otomatis buat database

### C. Set Environment Variables
1. Klik service aplikasi Laravel
2. Pilih tab **"Variables"**
3. Tambahkan variable berikut:

```env
APP_NAME=CareerMap
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:GENERATE_INI_NANTI

# Database (akan otomatis terisi dari MySQL service)
# MYSQLHOST, MYSQLPORT, MYSQLDATABASE, MYSQLUSER, MYSQLPASSWORD
# sudah otomatis dari Railway

# Session & Cache
SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

### D. Generate APP_KEY
1. Di Railway, buka **"Settings"** → **"Variables"**
2. Generate APP_KEY dengan perintah ini di local:
```bash
php artisan key:generate --show
```
3. Copy hasilnya (contoh: `base64:xxxxxxxxxxxxx`)
4. Paste ke variable `APP_KEY` di Railway

### E. Connect Database ke Laravel
Railway sudah menyediakan variable database, tapi Laravel butuh format berbeda:

Tambahkan variable ini (Railway sudah isi nilai otomatis):
```
DB_CONNECTION=mysql
DB_HOST=${MYSQLHOST}
DB_PORT=${MYSQLPORT}
DB_DATABASE=${MYSQLDATABASE}
DB_USERNAME=${MYSQLUSER}
DB_PASSWORD=${MYSQLPASSWORD}
```

Atau bisa langsung isi manual dari info MySQL service Railway.

## Langkah 3: Deploy Otomatis! (1 menit)

Railway akan otomatis:
- ✅ Install dependencies
- ✅ Run migrations
- ✅ Build aplikasi
- ✅ Generate public URL

Tunggu hingga status **"Success"** ✨

## Langkah 4: Akses Aplikasi

1. Klik **"Settings"** → cari **"Public URL"**
2. Buka URL tersebut: `https://careermap-production.up.railway.app`
3. Selesai! 🎉

## Update Aplikasi

Setiap kali push ke GitHub, Railway otomatis deploy ulang:
```bash
git add .
git commit -m "Update feature"
git push origin main
```

## Troubleshooting

### Error 500
Cek logs di Railway:
1. Klik service Laravel
2. Tab **"Deployments"**
3. Pilih deployment terakhir
4. Klik **"View Logs"**

### Database Connection Error
Pastikan variable database sudah terisi:
```
DB_HOST=${MYSQLHOST}
DB_PORT=${MYSQLPORT}
DB_DATABASE=${MYSQLDATABASE}
DB_USERNAME=${MYSQLUSER}
DB_PASSWORD=${MYSQLPASSWORD}
```

### Migration Error
Run manual di Railway CLI:
```bash
railway run php artisan migrate --force
```

### APP_KEY Missing
Generate di local lalu set di Railway Variables:
```bash
php artisan key:generate --show
```

## Biaya
- **FREE Plan**: 
  - $5 credit/bulan
  - 500 jam runtime
  - 1GB RAM
  - Cukup untuk testing & small production

- **Pro Plan** ($20/bulan):
  - Unlimited resources
  - Custom domains
  - Priority support

## Tips Pro

### Custom Domain
1. Beli domain (Namecheap, Cloudflare, dll)
2. Di Railway: Settings → Domains → Add Custom Domain
3. Update DNS CNAME record

### Monitoring
Railway menyediakan:
- Real-time logs
- Metrics (CPU, Memory, Network)
- Deployment history

### Environment per Branch
- Main branch → Production
- Dev branch → Staging
- Buat environment terpisah untuk masing-masing

## Alternatif Cepat Lainnya

Jika Railway tidak cocok:

### 1. **Vercel** (untuk frontend)
```bash
npm install -g vercel
vercel
```

### 2. **Render.com**
- Similar dengan Railway
- Free tier lebih generous
- Setup mirip

### 3. **Fly.io**
```bash
curl -L https://fly.io/install.sh | sh
fly launch
fly deploy
```

---

**Need Help?** 
Hubungi saya jika ada error atau butuh bantuan setup! 🚀
