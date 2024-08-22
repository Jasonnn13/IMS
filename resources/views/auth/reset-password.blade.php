<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'system-ui', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #1f1f1f; /* Light gray background for the whole page */
            color: #333; /* Dark gray text color */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #2e2e2e; /* White background for the form */
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .container h2 {
            margin-bottom: 20px;
            text-align: center;
            color: #4a4a4a  ; /* Dark gray for the heading */
            font-size: 24px;
            font-weight: 700;
        }

        .container .form-group {
            margin-bottom: 15px;
        }

        .container label {
            display: block;
            margin-bottom: 5px;
            color: #4a4a4a; /* Medium gray for labels */
            font-weight: 500;
        }

        .container input {
            padding: 10px;
            border: 1px solid #d1d5db; /* Light gray border */
            border-radius: 5px;
            background-color: #f9fafb; /* Very light gray background for inputs */
            color: #333; /* Dark gray text color */
            font-size: 16px;
        }

        .container .submit-button {
            background-color: #4b5563; /* Dark gray for button */
            color: #ffffff; /* White text color */
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 700;
            text-align: center;
            display: inline-block;
        }

        .container .submit-button:hover {
            background-color: #374151; /* Darker gray for button hover */
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Reset Password</h2>
        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div class="form-group">
                <label for="email">{{ __('Email') }}</label>
                <input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">{{ __('Password') }}</label>
                <input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                <input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <button type="submit" class="submit-button">
                    {{ __('Reset Password') }}
                </button>
            </div>
        </form>
    </div>
</body>

</html>
