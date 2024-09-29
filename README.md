# Instructions for running the News Aggregator API (Laravel)

## 1. Clone the repository
git clone https://github.com/zaharie/news-aggregator-api.git

## 2. Navigate to the project directory
cd news-aggregator-api

## 3. Create a .env file
cp .env.example .env

## 4. Start the Docker containers
docker-compose up -d

## 5. Access the application
Visit http://localhost:3000 in your browser

## 6. Stop the Docker containers
docker-compose down

## 7. tests
docker-compose exec app php artisan test

