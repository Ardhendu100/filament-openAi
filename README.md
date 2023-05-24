## Red Queen IVF AI
RedQUeenIVFAI, is an AI-powered medical diagnosis platform where users can input their medical issue, and the system uses OpenAI's technology to provide a response.

## Installation

```
cd /path/to/redqueenivfai
composer install
```

**Now the start the server using**
```
docker-compose up -d
```

**First Time Installation Only**
```
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed # This step destroys all data in the database. NOT TO BE RUN IN PRODUCTION
```

**Export Translations**
```
php artisan translatable:export en
```

**Run Development Environment**
```
php artisan serve
```

**Upgrade Filament**
```
php artisan config:clear
php artisan livewire:discover
php artisan route:clear
php artisan view:clear
```
```
composer update
php artisan filament:upgrade
```
