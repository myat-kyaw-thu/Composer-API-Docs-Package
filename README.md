# Laravel API Visibility

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE) [![Packagist Version](https://img.shields.io/packagist/v/vendor/laravel-api-visibility)](https://packagist.org/packages/vendor/laravel-api-visibility)

**Laravel API Visibility** is a developer-focused package that provides automatic API route documentation and live previewing of JSON/Markdown responses. It aims to be a lightweight, expressive alternative to Swagger — focused on Developer Experience (DX), speed, and readability.

---

## 🚀 Features

- 📖 Auto-generates API docs from registered routes and controller methods
- 🧠 Extracts validation rules from FormRequest classes
- 📂 Groups routes by namespace, prefix, and middleware
- 🔍 Live preview of actual JSON or Markdown responses
- 🛠 Customizable Blade views and configurable route settings
- 🧪 Fully testable, SOLID-based architecture

---

## 📦 Installation

Install via Composer:

```bash
composer require vendor/laravel-api-visibility
```

Optionally, publish the config and views:

```bash
php artisan vendor:publish --tag=api-visibility-config
php artisan vendor:publish --tag=api-visibility-views
```

---

## 🔧 Configuration

Edit the published configuration file `config/api-visibility.php` to customize routes and behavior:

```php
return [
    'docs_route'      => '/docs',
    'preview_route'   => '/preview',
    'enable_preview'  => true,
    'middleware'      => ['web'],
];
```

---

## 📖 Viewing API Documentation

Visit the documentation in your browser:

```
http://your-app.test/docs
```

Displays all API routes with:

- URI
- HTTP Method
- Controller & Method
- Validation Rules
- Middleware

---

## 🔍 Live Preview

To see actual responses for a given route:

```
http://your-app.test/preview/{routeName}
```

Or visit:

```
http://your-app.test/preview
```

Shows:

- Pretty-printed JSON
- Markdown as HTML
- HTTP status and headers

---

## 🧱 Example

```php
// routes/api.php
Route::post('/register', [AuthController::class, 'register'])
    ->name('auth.register');

// app/Http/Controllers/AuthController.php
class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        return response()->json([
            'message' => 'Registered successfully',
        ]);
    }
}

// app/Http/Requests/RegisterRequest.php
class RegisterRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email'    => 'required|email',
            'password' => 'required|min:8',
        ];
    }
}
```

Access `/docs` and `/preview/auth.register` to view this route.

---

## 🧾 Example Output

### At `/docs`

| URI         | Method | Controller@Method         | Validation Rules |
| ----------- | ------ | ------------------------- | ---------------- | ----------------------------- | ------ |
| `/register` | POST   | `AuthController@register` | `email: required | email`<br>`password: required | min:8` |

### At `/preview/auth.register`

```json
{
  "message": "Registered successfully"
}
```

---

## 📃 License

This project is open-sourced software licensed under the MIT License. See [LICENSE](LICENSE) for details.
