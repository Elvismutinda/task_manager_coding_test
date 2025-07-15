# 🗂️ Task Manager App

A task management system built using **Laravel 10**, **Vue 3**, **Inertia.js**, **Tailwind CSS**, and **Vite**. The app supports user authentication, task assignment with deadlines, role-based access control (admin vs. regular users), activity tracking, and a responsive dashboard.


## ⚙️ Installation & Setup

> You'll need PHP (>=8.1), Composer, Node.js, and a database (MySQL/PostgreSQL/SQLite) to run this locally.

### 1. Clone the Repo

```bash
git clone https://github.com/your-username/task-manager.git
cd task-manager
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install JS Dependencies

```bash
npm install
```

### 4. Create Environment File

```bash
cp .env.example .env
php artisan key:generate
```

Update your `.env` file with database and mail configuration.

### 5. Run Migrations and Seeders

```bash
php artisan migrate --seed
```

This will create two users:

| Role  | Email              | Password |
|-------|--------------------|----------|
| Admin | admin@gmail.com    | password |
| User  | elvocool@gmail.com | password |

### 6. Build Frontend Assets

```bash
npm run dev        # For development (with hot reload)
npm run build      # For production
```

### 7. Start the Server

```bash
php artisan serve
```

or

```bash
composer run dev
```

Visit [http://localhost:8000/login](http://localhost:8000/login)

---

## Role-Based Access & Policies

| Role  | Permissions |
|-------|-------------|
| Admin | Create, edit, assign, and delete all tasks. Manage users. See all dashboards. |
| User  | View and update tasks assigned to them. Cannot assign or delete tasks. |

### Laravel Policies

- `TaskPolicy`:
  - `view`: Users can view only their tasks. Admins can view all.
  - `update` & `delete`: Only admins can update or delete any task.
  - `create`: Only admins can assign tasks.

---

## App Structure

- `app/Models/Task.php` – Task model
- `app/Policies/TaskPolicy.php` – Task authorization logic
- `resources/js/pages/Dashboard.vue` – Dashboard UI
- `resources/js/pages/Tasks/*.vue` – Task views
- `routes/web.php` – Route definitions
- `app/Mail/TaskAssigned.php` – Email template for task assignment

---

## Deployment Notes

If deploying on services like **Railway** or **Vercel**, ensure:

- You run `npm run build` and commit the `/public/build` folder.
- `.env` is properly set with `APP_URL`, `APP_KEY`, `MAIL_` config, etc.
- You serve Laravel on port `8080` if required (`php artisan serve --port=8080`).

---
