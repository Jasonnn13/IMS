<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1f1f1f;
            color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .register-container {
            background-color: #2e2e2e;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            width: 400px;
        }

        .register-container h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        .register-container form {
            display: flex;
            flex-direction: column;
        }

        .register-container label {
            margin-bottom: 5px;
            color: #ccc;
        }

        .register-container input {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #4caf50;
            border-radius: 5px;
            background-color: #3a3a3a;
            color: #fff;
        }

        .register-container .buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .register-container .buttons a,
        .register-container .buttons button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
        }

        .register-container .buttons button.logout-button {
            background-color: #f44336;
        }

        .register-container .buttons a:hover,
        .register-container .buttons button:hover {
            background-color: #3a3a3a;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <h2>Register</h2>
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <label for="name">Name</label>
            <input type="text" id="name" name="name" :value="old('name')" required autofocus autocomplete="name">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />

            <!-- Email Address -->
            <label for="email">Email</label>
            <input type="email" id="email" name="email" :value="old('email')" required autocomplete="username">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />

            <!-- Password -->
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required autocomplete="new-password">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />

            <!-- Confirm Password -->
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />

            <div class="buttons">
                <a href="{{ url('/') }}">Already registered?</a>
                <button type="submit" class="btn btn-primary">Register</button>
            </div>
        </form>
    </div>
</body>

</html>
