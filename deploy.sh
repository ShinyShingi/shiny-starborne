#!/bin/bash

# Shiny Starborne Production Deployment Script
set -e

echo "🚀 Starting Shiny Starborne deployment..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if Docker and Docker Compose are installed
if ! command -v docker &> /dev/null; then
    print_error "Docker is not installed. Please install Docker first."
    exit 1
fi

if ! command -v docker-compose &> /dev/null; then
    print_error "Docker Compose is not installed. Please install Docker Compose first."
    exit 1
fi

# Check if production environment file exists
if [ ! -f ".env.production" ]; then
    print_error "Production environment file (.env.production) not found!"
    print_warning "Please copy .env.production.example to .env.production and configure it."
    exit 1
fi

# Copy production environment file
print_status "Setting up production environment..."
cp .env.production .env

# Pull latest images and build
print_status "Building production images..."
docker-compose -f docker-compose.prod.yml build --no-cache

# Stop existing containers if running
print_status "Stopping existing containers..."
docker-compose -f docker-compose.prod.yml down

# Start production containers
print_status "Starting production containers..."
docker-compose -f docker-compose.prod.yml up -d

# Wait for database to be ready
print_status "Waiting for database to be ready..."
sleep 30

# Run database migrations
print_status "Running database migrations..."
docker-compose -f docker-compose.prod.yml exec -T app php artisan migrate --force

# Seed database if needed (uncomment if you want to seed data)
# print_status "Seeding database..."
# docker-compose -f docker-compose.prod.yml exec -T app php artisan db:seed --force

# Clear and cache configuration
print_status "Optimizing application..."
docker-compose -f docker-compose.prod.yml exec -T app php artisan config:clear
docker-compose -f docker-compose.prod.yml exec -T app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec -T app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec -T app php artisan view:cache

# Create storage link
print_status "Creating storage link..."
docker-compose -f docker-compose.prod.yml exec -T app php artisan storage:link

# Set proper permissions
print_status "Setting file permissions..."
docker-compose -f docker-compose.prod.yml exec -T app chown -R appuser:appuser /var/www/storage
docker-compose -f docker-compose.prod.yml exec -T app chown -R appuser:appuser /var/www/bootstrap/cache

# Show container status
print_status "Checking container status..."
docker-compose -f docker-compose.prod.yml ps

# Health check
print_status "Performing health check..."
sleep 10

if curl -f http://localhost:80 > /dev/null 2>&1; then
    print_success "Application is running successfully!"
    print_success "🌟 Shiny Starborne is now live at http://localhost"
else
    print_error "Health check failed. Please check the logs:"
    docker-compose -f docker-compose.prod.yml logs app
    exit 1
fi

# Show useful commands
echo ""
print_success "Deployment completed successfully! 🎉"
echo ""
echo "Useful commands:"
echo "  View logs: docker-compose -f docker-compose.prod.yml logs -f"
echo "  Stop: docker-compose -f docker-compose.prod.yml down"
echo "  Restart: docker-compose -f docker-compose.prod.yml restart"
echo "  Shell access: docker-compose -f docker-compose.prod.yml exec app sh"
echo ""
print_warning "Don't forget to:"
echo "  1. Configure SSL certificates for HTTPS"
echo "  2. Set up domain name and DNS"
echo "  3. Configure automated backups"
echo "  4. Set up monitoring and logging"