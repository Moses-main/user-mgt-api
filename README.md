# Laravel API Application

This project is a Laravel-based RESTful API for user management, including features such as user registration, login, logout, profile management, and CRUD operations for users.

## Features

-   User registration with validation
-   User login and authentication using Laravel Sanctum
-   User logout
-   Profile retrieval for authenticated users
-   CRUD operations for users (create, read, update, delete)

## Prerequisites

Before running this application, ensure you have the following installed on your machine:

-   PHP (>= 8.1)
-   Composer
-   Laravel Framework (>= 11.x)
-   MySQL or another supported database
-   Node.js and npm (for frontend dependencies if applicable)

## Getting Started

Follow these steps to clone, set up, and test the application.

### 1. Clone the Repository

```bash
    git clone https://github.com/Moses-main/user-mgt-api.git

    cd user-mgt-api
```

# 2. Install Dependencies

Install PHP dependencies using Composer:

```bash
composer install
```

# 3. Set Up Environment Variables

Copy the .env.example file to .env:

```bash
cp .env.example .env

```

Upadate the <code>.env </code> file with your database credentials and other configurations. Example:

```bash
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:randomkeyhere
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=postgres
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=yorootur_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

```

# 4 Generate Application Key

Run the following command to generate the application key

```bash
php artisan key:generate

```

# 5. Run Database Migrations

Run migrations to set up the database tables:

```bash
php artisan migrate
```

# 6. Start the Development Server

Start the Laravel development server:

```bash
php artisan serve

```

The application will be accessible at http://127.0.0.1:8000.

# 7. Testing the API

You can test the API endpoints using a tool like Postman or cURL. Below are the available endpoints:

## Public Endpoints

### 1. Register a User

```bash
POST /api/register
```

Payload:

```json
{
    "name": "John Doe",
    "email": "johndoe@example.com",
    "password": "password",
    "password_confirmation": "password"
}
```

### 2. Login

```bash
POST /api/login
```

Payload:

```json
{
    "email": "johndoe@example.com",
    "password": "password"
}
```

### 3. Get a specific User

```bash
GET api/users/{id}
```

### Protected Endpoints (Requires Authentication)

Include the Authorization header with the token received from login:

```makefile
Authorization: Bearer <token>

```

### 1. Get User Profile

```bash
GET api/user/profile
```

### 2. Logout

```bash
POST api/logout
```

### 3. List All Users

```bash
GET api/users
```

### 4. Update a user

```bash
PUT api/users/{id}
```

Payload:

```json
{
    "name": "Updated Name",
    "email": "updatedemail@example.com"
}
```

### 5. Delete a User

```bash
DELETE api/users/{id}
```

---

## License

This Project is licensed under the MIT License

---

## Contributing

Contributions are welcome! Please fork this repository, make your changes, and submit a pull request.

## Support

If you encounter any issues or have questions, feel free to open an issue or contact me directly.

```markdown
### Notes:

1. Replace `yourusername` in the `git clone` URL with your actual GitHub username or repository URL.
2. Customize any details specific to your project.
3. Include a license file if applicable (e.g., `LICENSE`).
```
