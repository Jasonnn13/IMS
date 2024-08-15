<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Items to Pembelian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #fff;
            background-color: #1f1f1f;
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
        .item {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #3c3c3c;
            border-radius: 5px;
            position: relative;
        }
        .item .form-group {
            margin-bottom: 10px;
        }
        .delete-button {
            position: relative;
            top: 10px;
            background-color: #f44336;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .hidden {
            display: none;
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
        <main class="main-content">
            <header class="header">
                <h1>Add Items to Pembelian</h1>
                <div class="user-info">
                    <span>{{ Auth::user()->name }}</span>
                    <button class="logout-button" onclick="document.getElementById('logout-form').submit();">Logout</button>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </header>
            <section class="content">
                <h2>Select Items to Add</h2>
                <form action="{{ route('rincianpembelian.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="pembelian_id" value="{{ $pembelian->id }}">
                    <input type="hidden" name="supplier_id" value="{{ $pembelian->suppliers_id }}">

                    <div class="form-actions">
                        <button type="button" class="btn btn-primary" onclick="addNewItem()">Add New Item</button>
                        <button type="button" class="btn btn-primary" onclick="addExistingItems()">Add Existing Items</button>
                        <button type="submit" class="btn btn-primary">Save Items</button>
                    </div>

                    <div id="items-container" class="hidden">
                        
                    </div>

                    <div id="new-items-container" class="hidden">

                    </div>
                </form>

            </section>
        </main>
    </div>

    <script>
        let existingItemsAdded = 0;
        let newItemIndex = 0;

        function addNewItem() {
            const container = document.getElementById('new-items-container');
            container.classList.remove('hidden');

            const newItemHTML = `
                <div class="item">
                <h3>New Items</h3>
                    <div class="form-group">
                        <label for="new-item-name-${newItemIndex}">Item Name</label>
                        <input type="text" id="new-item-name-${newItemIndex}" name="items[new][${newItemIndex}][name]" placeholder="Enter new item name" required>
                    </div>
                    <div class="form-group">
                        <label for="new-item-quantity-${newItemIndex}">Quantity</label>
                        <input type="number" id="new-item-quantity-${newItemIndex}" name="items[new][${newItemIndex}][quantity]" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="new-item-price-${newItemIndex}">Price</label>
                        <input type="number" id="new-item-price-${newItemIndex}" name="items[new][${newItemIndex}][price]" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="kode-${newItemIndex}">Kode</label>
                        <input type="text" id="kode-${newItemIndex}" name="items[new][${newItemIndex}][kode]" class="form-control" placeholder="Enter new item kode" required>
                    </div>
                    <button type="button" class="delete-button" onclick="removeItem(this)">Delete</button>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', newItemHTML);
            newItemIndex++;
        }

        function addExistingItems() {
            const container = document.getElementById('items-container');
            container.classList.remove('hidden');

            const existingItemsHTML = `
                <div class="item">
                <h3>Existing Items</h3>
                    <div class="form-group">
                        <label for="stock_id-${existingItemsAdded}">Select Existing Item</label>
                        <select id="stock_id-${existingItemsAdded}" name="items[existing][${existingItemsAdded}][stock_id]" required>
                            @foreach($stocks as $stock)
                                <option value="{{ $stock->id }}">{{ $stock->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quantity-${existingItemsAdded}">Quantity</label>
                        <input type="number" id="quantity-${existingItemsAdded}" name="items[existing][${existingItemsAdded}][quantity]" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="price-${existingItemsAdded}">Price</label>
                        <input type="number" id="price-${existingItemsAdded}" name="items[existing][${existingItemsAdded}][price]" step="0.01" min="0" required>
                    </div>
                    <button type="button" class="delete-button" onclick="removeItem(this)">Delete</button>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', existingItemsHTML);
            existingItemsAdded++;
        }

        function removeItem(button) {
            const item = button.closest('.item');
            item.remove();
        }
    </script>
</body>
</html>
