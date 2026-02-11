# Local DB connection (Step 2)

Project config now supports environment variables for DB/app connection.

## Supported env vars

- `APP_URL`
- `APP_HTTPS` (`0` or `1`)
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`
- `DB_CHARSET`

## Run locally with real DB credentials

```bash
APP_URL="http://127.0.0.1:8080/" \
APP_HTTPS=0 \
DB_HOST="127.0.0.1" \
DB_PORT="3306" \
DB_DATABASE="your_db" \
DB_USERNAME="your_user" \
DB_PASSWORD="your_pass" \
DB_CHARSET="utf8" \
php -S 127.0.0.1:8080 -t _/www/arewise.com
```

If DB is reachable and credentials are valid, homepage should open without
`There is no connection to the database`.
