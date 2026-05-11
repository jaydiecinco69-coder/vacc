<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Animal Bite Center Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center gradient-bg">
    <div class="max-w-md w-full space-y-8 p-8">
        <div class="bg-white rounded-lg shadow-xl p-8">
            <div class="text-center">
                <i class="fas fa-key text-5xl text-blue-600 mb-4"></i>
                <h2 class="text-3xl font-bold text-gray-900">Reset Password</h2>
                <p class="mt-2 text-gray-600">Enter your new password</p>
            </div>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <form class="mt-8 space-y-6" action="<?php echo BASE_URL; ?>auth/reset-password" method="POST">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password" name="password" type="password" required
                               class="pl-10 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Enter new password">
                    </div>
                </div>
                
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="confirm_password" name="confirm_password" type="password" required
                               class="pl-10 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Confirm new password">
                    </div>
                </div>
                
                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Reset Password
                    </button>
                </div>
                
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        <a href="<?php echo BASE_URL; ?>auth/login" 
                           class="font-medium text-blue-600 hover:text-blue-500">
                            <i class="fas fa-arrow-left mr-1"></i>
                            Back to Login
                        </a>
                    </p>
                </div>
            </form>
        </div>
        
        <div class="text-center text-white">
            <p class="text-sm">
                &copy; 2024 Animal Bite Center Management System
            </p>
        </div>
    </div>
</body>
</html>
