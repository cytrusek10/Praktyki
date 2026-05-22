#!/bin/bash

set -e

echo ""
echo "======================================"
echo "  Stawiamy blog! Poczekaj chwilę..."
echo "======================================"
echo ""

if [ ! -f .env ]; then
    echo "[1/6] Kopiuję plik .env..."
    cp .env.example .env
else
    echo "[1/6] Plik .env już istnieje, pomijam."
fi

echo "[2/6] Buduję kontenery Docker (pierwsze uruchomienie trwa dłużej)..."
docker compose up -d --build

echo "[3/6] Czekam aż baza danych wstanie..."
until docker compose exec -T db mysql -u blog_user -psecret blog -e "SELECT 1" > /dev/null 2>&1; do
    echo "   ... MySQL jeszcze nie gotowy, czekam 3 sekundy..."
    sleep 3
done
echo "   MySQL gotowy!"

echo "[4/6] Instaluję zależności PHP..."
docker compose exec app composer install --no-interaction --optimize-autoloader

echo "[5/6] Generuję klucz aplikacji..."
docker compose exec app php artisan key:generate

echo "[5/6] Publikuję konfigurację Laravel AI..."
docker compose exec app php artisan vendor:publish --provider="Laravel\\Ai\\AiServiceProvider" --quiet

echo "[6/6] Tworzę tabele i dodaję przykładowe posty..."
docker compose exec app php artisan migrate --force
docker compose exec app php artisan db:seed --force

echo ""
echo "======================================"
echo "  GOTOWE! Blog działa!"
echo "======================================"
echo ""
echo "  Blog:        http://localhost:8000"
echo "  Panel admin: http://localhost:8000/admin"
echo ""
echo "  Login do admina:"
echo "  Email:    admin@blog.pl"
echo "  Hasło:    password"
echo ""
echo "======================================"
