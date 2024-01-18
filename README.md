
![Logo](https://zylalabs.com/img/logo-removebg-preview.png)


# CONTENT BUILDER

Description Builder API

Create descriptions for your products dynamically. Let the AI help you with some recommendations based on your products.

Business Name Generator API

This API will give you an extensive list of alternatives for your business names. Let the API help you to choose a name.


## Run Locally

Clone the project

```bash
  git clone git@github.com:Zyla-Labs/api-content-builder.git
```

Rename the project directory

```bash
  mv api-base my-project
```

Go to the project directory

```bash
  cd my-project
```

Delete the .git directory

Install dependencies

```bash
  composer install
```

Create database and config the .env file

Run migrations

```bash
  php artisan migrate
```

Run Admin User Seeder

```bash
  php artisan db:seed --class=UserSeeder
```

## Demo User Credentials
    
    email:adminapibase@zylalabs.com
    password:ZylaApiBase1!

## API Reference

#### Create user

```http
  POST /api/register
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
|   `name`  | `string` | **Required**. Your name    |
|   `email` | `string` | **Required**. Your email   |
|`password` | `string` | **Required**. Your password|

#### Login to get access token

```http
  POST /api/login
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
|   `email` | `string` | **Required**. Your email   |
|`password` | `string` | **Required**. Your password|

and with this we receive the access token (bearer) to be able to use the remaining endpoints.

```bash
  {
    "access_token": "1|laravel_sanctum_Vvh1OI04DbX1mtwSneGJ6ywMPtAousfBHi1ruynsf5ffacd9",
    "token_type": "Bearer"
  }
```

