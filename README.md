# Simple dockerized gta wallet api

## Requirements

- PHP 8.1
- Docker v20+
- docker-compose 1.29
- Debian OS preferable

## Configuration

1. You can add new API endpoints via adding actions in `app/src/Action/`.
2. You must extend `GTAWalletApi\Components\Application\AbstractAction`.
3. Routes is configured in `app/config/routes.php`

## Initialisation

1. Make sure
    - `docker` and `docker-compose` installed.
    - `make` utility available: ```apt-get install build-essential```
2. Create environment config: ```cp .env.example .env```
3. Edit `.env` and set actual data.
    - BILLING_VENDOR to set payment system using for payments
    - Configure payment systems options
    - SERVICE_NGINX_HTTP_PORTS - if proxy or balancers using
    - MYSQL_* connection data
4. Run `make install`

## Usage

### To add new payment

```
curl --request POST \
  --url 'https://{HOST}/payment' \
  --header 'Content-Type: application/json' \
  --data '{
    "accountId": 1,
    "amount": 100,
    "currency": "RUB",
    "credits": 100
  }'

```

Where

- `{HOST}` - current API host
- `accountId` - id of payer (integer)
- `amount` - value of money to be payed (integer|decimal)
- `credits` - value of credits to be issued (integer|decimal)
- `currency` - currency code (string)

### To get payment

```
curl --request GET \
  --url https://{HOST}/payment/{id}
```

Where

- `{HOST}` - current API host
- `{id}` - id must be in query path
