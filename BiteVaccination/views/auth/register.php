<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Animal Bite Center Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .password-toggle {
            cursor: pointer;
            color: #9ca3af;
            transition: color 0.3s;
        }
        .password-toggle:hover {
            color: #4b5563;
        }
    </style>
    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }
    </script>
</head>
<body class="min-h-screen flex items-center justify-center gradient-bg p-4">
    <div class="w-full max-w-4xl">
        <div class="bg-white rounded-lg shadow-2xl p-12">
            <div class="text-center mb-8">
                <i class="fas fa-user-plus text-5xl text-blue-600 mb-4"></i>
                <h2 class="text-3xl font-bold text-gray-900">Create Account</h2>
                <p class="mt-2 text-gray-600">Join Animal Bite Center Management System</p>
            </div>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    <?php 
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif; ?>
            
            <form class="mt-8" action="<?php echo BASE_URL; ?>auth/register" method="POST">
                
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                        <div class="mt-2 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input id="first_name" name="first_name" type="text" required
                                   class="pl-10 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="John"
                                   value="<?php echo isset($_SESSION['form_data']['first_name']) ? $_SESSION['form_data']['first_name'] : ''; ?>">
                        </div>
                    </div>
                    
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <div class="mt-2 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input id="last_name" name="last_name" type="text" required
                                   class="pl-10 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Doe"
                                   value="<?php echo isset($_SESSION['form_data']['last_name']) ? $_SESSION['form_data']['last_name'] : ''; ?>">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <div class="mt-2 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-phone text-gray-400"></i>
                            </div>
                            <input id="phone" name="phone" type="tel"
                                   class="pl-10 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="+63 XXX XXX XXXX"
                                   value="<?php echo isset($_SESSION['form_data']['phone']) ? $_SESSION['form_data']['phone'] : ''; ?>">
                        </div>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <div class="mt-2 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input id="email" name="email" type="email" required
                                   class="pl-10 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="your@email.com"
                                   value="<?php echo isset($_SESSION['form_data']['email']) ? $_SESSION['form_data']['email'] : ''; ?>">
                        </div>
                    </div>
                </div>

                
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="mt-2 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input id="password" name="password" type="password" required
                                   class="pl-10 pr-10 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Min. 6 characters">
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center password-toggle"
                                    onclick="togglePasswordVisibility('password', 'passwordIcon')">
                                <i id="passwordIcon" class="fas fa-eye-slash text-gray-400"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <div class="mt-2 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input id="confirm_password" name="confirm_password" type="password" required
                                   class="pl-10 pr-10 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Re-enter password">
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center password-toggle"
                                    onclick="togglePasswordVisibility('confirm_password', 'confirmPasswordIcon')">
                                <i id="confirmPasswordIcon" class="fas fa-eye-slash text-gray-400"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 mb-4 flex justify-center">
                    <button type="submit" 
                            class="group relative w-auto px-8 py-3 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                        <i class="fas fa-user-plus mr-2"></i>
                        Create Account
                    </button>
                </div>
                
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account? 
                        <a href="<?php echo BASE_URL; ?>auth/login" 
                           class="font-medium text-blue-600 hover:text-blue-500">
                            Sign in here
                        </a>
                    </p>
                </div>
            </form>
        </div>
        
        <div class="text-center text-white mt-4">
            <p class="text-sm">
                &copy; 2024 Animal Bite Center Management System
            </p>
        </div>
    </div>
</body>
</html>
