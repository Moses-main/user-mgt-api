# User Management API

## This guide will help you set up, run, and test a Laravel application with

1. authentication features, including login,
2. registration,
3. profile viewing, and l
4. ogout functionalities.

## Prerequisites

Ensure the following are installed on your system:

1. PHP (≥ 8.1)
2. Composer
3. Postgres or another supported database
4. Postman or another API testing tool (optional)
5. Laravel Installer (optional, use Composer if not installed)

# Steps to Set Up and Test the Application

6. Clone or Create the Laravel Application

### Clone an existing Laravel project

```git
   git clone https://github.com/Moses-main/user-mgt-api.git
```

### Navigate into the project directory

```cmd
cd user-mgt-api
```

2.  Install Dependencies

```bash

composer install

```

3. Configure Environment Variables

    Copy the .env.example file to .env and update the database credentials.

```bash
cp .env.example .env
```

Edit the .env file:

```env

APP_NAME=LaravelAPI
APP_ENV=local
APP_KEY=base64:GENERATED_KEY
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

4. Generate the Application Key

```bash
php artisan key:generate
```

5. Run Migrations and Set up the database schema.

```bash

php artisan migrate
```

6. Install Laravel Sanctum
   Sanctum provides API token authentication.

```bash
composer require laravel/sanctum
```

Publish the Sanctum configuration:

```bash

php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

Run the Sanctum migrations:

```bash
php artisan migrate
```

7. Update User Model
   Ensure the User model uses the HasApiTokens trait for Sanctum:

```php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
use HasApiTokens, Notifiable;
}
```

8. Define API Routes
   Add the following to your routes/api.php file:

```php

use App\Http\Controllers\AuthController;

// Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user/profile', [AuthController::class, 'profile']);
```

9. Create the AuthController
   Create a new controller for authentication logic:

```bash
php artisan make:controller AuthController
```

Add the following methods to AuthController:

```php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
        public function register(Request $request)
        {
        $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        ]);

       $user = User::create([
           'name' => $validatedData['name'],
           'email' => $validatedData['email'],
           'password' => Hash::make($validatedData['password']),
       ]);

       return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);

    }

        public function login(Request $request)
        {
        $validatedData = $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string|min:8',
        ]);

       $user = User::where('email', $validatedData['email'])->first();

       if (!$user || !Hash::check($validatedData['password'], $user->password)) {
           return response()->json(['message' => 'Invalid credentials'], 401);
       }

       $token = $user->createToken('authToken')->plainTextToken;

       return response()->json(['message' => 'Login successful', 'token' => $token], 200);

    }

        public function logout(Request $request)
        {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully'], 200);
        }

        public function profile(Request $request)
        {
            if (!$request->user()) {
        return response()->json(['message' => 'Unauthorized. Please login to get an access token.'], 401);
        }

            return response()->json(['user' => $request->user()], 200);

        }

}
```

10. Start the Application
    Run the application server:

```bash
php artisan serve
```

11. Test the API
    Using Postman or cURL:
    Register User

Endpoint: POST /api/register
Body (JSON):

```json
{
    "name": "John Doe",
    "email": "john.doe@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

Login User

```
Endpoint: POST /api/login
```

Body (JSON):

```json
{
    "email": "john.doe@example.com",
    "password": "password123"
}
```

Response:

```json
{
    "message": "Login successful",
    "token": "your-generated-token"
}
```

View Profile

```
Endpoint: GET /api/user/profile
```

Headers:

```makefile

Authorization: Bearer your-generated-token

```

Logout

```
Endpoint: POST /api/logout
```

Headers:

```makefile

Authorization: Bearer your-generated-token

```
