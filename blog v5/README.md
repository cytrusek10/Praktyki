# 🎲 Mój Randomowy Blog

Blog zrobiony na **Laravel 11** + **Filament 3** z panelem admina do zarządzania postami.
Wszystko działa na Dockerze, więc nie trzeba nic instalować na swoim komputerze (oprócz samego Dockera).

---

## ⚡ Jak uruchomić (naprawdę prosto)

### Krok 1: Zainstaluj Docker Desktop
Jeśli jeszcze nie masz: https://www.docker.com/products/docker-desktop/
Pobierz, zainstaluj, uruchom. Tyle.

### Krok 2: Odpal skrypt startowy

**Na Mac / Linux:**
```bash
chmod +x start.sh
./start.sh
```

**Na Windows (PowerShell):**
```powershell
docker compose up -d --build
# Poczekaj aż MySQL wstanie (30-60 sekund), potem:
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --force
docker compose exec app php artisan db:seed --force
```

### Krok 3: Otwórz przeglądarkę

- **Blog:** http://localhost:8000
- **Panel admina:** http://localhost:8000/admin

**Dane do logowania:**
```
Email:  admin@blog.pl
Hasło:  password
```

---

## 📁 Struktura projektu

```
blog/
├── app/
│   ├── Filament/Resources/
│   │   └── PostResource.php     ← panel admina dla postów
│   ├── Http/Controllers/
│   │   └── PostController.php   ← obsługa strony bloga
│   └── Models/
│       ├── Post.php             ← model posta
│       └── User.php             ← model użytkownika
├── database/
│   ├── migrations/              ← tworzenie tabel w bazie
│   └── seeders/
│       └── DatabaseSeeder.php   ← przykładowe posty
├── resources/views/
│   ├── layouts/app.blade.php    ← główny layout HTML
│   └── posts/
│       ├── index.blade.php      ← lista postów
│       └── show.blade.php       ← pojedynczy post
├── docker/nginx/default.conf    ← konfiguracja serwera www
├── docker-compose.yml           ← definicja kontenerów
├── Dockerfile                   ← obraz PHP 8.4
└── start.sh                     ← skrypt startowy
```

---

## 🛠️ Przydatne komendy

```bash
# Zatrzymanie projektu
docker compose down

# Zatrzymanie + usunięcie bazy danych (reset do zera)
docker compose down -v

# Logi aplikacji
docker compose logs app

# Wejście do kontenera PHP
docker compose exec app bash

# Dodanie nowego admina
docker compose exec app php artisan make:filament-user
```

---

## ✍️ Jak dodać post?

1. Wejdź na http://localhost:8000/admin
2. Zaloguj się (admin@blog.pl / password)
3. Kliknij "Posty" w bocznym menu
4. Kliknij "Utwórz post"
5. Wypełnij formularz, przełącz "Opublikowany?" na tak
6. Zapisz

Post pojawi się na blogu od razu.

---

## 🐛 Coś nie działa?

**Blog nie otwiera się:**
- Sprawdź czy Docker Desktop jest uruchomiony
- Poczekaj 30 sekund po uruchomieniu start.sh
- Sprawdź logi: `docker compose logs`

**Błąd przy bazie danych:**
- Baza potrzebuje chwili na start, spróbuj uruchomić migracje jeszcze raz:
  `docker compose exec app php artisan migrate --force`

**Cokolwiek innego:**
- `docker compose down -v` i zacznij od nowa ze skryptem start.sh

---

## 🤖 Generowanie treści przez AI

Blog używa **Laravel AI SDK** (`laravel/ai`). Żeby generowanie działało, potrzebujesz klucza API od jednego z providerów.

**Najprostsze: OpenAI**
1. Wejdź na https://platform.openai.com/api-keys
2. Stwórz klucz API
3. Wpisz go w `.env`:
```
OPENAI_API_KEY=sk-...
```

**Albo Anthropic (Claude)**
```
ANTHROPIC_API_KEY=sk-ant-...
```

Przycisk **"Generuj"** pojawi się w formularzu tworzenia posta w panelu admina.
