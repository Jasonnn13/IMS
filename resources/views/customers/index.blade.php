<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            overflow-y: auto;
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
            margin-bottom: 20px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #5a5a5a;
        }
        th {
            background-color: #4caf50;
        }
        tr:hover {
            background-color: #3a3a3a;
        }
        .search-form {
            margin-bottom: 20px;
        }
        .search-form input {
            margin-top: 20px;
            padding: 10px;
            width: calc(100% - 120px);
            border: 1px solid #4caf50;
            border-radius: 5px 0 0 5px;
            background-color: #3a3a3a;
            color: #fff;
        }
        .search-form button {
            padding: 10px;
            border: none;
            border-radius: 0 5px 5px 0;
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
        }
        .category-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .category-header h3 {
            margin: 0;
        }
        .action-icons {
            display: flex;
            gap: 10px;
        }
        .action-icons i {
            cursor: pointer;
            font-size: 18px;
            color: #fff;
        }
        .action-icons .edit {
            color: #4caf50;
        }
        .action-icons .delete {
            color: #f44336;
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
                    <li><a href="/pembelian">Pembelian</a></li>
                    <li><a href="/penjualan">Penjualan</a></li>
                    <li><a href="/suppliers">Suppliers</a></li>
                    <li><a href="/customers" class="active">Customers</a></li>
                </ul>
            </nav>
        </aside>
        <main class="main-content">
            <header class="header">
                <h1>Customers</h1>
                <div class="user-info">
                    <span>{{ Auth::user()->name }}</span>
                    <button class="logout-button" onclick="document.getElementById('logout-form').submit();">Logout</button>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </header>
            <section class="content">
            <div class="search-form">
                    <form action="{{ route('customers.index') }}" method="GET">
                        <input type="text" name="search" placeholder="Search by Name..." value="{{ $search }}">
                        <button type="submit">Search</button>
                    </form>
                </div>
                <div class="category-header">
                    <h3>Customers List</h3>
                    <a href="{{ route('customers.create') }}" class="btn btn-primary">Add Customer</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Contact Information</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                            <tr onclick="window.location='{{ route('penjualan.index', ['search' => $customer->name]) }}'">
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->contact_information }}</td>
                                <td>
                                    <div class="action-icons">
                                        <a href="{{ route('customers.edit', $customer->id) }}" class="edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete" style="background: none; border: none; cursor: pointer;" onclick="confirmation(event)">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                    </div>
                                    
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        </main>
    </div>
    <script>
        // Pass user level from Blade to JavaScript
        const userLevel = @json(Auth::user()->level);

        function confirmation(event) {
            event.preventDefault(); // Prevent the form from submitting immediately
            event.stopPropagation(); // Stop the event from propagating to the <tr> click event

            if (userLevel < 2) {
                swal({
                    title: "Sorry, you don't have permission to delete this customer",
                    text: "This action cannot be undone.",
                    icon: "error",
                    button: "OK",
                });
            } else {
                swal({
                    title: "Are you sure you want to delete this customer?",
                    text: "This action cannot be undone.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        // If the user confirms, reload the page
                        event.target.closest('form').submit();
                    }
                });
            }
        }
    </script>
</body>
</html>
