<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Levels</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
        .btn-secondary.red-outline {
            border: 1px solid #000; /* Black outline for delete button */
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
        .overdue {
            background-color: #ff4444; /* Red background for overdue */
        }
        .overdue:hover {
            background-color: #cc0000; /* Darker red on hover for overdue */
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
                    <li><a href="/dashboard" class="active">Dashboard</a></li>
                    <li><a href="/stocks">Stocks</a></li>
                    <li><a href="/pembelian">Pembelian</a></li>
                    <li><a href="/penjualan">Penjualan</a></li>
                    <li><a href="/suppliers">Suppliers</a></li>
                    <li><a href="/customers">Customers</a></li>
                </ul>
            </nav>
        </aside>
        <main class="main-content">
            <header class="header">
                <h1>User Levels</h1>
                <div class="user-info">
                    <span>{{ Auth::user()->name }}</span>
                    <button class="logout-button" onclick="document.getElementById('logout-form').submit();">Logout</button>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </header>
            <section class="content">
                <!-- Level 2 Users -->
                <h2>Level 2 Users</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dua as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <!-- Level 1 Users -->
                <h2>Level 1 Users</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($satu as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                @if (Auth::user()->level > 1)
                                    <td>
                                        <div class="action-icons">
                                            <a href="{{ route('level.edit', $user->id) }}" class="edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('level.destroy', $user->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="delete" style="background: none; border: none; cursor: pointer;" onclick="confirmation(event)">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    @else
                                    <td></td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Level 0 Users -->
                <h2>Level 0 Users</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($nol as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                @if (Auth::user()->level > 0)
                                    <td>
                                        <div class="action-icons">
                                            <a href="{{ route('level.edit', $user->id) }}" class="edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('level.destroy', $user->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="delete" style="background: none; border: none; cursor: pointer;" onclick="confirmation(event)">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        </main>
    </div>
    <script>
    function confirmation(event) {
        event.stopPropagation(); // Stop the event from propagating to the <tr> click event
        event.preventDefault();   // Prevent the form from submitting immediately

        swal({
            title: "Are you sure you want to delete this user?",
            text: "This action cannot be undone.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                // If the user confirms, submit the form
                event.target.closest('form').submit();
            }
        });
    }
    </script>
</body>
</html>
