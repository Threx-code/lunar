## About Shipment Time API

This document contains the following guides. Please note that step 1 is only needed if you want to use the database functionality added to the application

1. How to install the Shipment Time API
2. How to Run the Application
3. How to consume the API


## Installation (How to install the Shipment Time API)

- Create a database of your choice preferably (MySQL)
- Add the database credentials to .env file. Example shown below

		DB_DATABASE=lunar
		DB_USERNAME=lunar_admin
		DB_PASSWORD=password

- Launch your CLI
- Navigate to the folder (Directory) where the application is installed.
- Run this Laravel command. php artisan migrate --path=database/migrations/2021_10_01_085453_create_lunars_table.php

this will migrate only the table for storing shipment data such as shipping time, arrival time in LST

- Locate the file app/Http/Controllers/v1/LunarController.php store() method and uncomment

$this->storeToDatabase($host, $userAgent, $lunarTime, $shippingTime);



## Run the Application (How to Run the Application)
- Launch your CLI
- Navigate to the folder (Directory) where the application is installed.
- Run php artisan serve


## Consume (How to consume the API)

Refer to any of this links on how to make an HTTP Request to the API

- http://localhost:8000/ you can checkout the options Bash, JavaScript, PHP

- http://localhost:8000/docs

If you prefer to read JSON documentation

- http://localhost:8000/docs/collection.json

