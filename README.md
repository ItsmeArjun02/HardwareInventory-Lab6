# Hardware Inventory System — Lab Exercise 6

Laravel 12 project extended for the **Real-Time Inventory Tracking System** laboratory. The original Breeze login, product CRUD, and inventory report screens are retained.

## Included Lab 6 features

- `InventoryTransaction` model with `product()` and `user()` relationships, transaction scopes, quantity-before/after balances, references, notes, and timestamps.
- `InventoryTransactionObserver` automatically adjusts stock for stock-in, return, stock-out, and damaged transactions. The transaction controller wraps each stock movement in `DB::transaction()` and rejects quantities greater than available stock.
- MySQL `BEFORE INSERT` trigger as a second safeguard against excessive stock-out/damage transactions. The migration removes the old after-insert trigger so stock is not changed twice. SQLite test environments skip MySQL-only trigger SQL.
- Event/listener audit logging, user accountability, immutable transaction history, filters, pagination, transaction details, and product-level history with before/after running balances.
- Database + queued mail low-stock notifications. Alerts are queued when stock crosses from above the reorder level to at/below it; the notification includes name, SKU, current quantity, reorder level, and suggested action.
- Notification list, unread count, mark-read and mark-all-read actions.
- Authenticated JSON endpoints: `/api/dashboard/stats`, `/api/products/low-stock`, and `/api/transactions/recent`.
- Dashboard AJAX polling every 30 seconds with an updated timestamp and error message.
- Physical stock reconciliation form that calculates the difference and records it through the transaction observer.
- Product opening quantities are recorded as an initial stock-in transaction; editing product details does not silently overwrite quantity.
- Feature tests for stock-in, stock-out validation, and adjustment.

## Requirements

- PHP 8.2+ with extensions required by Laravel (including `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`, `ctype`, `fileinfo`, and `bcmath` as applicable)
- Composer
- Node.js and npm (to rebuild frontend assets)
- XAMPP/MySQL or another supported database

## Setup on Windows / XAMPP

1. Extract this ZIP into `C:\xampp\htdocs\hardware-inventory` (or another folder you prefer).
2. Start Apache and MySQL in XAMPP. In phpMyAdmin create a database named `hardware_inventory` (utf8mb4).
3. Open the project folder in VS Code and open Terminal in the folder that contains `artisan` and `composer.json`.
4. Copy `.env.example` to `.env`. On PowerShell:
   ```powershell
   Copy-Item .env.example .env
   ```
5. Confirm `.env` has your local DB settings:
   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=hardware_inventory
   DB_USERNAME=root
   DB_PASSWORD=
   QUEUE_CONNECTION=database
   MAIL_MAILER=log
   ```
   If your MySQL root account has a password, enter it in `DB_PASSWORD`.
6. Install PHP dependencies and create the app key:
   ```powershell
   composer install
   php artisan key:generate
   ```
7. Install/build frontend assets:
   ```powershell
   npm install
   npm run build
   ```
8. Run **only pending migrations**; do not use `migrate:fresh` because it deletes existing tables/data:
   ```powershell
   php artisan migrate
   ```
9. Start the app in one terminal:
   ```powershell
   php artisan serve
   ```
10. Start the queue worker in a second terminal to process database and email notifications:
    ```powershell
    php artisan queue:work --tries=3
    ```
11. Visit `http://127.0.0.1:8000`. Use your existing login account or register a local development account.

If `php` or `composer` is not recognized, use the executable paths configured on your computer or open a new terminal after installing them. Do not overwrite an existing `.env` without keeping a backup.

## Safe verification checklist

1. Log in and open Dashboard, Products, Reports, Transactions, and Stock Adjustment.
2. Create a product with an opening quantity and confirm an `INITIAL-<SKU>` stock-in transaction appears.
3. Record stock-in and confirm quantity rises.
4. Record stock-out and confirm quantity falls.
5. Try stock-out greater than available quantity; it must be rejected and quantity must not change.
6. Reconcile a product to a different physical count and confirm the transaction history and quantity match.
7. When a product crosses to at/below reorder level, check Notifications. With `MAIL_MAILER=log`, email content is written to `storage/logs/laravel.log`.
8. Leave the Dashboard open and verify its data refreshes after 30 seconds.
9. Verify the three JSON endpoints while signed in.
10. Run automated tests with `php artisan test` in an environment that has the required PHP extensions.

## Important implementation notes

- The transaction log is an audit trail and transaction deletion is intentionally disabled. Reversals should be recorded as a new stock movement rather than deleting history.
- The trigger is MySQL-specific. Automated tests on SQLite use the observer and validation, and skip trigger creation.
- `MAIL_MAILER=log` is suitable for local demonstration. Configure a real mail transport only if the instructor requires actual email delivery.
- `.env`, `.git`, `vendor`, and `node_modules` are intentionally not included in the delivery ZIP. Install dependencies with Composer/npm after extraction. The original local `.env` may contain credentials and should not be shared.
