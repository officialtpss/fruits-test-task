# fruity-app-backend

Requirements

- PHP 8.2
- Mysql

## Getting started

Clone project on local from git

## Setup .env File

- rename .env_local to .env

- Update db credentials

replace username, password, db_name on line 27

```
DATABASE_URL="mysql://username:password@127.0.0.1:3306/db_name?serverVersion=5&charset=utf8mb4"
```

- Update smtp credentails

replace username, password, host & port on line 40, Also FROM_EMAIL & TO_EMAIL

```
MAILER_DSN="smtp://username:password@host:port"
FROM_EMAIL="abc@xyz.com"
TO_EMAIL="xyz@abc.com"
```

## Commands to run

- generate new database & database connection

```
php bin/console doctrine:database:create
```

- Run migrations ( create tables )

```
php bin/console doctrine:migrations:migrate
```

- Generate a new user record 

```
php bin/console generate:user
```

- in frontend you can login with 

```
user@mail.com / 12345678
```

- Run symfony project

```

php -S localhost:8000 -t public/
```

- Getting all fruits from https://fruityvice.com/ and saving them into local DB & sending email ( don't forgot to setup smtp in .env with FROM_EMAIL & TO_EMAIL )

```
php bin/console fruits:fetch
```