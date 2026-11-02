# Task Manager API

A RESTful task management API built with **Laravel 10** and **Laravel Sanctum**.
Users register, log in with a token, and manage their own tasks — each user can only see and change the tasks they own.

## Features

- Token-based authentication with Laravel Sanctum (register, login, logout)
- Full task CRUD (create, list, update, delete)
- Tasks are scoped to the authenticated user
- Task status tracking (`pending`, `in-progress`, `completed`)
- Request validation with clear JSON error responses

## Tech Stack

PHP 8.1+ · Laravel 10 · Laravel Sanctum · MySQL

## Getting Started

```bash
git clone https://github.com/mhmdatya72/task-manager-api.git
cd task-manager-api
composer install
cp .env.example .env
php artisan key:generate
# set your DB_* values in .env
php artisan migrate
php artisan serve
```

The API runs at `http://127.0.0.1:8000/api`.

## API Endpoints

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| POST | `/api/register` | — | Create an account and get a token |
| POST | `/api/login` | — | Log in and get a token |
| POST | `/api/logout` | Bearer | Revoke the current token |
| GET | `/api/user` | Bearer | Current user |
| GET | `/api/tasks` | Bearer | List your tasks |
| POST | `/api/tasks` | Bearer | Create a task |
| PUT | `/api/tasks/{task}` | Bearer | Update a task |
| DELETE | `/api/tasks/{task}` | Bearer | Delete a task |

Send the token in the header: `Authorization: Bearer <token>`.

### Example

```http
POST /api/tasks
Authorization: Bearer <token>
Content-Type: application/json

{
  "title": "Prepare sprint demo",
  "status": "pending"
}
```

## Author

**Mohamed Atya Hawash** — Senior Full-Stack Laravel Developer
[Portfolio](https://mohamedatya-dev.vercel.app) · [LinkedIn](https://www.linkedin.com/in/mohamedatya-dev/)
