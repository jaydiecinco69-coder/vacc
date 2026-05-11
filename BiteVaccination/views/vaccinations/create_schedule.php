<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Vaccination Schedule - BiteCare Management System</title>
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
                    <li>
                        <a href="<?php echo BASE_URL; ?>vaccinations/schedule" class="flex items-center space-x-3 text-gray-700 hover:bg-gray-100 p-3 rounded-lg">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Vaccination Schedule</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>vaccinations/create-schedule" class="flex items-center space-x-3 text-green-600 bg-green-50 p-3 rounded-lg">
                            <i class="fas fa-plus-circle"></i>
                            <span>Create Schedule</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Create Vaccination Schedule</h1>
                        <p class="text-gray-600">Schedule upcoming vaccinations for patients</p>
                    </div>
                    <a href="<?php echo BASE_URL; ?>vaccinations/schedule" 
                       class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Schedule
                    </a>
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

            <!-- Debug Information -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
                <div class="flex items-start">
                    <div class="bg-yellow-100 p-2 rounded-full mr-3">
                        <i class="fas fa-bug text-yellow-600"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-yellow-900">Debug Information</h4>
                        <p class="text-yellow-800 text-sm mt-1">
                            Database Connection: <?php echo $db_test['success'] ? '✅ Connected' : '❌ Failed'; ?>
                        </p>
                        <p class="text-yellow-800 text-sm mt-1">
                            Total patients in DB: <?php echo $db_test['patient_count'] ?? 'Unknown'; ?>
                        </p>
                        <p class="text-yellow-800 text-sm mt-1">
                            Patients retrieved: <?php echo count($patients); ?>
                        </p>
                        <?php if (!$db_test['success']): ?>
                        <p class="text-red-800 text-sm mt-1">
                            Database Error: <?php echo htmlspecialchars($db_test['error'] ?? 'Unknown error'); ?>
                        </p>
                        <?php endif; ?>
                        <?php if (empty($patients) && $db_test['success']): ?>
                        <p class="text-yellow-800 text-sm mt-1">
                            ⚠️ Database has patients but query returned empty. Check query logic.
                        </p>
                        <?php elseif (!empty($patients)): ?>
                        <p class="text-yellow-800 text-sm mt-1">
                            First patient: <?php echo htmlspecialchars($patients[0]['first_name'] . ' ' . $patients[0]['last_name'] . ' (ID: ' . $patients[0]['id'] . ')'); ?>
                        </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Information Section -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                <div class="flex items-start">
                    <div class="bg-blue-100 p-2 rounded-full mr-3">
                        <i class="fas fa-info-circle text-blue-600"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-blue-900">Creating Vaccination Schedules</h4>
                        <ul class="text-blue-800 text-sm mt-2 space-y-1">
                            <li>• All patients in the system are available for scheduling</li>
                            <li>• Patient status shows their appointment and vaccination history</li>
                            <li>• You can create schedules for patients regardless of appointment status</li>
                            <li>• Use quick templates for common vaccination types</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Create Schedule Form -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <form action="<?php echo BASE_URL; ?>vaccinations/storeSchedule" method="POST" class="space-y-6">
                    <!-- Patient Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-2"></i>Select Patient
                        </label>
                        <select name="patient_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Choose a patient...</option>
                            <?php if (empty($patients)): ?>
                                <option value="" disabled>No patients found in the system</option>
                            <?php else: ?>
                                <?php foreach ($patients as $patient): ?>
                                    <?php 
                                    $statusInfo = '';
                                    $statusColor = 'text-gray-600';
                                    
                                    if (!empty($patient['appointment_statuses'])) {
                                        $statuses = explode(',', $patient['appointment_statuses']);
                                        if (in_array('confirmed', $statuses) || in_array('completed', $statuses)) {
                                            $statusInfo = ' ✓ Confirmed';
                                            $statusColor = 'text-green-600';
                                        } elseif (in_array('scheduled', $statuses)) {
                                            $statusInfo = ' ⏳ Scheduled';
                                            $statusColor = 'text-blue-600';
                                        }
                                    } else {
                                        $statusInfo = ' (No appointments)';
                                        $statusColor = 'text-gray-500';
                                    }
                                    
                                    $vaccinationInfo = $patient['scheduled_vaccinations'] > 0 ? " • {$patient['scheduled_vaccinations']} vaccinations" : '';
                                    $lastAppointment = !empty($patient['last_appointment_date']) ? " • Last: " . date('M j, Y', strtotime($patient['last_appointment_date'])) : '';
                                    ?>
                                    <option value="<?php echo $patient['id']; ?>">
                                        <?php echo htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']); ?> 
                                        (ID: <?php echo htmlspecialchars($patient['patient_id']); ?>)
                                        <?php if (!empty($patient['phone'])): ?>
                                        • <?php echo htmlspecialchars($patient['phone']); ?>
                                        <?php endif; ?>
                                        <?php echo $statusInfo . $vaccinationInfo . $lastAppointment; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php if (empty($patients)): ?>
                        <div class="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-yellow-800 text-sm">
                                <i class="fas fa-info-circle mr-2"></i>
                                No patients found. You need to create patients first before creating vaccination schedules.
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Vaccine Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-syringe mr-2"></i>Vaccine Type
                        </label>
                        <select name="vaccine_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select vaccine type...</option>
                            <option value="anti_rabies">Anti-Rabies</option>
                            <option value="immunoglobulin">Immunoglobulin</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Vaccine Brand -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-prescription-bottle mr-2"></i>Vaccine Brand
                            </label>
                            <input type="text" name="vaccine_brand" required 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="e.g., Verorab, Tetanus Toxoid">
                        </div>

                        <!-- Batch Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-barcode mr-2"></i>Batch Number
                            </label>
                            <input type="text" name="batch_number" required 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="e.g., VRB2024001">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Dose Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-hashtag mr-2"></i>Dose Number
                            </label>
                            <input type="number" name="dose_number" min="1" max="10" required 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="1">
                        </div>

                        <!-- Administration Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar mr-2"></i>Administration Date
                            </label>
                            <input type="date" name="administration_date" required 
                                   min="<?php echo date('Y-m-d'); ?>"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Administration Time -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-clock mr-2"></i>Administration Time
                            </label>
                            <input type="time" name="administration_time" required 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <!-- Administration Site -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt mr-2"></i>Administration Site
                        </label>
                        <select name="administration_site" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select administration site...</option>
                            <option value="left_deltoid">Left Deltoid</option>
                            <option value="right_deltoid">Right Deltoid</option>
                            <option value="left_thigh">Left Thigh</option>
                            <option value="right_thigh">Right Thigh</option>
                        </select>
                    </div>

                    <!-- Next Dose Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-plus mr-2"></i>Next Dose Date (Optional)
                        </label>
                        <input type="date" name="next_dose_date" 
                               min="<?php echo date('Y-m-d'); ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Leave empty if this is the final dose">
                    </div>

                    <!-- Appointment Link -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-link mr-2"></i>Link to Appointment (Optional)
                        </label>
                        <select name="appointment_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">No appointment link</option>
                            <?php if (!empty($appointments)): ?>
                                <?php foreach ($appointments as $appointment): ?>
                                    <option value="<?php echo $appointment['id']; ?>">
                                        <?php echo htmlspecialchars($appointment['first_name'] . ' ' . $appointment['last_name']); ?> - 
                                        <?php echo date('M j, Y', strtotime($appointment['appointment_date'])); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Quick Schedule Templates -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-magic mr-2"></i>Quick Schedule Templates
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <button type="button" onclick="applyTemplate('anti_rabies')" 
                                    class="p-4 border border-gray-300 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition">
                                <i class="fas fa-shield-virus text-blue-600 mb-2"></i>
                                <h4 class="font-medium text-gray-900">Anti-Rabies Series</h4>
                                <p class="text-sm text-gray-600">5-dose schedule (Days 0, 3, 7, 14, 28)</p>
                            </button>
                            <button type="button" onclick="applyTemplate('tetanus')" 
                                    class="p-4 border border-gray-300 rounded-lg hover:bg-green-50 hover:border-green-300 transition">
                                <i class="fas fa-medkit text-green-600 mb-2"></i>
                                <h4 class="font-medium text-gray-900">Tetanus Booster</h4>
                                <p class="text-sm text-gray-600">Single dose schedule</p>
                            </button>
                            <button type="button" onclick="applyTemplate('immunoglobulin')" 
                                    class="p-4 border border-gray-300 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition">
                                <i class="fas fa-vial text-purple-600 mb-2"></i>
                                <h4 class="font-medium text-gray-900">Immunoglobulin</h4>
                                <p class="text-sm text-gray-600">Single dose with wound care</p>
                            </button>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4 border-t pt-6">
                        <a href="<?php echo BASE_URL; ?>vaccinations/schedule" 
                           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <i class="fas fa-save mr-2"></i>Create Schedule
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        function applyTemplate(type) {
            const form = document.querySelector('form');
            
            switch(type) {
                case 'anti_rabies':
                    form.querySelector('[name="vaccine_type"]').value = 'anti_rabies';
                    form.querySelector('[name="vaccine_brand"]').value = 'Verorab';
                    form.querySelector('[name="dose_number"]').value = '1';
                    form.querySelector('[name="administration_site"]').value = 'left_deltoid';
                    break;
                case 'immunoglobulin':
                    form.querySelector('[name="vaccine_type"]').value = 'immunoglobulin';
                    form.querySelector('[name="vaccine_brand"]').value = 'Human Rabies Immunoglobulin';
                    form.querySelector('[name="dose_number"]').value = '1';
                    form.querySelector('[name="administration_site"]').value = 'right_thigh';
                    break;
            }
        }

        // Set default date to today
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.querySelector('[name="administration_date"]');
            if (dateInput && !dateInput.value) {
                dateInput.value = new Date().toISOString().split('T')[0];
            }
        });
    </script>
</body>
</html>
