<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pembelian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #fff;
            background-color: #1f1f1f;
        }
        .hamburger {
            display: none; /* Hide by default on larger screens */
            font-size: 1.5em;
            cursor: pointer;
            background: none;
            border: none;
            color: #fff;
        }
        .container {
            display: flex;
            height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: #2c2c2c;
            padding: 20px;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            width: 100px;
        }
        .menu {
            list-style-type: none;
            padding: 0;
        }
        .menu ul {
            list-style-type: none;
            padding: 0;
        }
        .menu li {
            margin: 10px 0;
        }
        .menu li a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 5px;
        }
        .menu li a:hover,
        .menu li a:focus {
            background-color: #3a3a3a;
        }
        .menu li a.active {
            background-color: #4caf50;
        }
        .menu li ul {
            display: none;
            padding-left: 20px;
        }
        .menu li:hover ul,
        .menu li:focus ul {
            display: block;
        }
        .main-content {
            flex-grow: 1;
            background-color: #2e2e2e;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .logout-button {
            background-color: #f44336;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .content {
            background-color: #3c3c3c;
            padding: 20px;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #5a5a5a;
            border-radius: 5px;
            background-color: #2c2c2c;
            color: #fff;
        }
        .form-actions {
            display: flex;
            justify-content: flex-start;
            gap: 10px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-primary {
            background-color: #4caf50;
            color: #fff;
        }
        .btn-secondary {
            background-color: #f44336;
            color: #fff;
        }
        /* Mobile-specific styles */
        @media (max-width: 480px) {
            .hamburger {
                display: block; /* Show hamburger on small screens */
            }

            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                width: 250px;
                height: 100%;
                z-index: 1000;
                overflow-x: hidden;
                background-color: #2c2c2c;
                padding: 20px;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .backdrop {
                display: none; /* Hide by default */
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black */
                z-index: 900; /* Behind sidebar but above content */
                transition: opacity 0.3s ease;
            }

            .backdrop.show {
                display: block;
            }

            .menu-toggle {
                display: block;
                background-color: #4caf50;
                color: #fff;
                padding: 10px;
                cursor: pointer;
                border: none;
                border-radius: 5px;
                margin: 10px;
            }

            .header {
                padding: 10px;
                align-items: center;
                justify-content: space-between;
            }

            .header h1 {
                font-size: 1.5em;
                align-self: center;
                text-align: center;
            }

            .main-content {
                padding: 10px; /* Reduce padding for smaller screens */
                margin-left: 0; /* No left margin on small screens */
            }

            .user-name {
                display: none; /* Hide user-name on small screens */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <nav class="menu">
                <ul>
                    <li><a href="/dashboard">Dashboard</a></li>
                    <li><a href="/stocks">Stocks</a></li>
                    <li><a href="/pembelian" class="active">Pembelian</a></li>
                    <li><a href="/penjualan">Penjualan</a></li>
                    <li><a href="/suppliers">Suppliers</a></li>
                    <li><a href="/customers">Customers</a></li>
                </ul>
            </nav>
        </aside>
        <div class="backdrop" id="backdrop" onclick="toggleSidebar()"></div>
        <main class="main-content">
            <header class="header">
                <button class="hamburger" onclick="toggleSidebar()">☰</button>
                <h1>Edit Pembelian</h1>
                <div class="user-info">
                    <span class="user-name">{{ Auth::user()->name }}</span>
                    <button class="logout-button" onclick="document.getElementById('logout-form').submit();">Logout</button>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </header>
            <section class="content">
                <h2>Edit Pembelian</h2>
                <form action="{{ route('pembelian.update', $pembelian->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="supplier-id">Supplier</label>
                        <select id="supplier-id" name="suppliers_id" required>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ $supplier->id == $pembelian->suppliers_id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="total">Harga Akhir</label>
                        <input type="number" id="total" name="total" step="0.01" min="0" value="{{ $pembelian->total }}" required>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Update Pembelian</button>
                    </div>
                </form>
            </section>
        </main>
    </div>
    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const backdrop = document.getElementById('backdrop');
            sidebar.classList.toggle('show');
            backdrop.classList.toggle('show');
        }
    </script>
</body>
</html>
