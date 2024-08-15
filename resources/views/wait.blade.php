<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waiting Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1f1f1f;
            color: white;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden; /* Ensure no scroll bars appear */
        }

        .header {
            width: 100%;
            padding: 10px;
            background-color: #333;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            position: absolute;
            top: 0;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logout-button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .logout-button:hover {
            background-color: #2980b9;
        }

        .waiting-container {
            text-align: center;
            margin-top: 60px; /* Adjust margin to avoid overlap with header */
        }

        .spinner {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        p {
            margin-top: 15px;
            font-size: 18px;
            color: white; /* Corrected color */
        }
    </style>
</head>

<body>
    <header class="header">
        <div class="user-info">
            <span>{{ Auth::user()->name }}</span>
            <button class="logout-button" onclick="document.getElementById('logout-form').submit();">Logout</button>
        </div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </header>

    <div class="waiting-container">
        <div class="spinner"></div>
        <p>Please wait for admin verification...</p>
    </div>
</body>
</html>
