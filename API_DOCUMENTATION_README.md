# 📚 API Documentation - Paageming Marketplace

Kumpulan lengkap dokumentasi API untuk project Paageming Marketplace. Semua endpoint sudah diimplementasi dan dapat ditest melalui Swagger UI atau tools lain.

## 📋 Daftar File Dokumentasi

### 🎯 Quick Access
| File | Deskripsi | Penggunaan |
|------|-----------|------------|
| **`API_QUICK_REFERENCE.md`** | Referensi cepat API | Development, testing harian |
| **`ENDPOINTS.md`** | Daftar semua endpoint | Overview lengkap API |
| **`ENDPOINTS_TABLE.md`** | Tabel endpoint lengkap | Referensi tim, dokumentasi |
| **`ENDPOINTS_DETAIL.md`** | Detail setiap endpoint | Testing, implementasi client |

### 🗄️ Database Documentation
| File | Deskripsi | Lokasi |
|------|-----------|--------|
| **ERD Documentation** | Entity Relationship Diagram | `database/erd/` |
| **Database Schema** | SQL schema & migration | `database/erd/` |
| **DBML Files** | dbdiagram.io format | `database/erd/` |

## 📖 Cara Penggunaan

### 1. Untuk Developer Baru
**Mulai dengan**: `API_QUICK_REFERENCE.md`
- Setup cepat
- Test accounts
- Basic commands
- Troubleshooting

### 2. Untuk Testing
**Gunakan**: `ENDPOINTS_DETAIL.md`
- Request/response examples
- Parameter details
- Error handling
- cURL commands

### 3. Untuk Dokumentasi Tim
**Gunakan**: `ENDPOINTS_TABLE.md`
- Tabel lengkap endpoint
- Status implementasi
- Role-based access
- Quick reference table

### 4. Untuk Overview Project
**Gunakan**: `ENDPOINTS.md`
- Daftar endpoint implemented
- Planned features
- Architecture overview

## 🚀 Quick Start

### 1. Start Local Server
```bash
cd c:\laragon\www\paageming
php artisan serve
```

### 2. Access Documentation
- **Swagger UI**: http://localhost:8000/api/documentation
- **API Info**: http://localhost:8000/

### 3. Test Authentication
```bash
# Login admin
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'
```

### 4. Test CRUD
```bash
# Get categories
curl http://localhost:8000/api/categories

# Get products  
curl http://localhost:8000/api/products
```

## 🔑 Test Accounts

| Role | Email | Password | Access |
|------|-------|----------|--------|
| Admin | `admin@example.com` | `password` | All endpoints |
| User | `user@example.com` | `password` | User endpoints |

## 📊 Implementation Status

### ✅ Implemented & Tested
- **Authentication**: Register, login, logout
- **Categories**: Full CRUD operations
- **Products**: Full CRUD operations
- **Users**: List management (admin)
- **Upload**: Image upload (admin)
- **Documentation**: Swagger UI

### 🔄 Planned Features
- Cart management
- Order processing
- User profiles
- Reviews & ratings
- Advanced search
- Admin dashboard

## 🛠️ Technical Stack

### Backend
- **Framework**: Laravel 11
- **Database**: Firebase Realtime Database
- **Authentication**: Laravel Sanctum
- **Documentation**: L5-Swagger
- **Deployment**: Railway

### Frontend Integration
- **API Format**: RESTful JSON
- **Authentication**: Bearer Token
- **CORS**: Configured for all origins
- **File Upload**: Multipart form data

## 📁 File Structure

```
paageming/
├── API_QUICK_REFERENCE.md      # Quick development guide
├── ENDPOINTS.md                # Complete endpoint list
├── ENDPOINTS_TABLE.md          # Tabular format
├── ENDPOINTS_DETAIL.md         # Detailed specifications
├── routes/
│   ├── api.php                 # API routes
│   └── web.php                 # Web routes
├── app/Http/Controllers/Api/   # API controllers
├── database/erd/               # Database documentation
└── storage/api-docs/           # Generated Swagger docs
```

## 🌐 URLs

### Local Development
- **API Base**: http://localhost:8000
- **Swagger**: http://localhost:8000/api/documentation
- **Root Info**: http://localhost:8000/

### Production (Railway)
- **API Base**: https://your-app.railway.app
- **Swagger**: https://your-app.railway.app/api/documentation
- **Root Info**: https://your-app.railway.app/

## 🧪 Testing Tools

### Recommended Tools
1. **Swagger UI** - Built-in documentation
2. **Postman** - Import from Swagger JSON
3. **Insomnia** - API testing
4. **VS Code REST Client** - Quick testing
5. **cURL** - Command line testing

### Import Swagger
URL: `http://localhost:8000/docs/api-docs.json`

## 🔧 Configuration

### Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Configure database
DB_CONNECTION=sqlite

# Configure Firebase
FIREBASE_CREDENTIALS=storage/app/firebase-credentials.json
```

### Laravel Commands
```bash
# Generate Swagger docs
php artisan l5-swagger:generate

# Seed Firebase data
php artisan firebase:seed

# Clear cache
php artisan config:clear
php artisan cache:clear
```

## 📞 Support

### Development Issues
1. Check `API_QUICK_REFERENCE.md` for common issues
2. Verify environment configuration
3. Check Laravel logs in `storage/logs/`
4. Test Firebase connection

### API Issues
1. Verify authentication token
2. Check request format (JSON)
3. Validate required parameters
4. Check user permissions

### Documentation Updates
- Swagger docs auto-generate from annotations
- Manual docs in this folder need manual updates
- ERD files in `database/erd/` folder

## 🎯 Next Steps

### For Developers
1. Review `API_QUICK_REFERENCE.md`
2. Test basic endpoints
3. Implement client integration
4. Handle error responses

### For Product Team
1. Review `ENDPOINTS_TABLE.md`
2. Plan additional features
3. Define business requirements
4. Test user flows

### For DevOps
1. Configure Railway deployment
2. Set production environment variables
3. Monitor API performance
4. Setup logging and monitoring

## 📝 Notes

- All endpoints return JSON responses
- Authentication uses Bearer tokens
- File uploads limited to 2MB
- CORS enabled for all origins
- Rate limiting not implemented (can be added)
- Database uses Firebase (no SQL migrations)

---

**Last Updated**: January 2024  
**Version**: 1.0.0  
**Status**: Production Ready ✅
