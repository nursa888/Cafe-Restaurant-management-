# Café & Restaurant Management System (core build)
## Install on XAMPP
1. Copy this folder to `C:\xampp\htdocs\cafe_restaurant`. Start Apache + MySQL.
2. phpMyAdmin -> Import -> `database/cafe_restaurant.sql` (creates the `cafe_restaurant` database).
3. If your MySQL root has a password, edit `config/config.php`.
4. Open http://localhost/cafe_restaurant/ (internet needed for Bootstrap/Chart.js CDN).
## Accounts (password for all: `Admin@123`, change after first login)
admin, manager, cashier, waiter, kitchen, inventory
## Included
Login/roles, CSRF, PDO, dashboard + chart, POS, orders + status flow, receipts/payments, kitchen display (AJAX), tables, menu with secure uploads.
The schema already contains all tables (inventory, suppliers, reservations, attendance, ...) with demo data.
## Reports (admin/manager)
Reports -> pick From/To dates or a preset (Today, This week, This month, Last 30 days, This year). Shows sales, orders by type/status, payments by method, best-selling and most profitable items, inventory movement, low stock, comparison with the previous period and written analysis. Print/Save as PDF and CSV export (orders, products, payments).
## Product photos
Menu -> Add item (choose photo) or press Choose photo on any row, then Save. JPG/PNG/WEBP up to 2MB, stored in `assets/uploads/`. Photos show on the POS item cards.
## Not yet built (UI)
Inventory, suppliers, customers, reservations, staff/attendance, settings screens.

## Dashboards by role
- admin/manager/cashier/inventory: `admin/dashboard.php` (sales, orders, tables, stock, best sellers, recent orders, payments and staff performance today)
- waiter: `waiter/dashboard.php` (own orders and sales, ready-to-serve with Mark served, table overview, New order button)
- kitchen: `kitchen/dashboard.php` (new/preparing/ready counts, longest wait, items to prepare, low stock, link to kitchen display)

## Registration
Login page has Login / Register tabs. Anyone can register as Manager, Cashier, Waiter, Kitchen Staff or Inventory Staff (Administrator is not offered). New accounts are created inactive; the admin approves them in Users (sidebar) before they can log in. Passwords need 8+ characters with letters and numbers.
