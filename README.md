# Laravel API Visibility

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE) [![Packagist Version](https://img.shields.io/packagist/v/myat-kyaw-thu/laravel-api-visibility)](https://packagist.org/packages/myat-kyaw-thu/laravel-api-visibility)

**Laravel API Visibility** is a developer-focused Laravel package that provides automatic API route documentation and live previewing capabilities. It serves as a lightweight, expressive alternative to Swagger with emphasis on Developer Experience (DX), speed, and readability.

---

## 🚀 Features

- 📖 **Auto-generates API documentation** from registered Laravel routes and controller methods
- 🧠 **Extracts validation rules** from FormRequest classes automatically
- 📂 **Groups routes** by namespace, prefix, and middleware for better organization
- 🔍 **Live preview** of actual JSON/Markdown responses
- � ️ **Third-party route filtering** with configurable inclusion/exclusion rules
- 🎛️ **Flexible configuration** with environment variable support
- 🛠️ **Customizable Blade views** and configurable route settings
- 🧪 **Fully testable** with SOLID-based architecture
- ⚡ **Performance optimized** for large route collections

---

## 📦 Installation

Install via Composer:

```bash
composer require myat-kyaw-thu/laravel-api-visibility
```

The package will automatically register itself via Laravel's package discovery.

### Publishing Assets

Optionally, publish the configuration and views:

```bash
# Publish configuration file
php artisan vendor:publish --tag=api-visibility-config

# Publish Blade views for customization
php artisan vendor:publish --tag=api-visibility-views
```

---

## 🔧 Configuration

The package works out of the box with sensible defaults. For customization, edit `config/api-visibility.php`:

```php
return [
    // Enable or disable the package
    'enabled' => env('API_VISIBILITY_ENABLED', true),
    
    // Enable or disable live preview feature
    'enable_preview' => env('API_VISIBILITY_PREVIEW_ENABLED', true),
    
    // Route prefix (empty by default)
    'route_prefix' => env('API_VISIBILITY_ROUTE_PREFIX', ''),
    
    // Middleware for documentation routes
    'middleware' => ['web'],
    
    // Exclude routes with specific middleware
    'exclude_middleware' => ['auth.basic'],
    
    // Exclude specific URIs
    'exclude_uris' => [
        '/', 'docs', 'preview', '_ignition/*'
    ],
    
    // Exclude routes with specific prefixes
    'exclude_prefixes' => ['_debugbar', '_ignition'],
    
    // Exclude routes from specific namespaces
    'exclude_namespaces' => [
        'Laravel\Sanctum', 'Laravel\Fortify'
    ],
    
    // Third-party route filtering
    'include_third_party_routes' => env('API_VISIBILITY_INCLUDE_THIRD_PARTY', false),
    
    // Allowed third-party namespaces (whitelist)
    'allowed_third_party_namespaces' => [
        // 'MyCompany\\MyPackage\\',
    ],
    
    // Third-party namespaces to exclude
    'third_party_namespaces' => [
        'Filament\\', 'Livewire\\', 'Spatie\\', 'Barryvdh\\'
    ],
    
    // Response formatters
    'formatters' => [
        'json' => JsonFormatter::class,
    ],
];
```

### Environment Variables

```env
API_VISIBILITY_ENABLED=true
API_VISIBILITY_PREVIEW_ENABLED=true
API_VISIBILITY_ROUTE_PREFIX=""
API_VISIBILITY_INCLUDE_THIRD_PARTY=false
```

---

## 📖 Usage

### Viewing API Documentation

Visit the documentation in your browser:

```
http://your-app.test/docs
```

The documentation displays:
- **Route URI** and **HTTP methods**
- **Controller** and **method** information
- **Middleware** applied to routes
- **Validation rules** extracted from FormRequest classes
- **Route grouping** by prefix, namespace, or middleware
- **Third-party route** indicators

### Live Response Preview

Preview actual route responses:

```
# Preview specific route
http://your-app.test/preview/{routeName}

# Preview index page
http://your-app.test/preview
```

Features:
- **Pretty-printed JSON** responses
- **HTTP status codes** and **headers**
- **Request parameter** handling
- **Multiple HTTP methods** support (GET, POST, PUT, DELETE)
- **Error response** handling

---

## 🧱 Example Usage

```php
// routes/api.php
Route::prefix('api/v1')->group(function () {
    Route::post('/users', [UserController::class, 'store'])
        ->name('api.users.store');
    
    Route::get('/users/{id}', [UserController::class, 'show'])
        ->name('api.users.show');
});

// app/Http/Controllers/UserController.php
class UserController extends Controller
{
    public function store(CreateUserRequest $request)
    {
        $user = User::create($request->validated());
        
        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ], 201);
    }
    
    public function show(User $user)
    {
        return response()->json(['user' => $user]);
    }
}

// app/Http/Requests/CreateUserRequest.php
class CreateUserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ];
    }
}
```

### Documentation Output

Visit `/docs` to see:

| URI | Method | Controller | Validation Rules | Middleware |
|-----|--------|------------|------------------|------------|
| `api/v1/users` | POST | `UserController@store` | `name: required\|string\|max:255`<br>`email: required\|email\|unique:users`<br>`password: required\|string\|min:8\|confirmed` | `web` |
| `api/v1/users/{id}` | GET | `UserController@show` | - | `web` |

### Preview Output

Visit `/preview/api.users.store` with POST data to see:

```json
{
  "message": "User created successfully",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "created_at": "2025-01-23T10:30:00.000000Z",
    "updated_at": "2025-01-23T10:30:00.000000Z"
  }
}
```

---

## 🛡️ Third-Party Route Filtering

The package includes intelligent third-party route filtering to keep your documentation clean:

### Default Behavior
- **Excludes** routes from common packages (Filament, Livewire, Spatie, etc.)
- **Includes** your application routes by default
- **Configurable** via environment variables

### Customization
```php
// Include all third-party routes
'include_third_party_routes' => true,

// Allow specific third-party packages
'allowed_third_party_namespaces' => [
    'MyCompany\\MyPackage\\',
    'TrustedVendor\\Package\\',
],

// Add custom third-party namespaces to exclude
'third_party_namespaces' => [
    'CustomPackage\\',
    'AnotherVendor\\',
],
```

---

## 🎨 Facade Usage

Use the `ApiVisibility` facade for programmatic access:

```php
use myatKyawThu\LaravelApiVisibility\Facades\ApiVisibility;

// Get all documented routes
$routes = ApiVisibility::getDocumentation();

// Preview a specific route response
$preview = ApiVisibility::previewResponse('api.users.store', [
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => 'secret123',
    'password_confirmation' => 'secret123'
]);
```

---

## 🧪 Testing (---Currently Working On it---)

Run the test suite:

```bash
# Run all tests
composer test

# Run tests with coverage
composer test-coverage
```

The package includes comprehensive tests covering:
- Route collection and filtering
- Documentation generation
- Response previewing
- Third-party route detection
- Configuration handling

---

## 📋 Requirements

- **PHP**: ^8.0
- **Laravel**: ^8.0|^9.0|^10.0|^11.0|^12.0

---

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

---

## 📃 License

This project is open-sourced software licensed under the [MIT License](LICENSE).
