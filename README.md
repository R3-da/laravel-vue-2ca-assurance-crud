# Insurance Claims Management System

This repository contains a web application built with **Laravel** and **Vue.js Livewire** for managing insurance claims for **2CA Business**. The application provides role-based access control, allowing **Admin**, **Broker**, and **Client** users to perform specific actions based on their permissions.

## Preview

[![Preview](https://github.com/user-attachments/assets/b71feb52-239e-484e-8b4a-99b194e4aa4f)](https://github.com/user-attachments/assets/b71feb52-239e-484e-8b4a-99b194e4aa4f)

## Login Credentials

You can use the following credentials to log in to the system:

| Sl  | Role   | Email Address      | Password  |
|-----|--------|--------------------|-----------|
| 01  | Admin  | admin@admin.com    | password  |
| 02  | Broker | broker@broker.com  | password  |
| 03  | Client | client@client.com  | password  |

## Initial Permissions

- **Admin**: Granted all permissions.
- **Broker**: Granted `claims-view`, `claims-edit`, and `attachments-view`.
- **Client**: Granted `claims-view`, `claims-create`, `attachments-view`, and `attachments-create`.

---

## Backlog

### Features to Implement
- [x] Implement a role management system for Admins to manage user roles and permissions dynamically.
- [x] Client can submit a new claim.
- [x] Broker can update the status of the claim.
- [x] Implement email notifications for claim updates.
- [x] Add a dashboard with analytics for claims.
- [ ] Enable file uploads for attachments with size and type validation.
- [ ] Implement an internal messaging system within claims between clients and brokers

## Installation

Follow these steps to set up the project locally:

1. **Clone the repository and install dependencies**:
```bash
   git clone https://github.com/your-repo-url.git

   cd your-repo-directory

   composer install

   npm install
```

2. **Set up the following env variables**:
```bash
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
MAIL_HOST=your_smtp_host
MAIL_USERNAME=your_smtp_username
MAIL_PASSWORD=your_smtp_password
MAIL_FROM_ADDRESS=your_email_address
```

3. **Generate application key:**:
```bash
php artisan key:generate
```

3. **Generate application key:**:
```bash
php artisan migrate
php artisan db:seed
```

3. **Launch dev server:**:
```bash
php artisan serve
npm run dev
```

   
