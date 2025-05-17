<!-- resources/views/auth/reset-password.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      background: #f4f6f8;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }

    .reset-container {
      background: #fff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      max-width: 400px;
      width: 100%;
    }

    .reset-container h1 {
      font-size: 24px;
      margin-bottom: 20px;
      text-align: center;
      color: #333;
    }

    .reset-container input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 14px;
    }

    .reset-container button {
      width: 100%;
      padding: 12px;
      background-color: #5E548E;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .reset-container button:hover {
      background-color: #4b4477;
    }

    .error {
      color: red;
      font-size: 13px;
      margin-top: -5px;
    }
  </style>
</head>
<body>

  <div class="reset-container">
    <h1>🔐 Reset Your Password</h1>

    <form method="POST" action="{{ route('password.update') }}">
      @csrf

      <input type="hidden" name="token" value="{{ request()->route('token') }}">

      <input type="email" name="email" placeholder="Email address" value="{{ old('email') }}" required>
      @error('email')
        <div class="error">{{ $message }}</div>
      @enderror

      <input type="password" name="password" placeholder="New password" required>
      @error('password')
        <div class="error">{{ $message }}</div>
      @enderror

      <input type="password" name="password_confirmation" placeholder="Confirm password" required>

      <button type="submit">Reset Password</button>
    </form>
  </div>

</body>
</html>
