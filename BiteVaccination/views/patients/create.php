<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Patient - Animal Bite Center Management System</title>
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
                        <h1 class="text-3xl font-bold text-gray-900">Add New Patient</h1>
                        <p class="text-gray-600">Register a new patient in the system</p>
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

            <!-- Patient Registration Form -->
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
                                   value="<?php echo isset($_SESSION['form_data']['first_name']) ? $_SESSION['form_data']['first_name'] : ''; ?>">
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="last_name" name="last_name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Enter last name"
                                   value="<?php echo isset($_SESSION['form_data']['last_name']) ? $_SESSION['form_data']['last_name'] : ''; ?>">
                        </div>

                        <div>
                            <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Middle Name
                            </label>
                            <input type="text" id="middle_name" name="middle_name"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Enter middle name"
                                   value="<?php echo isset($_SESSION['form_data']['middle_name']) ? $_SESSION['form_data']['middle_name'] : ''; ?>">
                        </div>

                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Birth Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="birth_date" name="birth_date" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   value="<?php echo isset($_SESSION['form_data']['birth_date']) ? $_SESSION['form_data']['birth_date'] : ''; ?>">
                        </div>

                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                                Gender <span class="text-red-500">*</span>
                            </label>
                            <select id="gender" name="gender" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Gender</option>
                                <option value="male" <?php echo (isset($_SESSION['form_data']['gender']) && $_SESSION['form_data']['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
                                <option value="female" <?php echo (isset($_SESSION['form_data']['gender']) && $_SESSION['form_data']['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
                                <option value="other" <?php echo (isset($_SESSION['form_data']['gender']) && $_SESSION['form_data']['gender'] == 'other') ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>

                        <div>
                            <label for="blood_type" class="block text-sm font-medium text-gray-700 mb-2">
                                Blood Type
                            </label>
                            <select id="blood_type" name="blood_type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Blood Type</option>
                                <option value="A+" <?php echo (isset($_SESSION['form_data']['blood_type']) && $_SESSION['form_data']['blood_type'] == 'A+') ? 'selected' : ''; ?>>A+</option>
                                <option value="A-" <?php echo (isset($_SESSION['form_data']['blood_type']) && $_SESSION['form_data']['blood_type'] == 'A-') ? 'selected' : ''; ?>>A-</option>
                                <option value="B+" <?php echo (isset($_SESSION['form_data']['blood_type']) && $_SESSION['form_data']['blood_type'] == 'B+') ? 'selected' : ''; ?>>B+</option>
                                <option value="B-" <?php echo (isset($_SESSION['form_data']['blood_type']) && $_SESSION['form_data']['blood_type'] == 'B-') ? 'selected' : ''; ?>>B-</option>
                                <option value="O+" <?php echo (isset($_SESSION['form_data']['blood_type']) && $_SESSION['form_data']['blood_type'] == 'O+') ? 'selected' : ''; ?>>O+</option>
                                <option value="O-" <?php echo (isset($_SESSION['form_data']['blood_type']) && $_SESSION['form_data']['blood_type'] == 'O-') ? 'selected' : ''; ?>>O-</option>
                                <option value="AB+" <?php echo (isset($_SESSION['form_data']['blood_type']) && $_SESSION['form_data']['blood_type'] == 'AB+') ? 'selected' : ''; ?>>AB+</option>
                                <option value="AB-" <?php echo (isset($_SESSION['form_data']['blood_type']) && $_SESSION['form_data']['blood_type'] == 'AB-') ? 'selected' : ''; ?>>AB-</option>
                            </select>
                        </div>

                        <!-- Contact Information -->
                        <div class="md:col-span-2 mt-6">
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 border-b pb-2">
                                <i class="fas fa-phone mr-2 text-blue-600"></i>Contact Information
                            </h3>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="phone" name="phone" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="+63 XXX XXX XXXX"
                                   value="<?php echo isset($_SESSION['form_data']['phone']) ? $_SESSION['form_data']['phone'] : ''; ?>">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address
                            </label>
                            <input type="email" id="email" name="email"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="patient@email.com"
                                   value="<?php echo isset($_SESSION['form_data']['email']) ? $_SESSION['form_data']['email'] : ''; ?>">
                        </div>

                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                Address <span class="text-red-500">*</span>
                            </label>
                            <textarea id="address" name="address" rows="3" required
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Enter complete address"><?php echo isset($_SESSION['form_data']['address']) ? $_SESSION['form_data']['address'] : ''; ?></textarea>
                        </div>

                        <!-- Emergency Contact -->
                        <div class="md:col-span-2 mt-6">
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 border-b pb-2">
                                <i class="fas fa-exclamation-triangle mr-2 text-blue-600"></i>Emergency Contact
                            </h3>
                        </div>

                        <div>
                            <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Emergency Contact Name
                            </label>
                            <input type="text" id="emergency_contact_name" name="emergency_contact_name"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Emergency contact person"
                                   value="<?php echo isset($_SESSION['form_data']['emergency_contact_name']) ? $_SESSION['form_data']['emergency_contact_name'] : ''; ?>">
                        </div>

                        <div>
                            <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Emergency Contact Phone
                            </label>
                            <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="+63 XXX XXX XXXX"
                                   value="<?php echo isset($_SESSION['form_data']['emergency_contact_phone']) ? $_SESSION['form_data']['emergency_contact_phone'] : ''; ?>">
                        </div>

                        <!-- Medical Information -->
                        <div class="md:col-span-2 mt-6">
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 border-b pb-2">
                                <i class="fas fa-heartbeat mr-2 text-blue-600"></i>Medical Information
                            </h3>
                        </div>

                        <div class="md:col-span-2">
                            <label for="allergies" class="block text-sm font-medium text-gray-700 mb-2">
                                Allergies
                            </label>
                            <textarea id="allergies" name="allergies" rows="2"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="List any known allergies"><?php echo isset($_SESSION['form_data']['allergies']) ? $_SESSION['form_data']['allergies'] : ''; ?></textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label for="medical_history" class="block text-sm font-medium text-gray-700 mb-2">
                                Medical History
                            </label>
                            <textarea id="medical_history" name="medical_history" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Relevant medical history or conditions"><?php echo isset($_SESSION['form_data']['medical_history']) ? $_SESSION['form_data']['medical_history'] : ''; ?></textarea>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4 mt-8">
                        <a href="<?php echo BASE_URL; ?>patients" 
                           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-save mr-2"></i>Register Patient
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        // Calculate age from birth date
        document.getElementById('birth_date').addEventListener('change', function() {
            const birthDate = new Date(this.value);
            const today = new Date();
            const age = Math.floor((today - birthDate) / (365.25 * 24 * 60 * 60 * 1000));
            
            if (age < 0) {
                this.setCustomValidity('Birth date cannot be in the future');
            } else if (age > 120) {
                this.setCustomValidity('Please enter a valid birth date');
            } else {
                this.setCustomValidity('');
            }
        });

        // Phone number formatting
        document.getElementById('phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0) {
                if (value.startsWith('63')) {
                    value = '+' + value;
                } else {
                    value = '+63' + value;
                }
            }
            e.target.value = value;
        });

        document.getElementById('emergency_contact_phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0) {
                if (value.startsWith('63')) {
                    value = '+' + value;
                } else {
                    value = '+63' + value;
                }
            }
            e.target.value = value;
        });

        // Form validation
        document.getElementById('patientForm').addEventListener('submit', function(e) {
            const phone = document.getElementById('phone').value;
            const birthDate = document.getElementById('birth_date').value;
            
            if (!phone || phone.replace(/\D/g, '').length < 10) {
                e.preventDefault();
                alert('Please enter a valid phone number');
                return;
            }
            
            if (!birthDate) {
                e.preventDefault();
                alert('Please select a birth date');
                return;
            }
        });
    </script>
</body>
</html>
