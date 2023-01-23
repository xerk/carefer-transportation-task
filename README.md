# Transportation Carefer Task

## Bus Ticket Reservation Transportation API

The Bus Ticket Reservation Transportation API allows users to easily search for available bus routes, view bus schedules and prices, and make reservations for a specific seat on a bus. The API includes endpoints for searching for trips or routes, viewing bus schedules, reserving seats, and managing reservations. The API uses standard HTTP requests and returns data in JSON format. This documentation provides detailed information on the available endpoints, their parameters, and the expected responses. Additionally, it provides examples of how to use the API with the Postman collection to help you get started quickly and easily.

[Postman Collection](https://drive.google.com/file/d/136g5y9t3omyGnp4f4qorpR1XhcIGqFhG/view?usp=sharing)

## Clone the project

Run Composer \
`composer install`

Copy the ENV \
`cp .env.example .env` 

Generate KEY \
`php artisan key:generate`


## Docker Installtion 

Let’s build & run our app in a Docker container: \
```./vendor/bin/sail up```

OR

Also you can use \
`docker-compose up -d`

## Local Installtion 

`php artisan server --port=80`

UNIT TEST \
`php artisan test`
