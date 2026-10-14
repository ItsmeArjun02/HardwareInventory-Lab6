# Lab 6 — Report Notes (complete after running locally)

## Questions

**1. How would you handle a product quantity update failing after a transaction was created? How does Laravel's database transaction help?**

Create the inventory transaction and update the product quantity inside the same `DB::transaction()` closure. If the quantity update fails, an exception is thrown and Laravel rolls back the transaction, so the inventory movement record is not committed separately from the stock change. This prevents the transaction history and the product's quantity from disagreeing.

**2. What happens if an exception is thrown inside a `DB::transaction()` closure? How does Laravel handle rollback?**

Laravel rolls back the active database transaction and rethrows the exception. The controller can catch or convert the exception into a validation message, while the database remains unchanged by the failed operation. If the closure completes successfully, Laravel commits the transaction.

**3. Compare AJAX polling with WebSockets for real-time updates. What are the trade-offs?**

AJAX polling asks the server for updated data at a fixed interval (this project uses 30 seconds). It is simpler to implement and does not require a persistent connection, but updates are delayed until the next poll and frequent requests add server load. WebSockets keep a connection open so the server can push changes immediately with less repeated polling, but they require more infrastructure and more complex setup.

## Screenshot checklist (take these yourself after the project runs)

1. Product model code (`app/Models/Product.php`)
2. Product controller (`app/Http/Controllers/ProductController.php`)
3. Blade templates, including transaction and adjustment views
4. Custom CSS (`public/css/custom.css`)
5. Login page
6. Dashboard with statistics and polling timestamp
7. Product index
8. Add Product form
9. Product edit page
10. phpMyAdmin products table with data
11. GitHub repository with project files (after you push it)
12. Terminal showing successful Artisan commands and tests

Do not paste mock screenshots or claim tests succeeded unless you ran them on your own XAMPP/PHP setup. Name the final PDF as `Lab6_YourStudentId_YourLastName.pdf` per the handout.
