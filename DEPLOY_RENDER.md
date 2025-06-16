# 🚀 Paageming - Marketplace Hasil Pertanian

## Deploy ke Render.com

### Langkah-langkah Deploy:

#### 1. **Persiapan Repository**
```bash
# Pastikan semua file sudah di commit
git add .
git commit -m "Prepare for Render deployment"
git push origin main
```

#### 2. **Setup di Render.com**
1. Kunjungi [render.com](https://render.com)
2. Daftar/Login dengan GitHub account
3. Connect GitHub repository `paageming`
4. Pilih "Web Service"

#### 3. **Konfigurasi Service**
- **Name**: `paageming-api`
- **Environment**: `PHP`
- **Build Command**: `chmod +x build.sh && ./build.sh`
- **Start Command**: `php artisan serve --host=0.0.0.0 --port=$PORT`
- **Instance Type**: Free tier

#### 4. **Environment Variables**
Tambahkan environment variables berikut di Render dashboard:

```
APP_NAME=Paageming
APP_ENV=production
APP_DEBUG=false
APP_KEY=[generate di Render atau gunakan php artisan key:generate]
APP_URL=[akan diisi otomatis oleh Render]
LOG_LEVEL=error
DB_CONNECTION=sqlite
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
FIREBASE_PROJECT_ID=paageming-marketplace
FIREBASE_DATABASE_URL=https://paageming-marketplace-default-rtdb.firebaseio.com/
FIREBASE_STORAGE_BUCKET=paageming-marketplace.appspot.com
L5_SWAGGER_GENERATE_ALWAYS=true
```

#### 5. **Firebase Credentials**
Upload file `firebase-credentials.json` ke dalam folder `storage/app/` di repository.

#### 6. **Deploy**
1. Klik "Create Web Service"
2. Tunggu proses build selesai (~5-10 menit)
3. Akses aplikasi di URL yang diberikan Render

### 🔗 **Endpoints Setelah Deploy**

- **API Base URL**: `https://your-app-name.onrender.com/api`
- **Swagger Documentation**: `https://your-app-name.onrender.com/api/documentation`
- **Test Pages**: 
  - `https://your-app-name.onrender.com/firebase-test.html`
  - `https://your-app-name.onrender.com/simple-login-test.html`

### 🧪 **Testing Setelah Deploy**

1. **Test Login API**:
```bash
curl -X POST https://your-app-name.onrender.com/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@panenku.com","password":"admin123"}'
```

2. **Test Create Product**:
```bash
curl -X POST https://your-app-name.onrender.com/api/products \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{"name":"Test Product","description":"Test Description","price":10000,"stock":100,"category_id":1}'
```

### 🔧 **Troubleshooting**

- **Build Failed**: Check logs di Render dashboard
- **Database Error**: Pastikan SQLite database ter-create dengan benar
- **Firebase Error**: Pastikan credentials file uploaded dan environment variables correct
- **Permission Error**: Build script akan set proper permissions

### 📚 **Documentation**

- **API Documentation**: Available at `/api/documentation`
- **Postman Collection**: Import dari Swagger export
- **Test Files**: Available in `/public/` directory

---

**Admin Login**:
- Email: `admin@panenku.com`
- Password: `admin123`
