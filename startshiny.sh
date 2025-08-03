#!/bin/bash

echo "🚀 Starting Shiny Starborne..."

# Start all containers in detached mode
echo "Starting Docker containers..."
docker-compose up -d

# Wait a moment for containers to initialize
echo "Waiting for containers to be ready..."
sleep 2

# Check if containers are running
if docker ps | grep -q shiny-starborne-app; then
    echo "✅ All containers started successfully!"
    echo ""
    echo "📦 Entering app container shell..."
    echo "💡 You can now run commands like:"
    echo "   - php artisan migrate"
    echo "   - npm install"
    echo "   - npm run dev"
    echo "   - composer install"
    echo ""
    echo "Type 'exit' to leave the container shell"
    echo "================================================"
    
    # Enter interactive shell in app container
    docker exec -it shiny-starborne-app bash
else
    echo "❌ Failed to start containers. Please check docker-compose logs."
    exit 1
fi