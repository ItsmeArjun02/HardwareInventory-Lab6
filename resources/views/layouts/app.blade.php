<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hardware Inventory')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">    
</head>

<body>

<div class="topbar">
    <div>
        <strong>Hardware Inventory System</strong>
    </div>

    <div>
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('products.index') }}">Products</a>
        <a href="{{ route('reports.inventory') }}">Reports</a>
        <a href="{{ route('inventory-transactions.index') }}">Transactions</a>
        <a href="{{ route('adjustments.create') }}">Stock Adjustment</a>
        <a href="{{ route('notifications.index') }}" aria-label="Notifications">🔔 Notifications ({{ auth()->user()?->unreadNotifications()->count() ?? 0 }})</a>

        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-danger" style="margin-left:15px;">
                Logout
            </button>
        </form>
    </div>
</div>

<div class="container">

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')

</div>

</body>
</html>