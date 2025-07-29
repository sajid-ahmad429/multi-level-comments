# Complete Setup Guide - Optimized Laravel Livewire App

Ye guide follow karke aap optimized repository ko clone kar ke run kar sakte hain.

## 🎯 Repository Clone Karo

### Method 1: Complete Repository
```bash
git clone https://github.com/sajid-ahmad429/multi-level-comments.git
cd multi-level-comments
git checkout cursor/optimize-codebase-1582
```

### Method 2: Direct Optimized Branch
```bash
git clone -b cursor/optimize-codebase-1582 https://github.com/sajid-ahmad429/multi-level-comments.git optimized-app
cd optimized-app
```

## 🔧 Development Setup

### 1. PHP Dependencies Install Karo
```bash
composer install
```

### 2. Frontend Dependencies Install Karo
```bash
npm install
```

### 3. Environment File Setup
```bash
# Production environment copy karo
cp .env.production .env

# Ya phir development ke liye basic setup
cp .env.example .env
```

### 4. Application Key Generate Karo
```bash
php artisan key:generate
```

### 5. Database Setup
```bash
# SQLite database file banao
touch database/database.sqlite

# Migrations run karo (optimization indexes included)
php artisan migrate

# Sample data add karo (optional)
php artisan db:seed
```

### 6. Storage Link Create Karo
```bash
php artisan storage:link
```

### 7. Frontend Build Karo
```bash
# Development build
npm run dev

# Production build (optimized)
npm run build:production
```

## 🐳 Docker Setup (Recommended for Production)

### 1. Docker Image Build Karo
```bash
# Multi-stage optimized build
docker build -t laravel-optimized .
```

### 2. Container Run Karo
```bash
docker run -p 8080:8080 laravel-optimized
```

### 3. Docker Compose Use Karo (with Redis)
```bash
# docker-compose.yml file banao
cat > docker-compose.yml << 'EOF'
version: '3.8'
services:
  app:
    build: .
    ports:
      - "8080:8080"
    depends_on:
      - redis
    environment:
      - REDIS_HOST=redis
      - CACHE_STORE=redis
      - SESSION_DRIVER=redis
      - QUEUE_CONNECTION=redis

  redis:
    image: redis:7-alpine
    ports:
      - "6379:6379"
    command: redis-server --appendonly yes
    volumes:
      - redis_data:/data

volumes:
  redis_data:
EOF

# Services start karo
docker-compose up -d
```

## ⚡ Laravel Optimization Commands

Production mein deploy karne se pehle ye commands run karo:

```bash
# Configuration cache
php artisan config:cache

# Route cache
php artisan route:cache

# View cache
php artisan view:cache

# Complete optimization
php artisan optimize

# Clear all caches (development mein)
php artisan optimize:clear
```

## 🔄 Redis Setup (Local Development)

### Ubuntu/Debian
```bash
sudo apt update
sudo apt install redis-server
sudo systemctl start redis-server
sudo systemctl enable redis-server
```

### macOS
```bash
brew install redis
brew services start redis
```

### Windows
```bash
# Windows Subsystem for Linux use karo ya Docker
docker run -d -p 6379:6379 redis:alpine
```

## 📝 Environment Variables (Development)

`.env` file mein ye settings add karo:

```env
# Basic Laravel Settings
APP_NAME="Laravel Livewire App"
APP_ENV=local
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database (SQLite for development)
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite

# Redis Configuration
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Cache Settings
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Mail (for testing)
MAIL_MAILER=log
```

## 🚀 Development Server Start Karo

### Option 1: Laravel Artisan
```bash
php artisan serve
# Application available at: http://localhost:8000
```

### Option 2: Composer Dev Script
```bash
composer run dev
# Ye automatically server, queue worker, aur vite start kar dega
```

### Option 3: Separate Terminals
```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Queue worker
php artisan queue:work

# Terminal 3: Frontend dev server
npm run dev
```

## 🧪 Testing Setup

### PHPUnit Tests Run Karo
```bash
# Basic tests
php artisan test

# With coverage
composer run test
```

### Frontend Tests (if any)
```bash
npm run test
```

## 📊 Performance Verification

### 1. Check OPcache Status
```bash
php -i | grep opcache
```

### 2. Redis Connection Test
```bash
redis-cli ping
# Should return: PONG
```

### 3. Database Query Performance
```bash
# Enable query log temporarily
DB_LOG_QUERIES=true php artisan serve
```

### 4. Frontend Build Verification
```bash
# Check optimized build
npm run build:production
ls -la public/build/
```

## 🔧 Troubleshooting

### Common Issues & Solutions

1. **Permission Errors**
```bash
sudo chown -R $USER:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

2. **Redis Connection Failed**
```bash
# Check Redis status
sudo systemctl status redis-server

# Test Redis connection
redis-cli ping
```

3. **Composer Memory Error**
```bash
php -d memory_limit=-1 /usr/local/bin/composer install
```

4. **Node Memory Error**
```bash
export NODE_OPTIONS="--max-old-space-size=4096"
npm run build:production
```

5. **SQLite Permission Error**
```bash
sudo chown www-data:www-data database/database.sqlite
sudo chmod 664 database/database.sqlite
```

## 📈 Performance Monitoring

### Check Application Performance
- Load time should be < 200ms
- Database queries < 100ms average
- Cache hit rate > 80%

### Tools for Monitoring
```bash
# Laravel Telescope (development only)
composer require laravel/telescope --dev
php artisan telescope:install

# Redis monitoring
redis-cli monitor

# Nginx logs (in Docker)
docker logs container_name
```

## 🎯 Next Steps

1. **Setup CI/CD Pipeline** for automated deployment
2. **Configure Monitoring** tools like New Relic or DataDog
3. **Setup Backup Strategy** for database and Redis
4. **Configure CDN** for static assets
5. **Setup SSL Certificate** for production

## 📞 Support

Agar koi issue aaye to:
1. Check logs: `tail -f storage/logs/laravel.log`
2. Clear caches: `php artisan optimize:clear`
3. Restart services: `docker-compose restart` (if using Docker)

Ye guide follow karke aap successfully optimized Laravel Livewire application run kar sakte hain! 🚀