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
        .hamburger {
            display: none; /* Hide by default on larger screens */
            font-size: 1.5em;
            cursor: pointer;
            background: none;
            border: none;
            color: #fff;
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
        .warning {
            color: #f44336;
            margin-top: 10px;
            display: none;
        }
        /* Custom CSS for the autocomplete suggestion box */
        .ui-autocomplete {
            background-color: #2e2e2e !important; /* Matches the body background color */
            color: #3a3a3a !important;
            border: 1px solid #5a5a5a !important;
            z-index: 1000 !important;
            border-radius: 5px !important;
        }

        /* Customize the individual item styles */
        .ui-menu-item {
            padding: 10px !important;
            font-size: 14px !important;
            background-color: #2e2e2e !important; /* Matches the suggestion box background */
            border-bottom: 1px solid #5a5a5a !important;
            color: #3a3a3a !important;
        }

        /* Target the item wrapper directly for hover and active states */
        .ui-menu-item:hover > .ui-menu-item-wrapper,
        .ui-menu-item.ui-state-active > .ui-menu-item-wrapper,
        .ui-menu-item.ui-state-focus > .ui-menu-item-wrapper {
            background-color: #3a3a3a !important; /* Slightly lighter gray for the active item */
            color: #3a3a3a !important; /* Ensure text color remains white */
            border: none !important;
        }

        /* Ensure the font color is white */
        .ui-menu-item .ui-menu-item-wrapper {
            color: #ffffff !important;
            font-family: 'Arial', sans-serif !important;
        }

        /* Hide the default focus border */
        .ui-helper-hidden-accessible {
            display: none !important;
        }
        .ui-helper-hidden-accessible{
            background-color: #3a3a3a !important; /* Slightly lighter gray for the active item */
            color: #3a3a3a !important; /* Ensure text color remains white */
        }
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
    <!-- jQuery and jQuery UI -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/smoothness/jquery-ui.css">
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
                <h1>Add Items to Pembelian</h1>
                <div class="user-info">
                    <span class="user-name">{{ Auth::user()->name }}</span>
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

        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const backdrop = document.getElementById('backdrop');
            sidebar.classList.toggle('show');
            backdrop.classList.toggle('show');
        }
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
                        <label for="stock-input-${existingItemsAdded}">Select Existing Item</label>
                        <input type="text" id="stock-input-${existingItemsAdded}" name="items[existing][${existingItemsAdded}][name]" class="form-control" placeholder="Enter item name" required>
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

            // Initialize autocomplete for the newly added input
            $(`#stock-input-${existingItemsAdded - 1}`).autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: '/stocks/autocomplete',
                        dataType: 'json',
                        data: {
                            term: request.term
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                select: function(event, ui) {
                    $(this).val(ui.item.label); 
                }
            });
        }

        function removeItem(button) {
            button.parentElement.remove();
        }
    </script>
</body>
</html>
