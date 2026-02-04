#!/bin/bash

set -e

echo "🚀 Bootstrap Symfony API CRM"
echo "=============================="

echo "📦 Stopping existing containers..."
docker-compose down -v

echo "🔨 Building Docker images..."
docker-compose build --no-cache

echo "🚀 Starting containers..."
docker-compose up -d

echo "⏳ Waiting for containers to be ready..."
sleep 5

echo "📚 Installing Composer dependencies..."
docker-compose exec -T php composer install --working-dir=/var/www/html

echo "✅ Bootstrap completed!"
echo ""
echo "Access the application at: http://localhost"
echo "MySQL: localhost:3306 (symfony/symfony/symfony)"
echo "Redis: localhost:6379"
