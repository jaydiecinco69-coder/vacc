<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Appointment - Animal Bite Center Management System</title>
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
                        <a href="<?php echo BASE_URL; ?>patients" class="flex items-center space-x-3 text-gray-700 hover:bg-gray-100 p-3 rounded-lg">
                            <i class="fas fa-users"></i>
                            <span>Patients</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>appointments" class="flex items-center space-x-3 text-blue-600 bg-blue-50 p-3 rounded-lg">
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
                    <li>
                        <a href="<?php echo BASE_URL; ?>reports" class="flex items-center space-x-3 text-gray-700 hover:bg-gray-100 p-3 rounded-lg">
                            <i class="fas fa-chart-line"></i>
                            <span>Reports</span>
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
                    <a href="<?php echo BASE_URL; ?>appointments" class="text-blue-600 hover:text-blue-800 mr-3">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Edit Appointment</h1>
                        <p class="text-gray-600">Update appointment details</p>
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

            <!-- Appointment Form -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <form method="POST" id="appointmentForm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Appointment Details -->
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 border-b pb-2">
                                <i class="fas fa-calendar mr-2 text-blue-600"></i>Appointment Details
                            </h3>
                        </div>

                        <input type="hidden" name="patient_id" value="<?php echo htmlspecialchars($appointment['patient_id']); ?>">

                        <div>
                            <label for="appointment_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Appointment Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="appointment_date" name="appointment_date" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   value="<?php echo htmlspecialchars($appointment['appointment_date']); ?>">
                        </div>

                        <div>
                            <label for="appointment_time" class="block text-sm font-medium text-gray-700 mb-2">
                                Appointment Time <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="appointment_time" name="appointment_time" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   value="<?php echo htmlspecialchars($appointment['appointment_time']); ?>">
                        </div>

                        <div>
                            <label for="appointment_type" class="block text-sm font-medium text-gray-700 mb-2">
                                Appointment Type <span class="text-red-500">*</span>
                            </label>
                            <select id="appointment_type" name="appointment_type" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="initial_consultation" <?php echo $appointment['appointment_type'] === 'initial_consultation' ? 'selected' : ''; ?>>Initial Consultation</option>
                                <option value="vaccination" <?php echo $appointment['appointment_type'] === 'vaccination' ? 'selected' : ''; ?>>Vaccination</option>
                                <option value="follow_up" <?php echo $appointment['appointment_type'] === 'follow_up' ? 'selected' : ''; ?>>Follow Up</option>
                                <option value="emergency" <?php echo $appointment['appointment_type'] === 'emergency' ? 'selected' : ''; ?>>Emergency</option>
                            </select>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select id="status" name="status" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="scheduled" <?php echo $appointment['status'] === 'scheduled' ? 'selected' : ''; ?>>Scheduled</option>
                                <option value="confirmed" <?php echo $appointment['status'] === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                <option value="in_progress" <?php echo $appointment['status'] === 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                                <option value="completed" <?php echo $appointment['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                <option value="cancelled" <?php echo $appointment['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                <option value="no_show" <?php echo $appointment['status'] === 'no_show' ? 'selected' : ''; ?>>No Show</option>
                            </select>
                        </div>

                        <div>
                            <label for="staff_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Assigned Staff
                            </label>
                            <input type="number" id="staff_id" name="staff_id"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   value="<?php echo htmlspecialchars($appointment['staff_id'] ?? ''); ?>">
                        </div>

                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Notes
                            </label>
                            <textarea id="notes" name="notes" rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($appointment['notes'] ?? ''); ?></textarea>
                        </div>

                        <!-- Form Actions -->
                        <div class="md:col-span-2 flex justify-end space-x-4">
                            <a href="<?php echo BASE_URL; ?>appointments" 
                               class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </a>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                <i class="fas fa-save mr-2"></i>Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
