# LSD-APPS

## Setup Instructions

### Prerequisites

- Docker
- Composer (for local development)

### Installation

1. Clone the repository

2. Create the file .env.local

```
###> doctrine/doctrine-bundle ###
DATABASE_URL="mysql://symfony_user:symfony_password@database:3306/symfony?serverVersion=8.0"
###< doctrine/doctrine-bundle ###
```

3. Build and start the Docker containers:

```bash
docker-compose up -d
```

4. Install dependencies:

```bash
docker-compose exec php composer install
```

5. Create database schema:

```bash
docker-compose exec php php bin/console make:migration
docker-compose exec php php bin/console doctrine:migrations:migrate
```

6. Load fixtures (sample data):

```bash
docker-compose exec php php bin/console doctrine:fixtures:load
```

7. Access the application at http://localhost:8080

## Technical Stack

- Symfony 7.3
- MySQL 8.0
- PHP 8.2
- Docker
