<div align="center">

# 🏗️ Construction SaaS · Admin Panel

### The office command center for construction companies, built on Filament

Projects · Estimations · Budgets · Materials · Labor · Team

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Filament](https://img.shields.io/badge/Filament-3-FDAE4B?style=for-the-badge&logoColor=black)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)

![Tenancy](https://img.shields.io/badge/multi--tenant-stancl%2Ftenancy-F59E0B?style=flat-square)
![RBAC](https://img.shields.io/badge/RBAC-spatie%2Fpermission-F59E0B?style=flat-square)
![License](https://img.shields.io/badge/license-MIT-F59E0B?style=flat-square)

**Part of the Construction SaaS suite:**
[Backend API](https://github.com/shacrawi92/construction-backend) · **Admin panel** (this repo) · [Field PWA](https://github.com/shacrawi92/construction-pwa)

</div>

---

## 🧭 Overview

Office staff run the whole company from this panel. They create projects, build estimations from reusable templates, approve budgets and material requests coming from the field, and watch costs on live dashboards. The data model is tenant-ready (central and tenant migrations, `stancl/tenancy`) so each company can run in isolation.

## 🖥 Resources

| Resource | Purpose |
|---|---|
| 📁 **Projects** | Project register, status and timelines |
| 📐 **Estimations** | Line-item estimates with PDF export |
| 🧩 **Estimation templates** | Reusable estimate blueprints |
| 🧱 **Work types** | Catalog of work categories and unit rates |
| 💰 **Budgets** | Budgets and transactions per project |
| 📦 **Materials** | Material catalog and stock |
| 📝 **Material requests** | Approve or reject requests submitted by foremen |
| 👷 **Labor logs** | Daily labor records from the field |
| 👤 **Users** | Team members, roles and permissions |

## 📊 Dashboard widgets

- **Cost overview**: committed versus spent across all projects
- **Financial burn rate chart**: spending velocity over time
- **Project status chart**: portfolio at a glance
- **Low-stock alert table**: materials that need reordering

## 🛠 Tech stack

| Layer | Technology |
|---|---|
| Framework | Laravel 12, PHP 8.2+ |
| Admin UI | Filament 3 (Livewire, Alpine, Tailwind) |
| Tenancy | `stancl/tenancy` with central and tenant migrations |
| Auth & roles | Laravel Sanctum, `spatie/laravel-permission` |
| Documents | `barryvdh/laravel-dompdf` |

## 🚀 Getting started

```bash
git clone https://github.com/shacrawi92/construction-admin.git
cd construction-admin
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan make:filament-user
php artisan serve        # open http://localhost:8000/admin
```

For production, start from [`.env.production.example`](.env.production.example).

## 📁 Structure

```
app/
├── Filament/
│   ├── Resources/      # 9 CRUD resources
│   └── Widgets/        # 4 dashboard widgets
├── Models/
└── Providers/Filament/AdminPanelProvider.php
database/migrations/
├── central/            # tenants, domains
└── tenant/             # company data
```

## 📄 License

MIT

<div align="center">

Built by **[@shacrawi92](https://github.com/shacrawi92)**

</div>
