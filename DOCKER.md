# Docker Setup for Shiny Starborne

This project includes a complete Docker setup with separate containers for each service.

## Services

- **app**: PHP 8.2-FPM with Laravel application
- **nginx**: Nginx web server (port 8080)
- **db**: MySQL 8.0 database (port 3307)
- **redis**: Redis cache (port 6380)
- **node**: Node.js for frontend build tools (port 5173)

## Quick Start

1. **Copy environment file:**
   ```bash
   cp .env.example .env
   ```

2. **Update .env file** with Docker settings (already configured in .env.example)

3. **Start the containers:**
   ```bash
   docker-compose up -d
   ```

4. **Install dependencies and set up the application:**
   ```bash
   # Install PHP dependencies
   docker-compose exec app composer install
   
   # Generate application key
   docker-compose exec app php artisan key:generate
   
   # Run migrations and seeders
   docker-compose exec app php artisan migrate:fresh --seed
   
   # Install Node dependencies and build assets
   docker-compose exec node npm install
   docker-compose exec node npm run build
   ```

5. **Access the application:**
   - Web Application: http://localhost:8080
   - Vite Dev Server: http://localhost:5173

## Development Workflow

### Running Commands

```bash
# Laravel commands
docker-compose exec app php artisan migrate
docker-compose exec app php artisan tinker

# Composer commands
docker-compose exec app composer install
docker-compose exec app composer update

# Node/NPM commands
docker-compose exec node npm install
docker-compose exec node npm run dev
docker-compose exec node npm run build

# Database access
docker-compose exec db mysql -u shiny -p shiny_starborne
```

### File Watching

For development with hot reloading:

```bash
# Start Vite dev server with hot reload
docker-compose exec node npm run dev
```

### Logs

```bash
# View logs for all services
docker-compose logs

# View logs for specific service
docker-compose logs app
docker-compose logs nginx
docker-compose logs db
docker-compose logs redis
```

## Stopping and Cleaning Up

```bash
# Stop containers
docker-compose down

# Stop and remove volumes (WARNING: This will delete your database)
docker-compose down -v

# Rebuild containers
docker-compose up --build
```

## Database Connection

When using Docker, your database connection settings should be:
- Host: `db` (service name)
- Port: `3306` (internal port)
- Database: `shiny_starborne`
- Username: `shiny`
- Password: `shiny`

For external access (e.g., database tools), use:
- Host: `localhost`
- Port: `3307`

## Redis Connection

When using Docker, your Redis connection settings should be:
- Host: `redis` (service name)
- Port: `6379` (internal port)

For external access, use:
- Host: `localhost`
- Port: `6380`

## Troubleshooting

### Container won't start
```bash
docker-compose logs [service-name]
```

### Permission issues
```bash
docker-compose exec app chown -R www-data:www-data /var/www
docker-compose exec app chmod -R 755 /var/www/storage
```

### Clear caches
```bash
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
```

## File Structure

```
docker/
├── nginx/
│   ├── nginx.conf          # Main Nginx configuration
│   └── site.conf           # Site-specific configuration
├── php/
│   └── local.ini           # PHP configuration
└── mysql/
    └── my.cnf              # MySQL configuration

docker-compose.yml          # Docker Compose configuration
Dockerfile                  # PHP application Dockerfile
.dockerignore              # Docker ignore file
```