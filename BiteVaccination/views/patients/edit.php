<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Patient - Animal Bite Center Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <i class="fas fa-shield-virus text-blue-600 text-2xl mr-3"></i>
                    <span class="font-bold text-xl text-gray-800">BiteCare System</span>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <img src="https://picsum.photos/seed/user/40/40.jpg" alt="Profile" class="w-8 h-8 rounded-full">
                        <span class="text-sm font-medium"><?php echo $_SESSION['full_name']; ?></span>
                    </div>
                    <a href="<?php echo BASE_URL; ?>auth/logout" class="text-red-600 hover:text-red-800">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="flex">
        <aside class="w-64 bg-white shadow-lg h-screen sticky top-0">
            <nav class="p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="<?php echo BASE_URL; ?>dashboard" class="flex items-center space-x-3 text-gray-700 hover:bg-gray-100 p-3 rounded-lg">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>patients" class="flex items-center space-x-3 text-blue-600 bg-blue-50 p-3 rounded-lg">
                            <i class="fas fa-users"></i>
                            <span>Patients</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>appointments" class="flex items-center space-x-3 text-gray-700 hover:bg-gray-100 p-3 rounded-lg">
                            <i class="fas fa-calendar-check"></i>
                            <span>Appointments</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>vaccinations" class="flex items-center space-x-3 text-gray-700 hover:bg-gray-100 p-3 rounded-lg">
                            <i class="fas fa-syringe"></i>
                            <span>Vaccinations</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center">
                    <a href="<?php echo BASE_URL; ?>patients" class="text-blue-600 hover:text-blue-800 mr-3">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Edit Patient</h1>
                        <p class="text-gray-600">Update patient information in the system</p>
                    </div>
                </div>
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

            <!-- Patient Edit Form -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <form method="POST" id="patientForm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Personal Information -->
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 border-b pb-2">
                                <i class="fas fa-user mr-2 text-blue-600"></i>Personal Information
                            </h3>
                        </div>

                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="first_name" name="first_name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Enter first name"
                                   value="<?php echo htmlspecialchars($patient['first_name'] ?? ''); ?>">
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="last_name" name="last_name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Enter last name"
                                   value="<?php echo htmlspecialchars($patient['last_name'] ?? ''); ?>">
                        </div>

                        <div>
                            <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Middle Name
                            </label>
                            <input type="text" id="middle_name" name="middle_name"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Enter middle name"
                                   value="<?php echo htmlspecialchars($patient['middle_name'] ?? ''); ?>">
                        </div>

                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Birth Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="birth_date" name="birth_date" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   value="<?php echo htmlspecialchars($patient['birth_date'] ?? ''); ?>">
                        </div>

                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                                Gender <span class="text-red-500">*</span>
                            </label>
                            <select id="gender" name="gender" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Gender</option>
                                <option value="Male" <?php echo (isset($patient['gender']) && $patient['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo (isset($patient['gender']) && $patient['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                <option value="Other" <?php echo (isset($patient['gender']) && $patient['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>

                        <div>
                            <label for="contact_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Contact Number <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="contact_number" name="contact_number" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Enter contact number"
                                   value="<?php echo htmlspecialchars($patient['contact_number'] ?? ''); ?>">
                        </div>

                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                Address <span class="text-red-500">*</span>
                            </label>
                            <textarea id="address" name="address" rows="3" required
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Enter complete address"><?php echo htmlspecialchars($patient['address'] ?? ''); ?></textarea>
                        </div>

                        <!-- Emergency Contact -->
                        <div class="md:col-span-2 mt-6">
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 border-b pb-2">
                                <i class="fas fa-phone-alt mr-2 text-blue-600"></i>Emergency Contact
                            </h3>
                        </div>

                        <div>
                            <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Emergency Contact Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="emergency_contact_name" name="emergency_contact_name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Enter emergency contact name"
                                   value="<?php echo htmlspecialchars($patient['emergency_contact_name'] ?? ''); ?>">
                        </div>

                        <div>
                            <label for="emergency_contact_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Emergency Contact Number <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="emergency_contact_number" name="emergency_contact_number" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Enter emergency contact number"
                                   value="<?php echo htmlspecialchars($patient['emergency_contact_phone'] ?? ''); ?>">
                        </div>

                                            </div>

                    <!-- Form Actions -->
                    <div class="mt-8 flex justify-end space-x-4">
                        <a href="<?php echo BASE_URL; ?>patients" 
                           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                            <i class="fas fa-save mr-2"></i>Update Patient
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        // Form validation
        document.getElementById('patientForm').addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });

        // Phone number validation
        document.getElementById('contact_number').addEventListener('input', function(e) {
            const value = e.target.value.replace(/\D/g, '');
            if (value.length >= 10) {
                e.target.setCustomValidity('');
            } else {
                e.target.setCustomValidity('Please enter a valid phone number (at least 10 digits)');
            }
        });

        document.getElementById('emergency_contact_number').addEventListener('input', function(e) {
            const value = e.target.value.replace(/\D/g, '');
            if (value.length >= 10) {
                e.target.setCustomValidity('');
            } else {
                e.target.setCustomValidity('Please enter a valid phone number (at least 10 digits)');
            }
        });
    </script>
</body>
</html>
