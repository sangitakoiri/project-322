<?php
session_start();

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Replace with actual authentication logic
    if ($username === 'admin' && $password === 'password') {
        $_SESSION['user_logged_in'] = true;
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .background-watermark {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://tse2.mm.bing.net/th?id=OIP.1WvHwGFGqXHmPfI61hfmggHaEK&pid=Api&P=0&w=300&h=300'); /* Path to your BSF logo */
            background-repeat: no-repeat;
            background-size: cover; /* Adjust based on your needs */
            background-position: center;
            opacity: 0.1; /* Adjust opacity for watermark effect */
            z-index: -1; /* Ensure the watermark is behind the content */
        }

        .container {
            max-width: 480px;
            width: 100%;
            position: relative;
            z-index: 1;
        }

        form {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
            padding: 30px;
            position: relative;
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        h2 {
            margin-bottom: 20px;
            color: #4a5c6c;
            font-size: 2em;
            font-weight: 700;
        }

        .input-container {
            position: relative;
            margin-bottom: 15px;
        }

        .input-container i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        input[type="text"], input[type="password"] {
            width: calc(100% - 50px);
            padding: 12px;
            padding-left: 40px; /* Space for the icon */
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #4a5c6c;
            outline: none;
        }

        .password-container {
            position: relative;
        }

        .password-container i {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
        }

        input[type="submit"] {
            background-color: #4a5c6c;
            color: #fff;
            border: none;
            padding: 12px;
            font-size: 18px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        input[type="submit"]:hover {
            background-color: #365a5a;
            transform: translateY(-2px);
        }

        .error {
            color: #d8000c;
            background-color: #ffdddd;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #d8000c;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
        }

        .success {
            color: #4a5c6c;
            background-color: #ddffdd;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #4a5c6c;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
        }

        @media (max-width: 600px) {
            form {
                width: 100%;
                padding: 20px;
            }

            input[type="text"], input[type="password"] {
                width: 100%;
            }
        }
    </style>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }
    </script>
</head>
<body>
    <div class="background-watermark"></div>
    <div class="container">
        <form method="POST" action="">
            <h2>Login</h2>
            <?php if (isset($error)): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <div class="success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <div class="input-container">
                <i class="fas fa-user"></i>
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="password-container">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <i id="password-icon" class="fas fa-eye" onclick="togglePassword()"></i>
            </div>
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
