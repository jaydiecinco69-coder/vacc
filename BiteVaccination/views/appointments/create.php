<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Appointment - BiteCare Patient Portal</title>
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
                    <span class="font-bold text-xl text-gray-800"><?php echo (isset($userRole) && $userRole === ROLE_PATIENT) ? 'BiteCare Patient Portal' : 'BiteCare System'; ?></span>
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
                    <?php if (isset($userRole) && $userRole === ROLE_PATIENT): ?>
                        <li>
                            <a href="<?php echo BASE_URL; ?>dashboard" class="flex items-center space-x-3 text-gray-700 hover:bg-gray-100 p-3 rounded-lg">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>my-appointments" class="flex items-center space-x-3 text-gray-700 hover:bg-gray-100 p-3 rounded-lg">
                                <i class="fas fa-calendar-check"></i>
                                <span>My Appointments</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>appointments/create" class="flex items-center space-x-3 text-blue-600 bg-blue-50 p-3 rounded-lg">
                                <i class="fas fa-calendar-plus"></i>
                                <span>Book Appointment</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>vaccinations/myVaccinations" class="flex items-center space-x-3 text-gray-700 hover:bg-gray-100 p-3 rounded-lg">
                                <i class="fas fa-syringe"></i>
                                <span>My Vaccinations</span>
                            </a>
                        </li>
                    <?php else: ?>
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
                    <?php endif; ?>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center">
                    <a href="<?php echo BASE_URL; ?><?php echo (isset($userRole) && $userRole === ROLE_PATIENT) ? 'my-appointments' : 'appointments'; ?>" class="text-blue-600 hover:text-blue-800 mr-3">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Schedule Appointment</h1>
                        <p class="text-gray-600"><?php echo (isset($userRole) && $userRole === ROLE_PATIENT) ? 'Book a new vaccination appointment' : 'Book a new appointment for patient'; ?></p>
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
                        <!-- Patient Selection -->
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 border-b pb-2">
                                <i class="fas fa-user mr-2 text-blue-600"></i>Patient Information
                            </h3>
                        </div>

                        <?php if (isset($userRole) && $userRole === ROLE_PATIENT): ?>
                            <div class="md:col-span-2">
                                <input type="hidden" id="patient_id" name="patient_id" value="<?php echo $userPatient['id'] ?? ''; ?>" required>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Patient Information <span class="text-red-500">*</span>
                                </label>
                                <?php if ($userPatient): ?>
                                    <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                                        <div class="flex items-center">
                                            <div class="bg-blue-100 p-2 rounded-full mr-3">
                                                <i class="fas fa-user text-blue-600"></i>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900"><?php echo htmlspecialchars($userPatient['first_name'] . ' ' . $userPatient['last_name']); ?></div>
                                                <div class="text-sm text-gray-600">
                                                    <i class="fas fa-id-card mr-1"></i><?php echo htmlspecialchars($userPatient['patient_id']); ?>
                                                    <i class="fas fa-phone ml-3 mr-1"></i><?php echo htmlspecialchars($userPatient['phone']); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="p-4 bg-red-50 rounded-lg border border-red-200">
                                        <div class="text-red-700">
                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                            Patient profile not found. Please contact administrator.
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="md:col-span-2">
                                <label for="patient_search" class="block text-sm font-medium text-gray-700 mb-2">
                                    Search Patient <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" id="patient_search" 
                                           class="w-full px-3 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="Search by name, patient ID, or phone number..."
                                           autocomplete="off">
                                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                                    <div id="patient_results" class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg mt-1 max-h-48 overflow-y-auto hidden"></div>
                                </div>
                                <input type="hidden" id="patient_id" name="patient_id" required>
                                <div id="selected_patient_info" class="mt-3 p-3 bg-blue-50 rounded-lg hidden">
                                    <!-- Selected patient info will be displayed here -->
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Appointment Details -->
                        <div class="md:col-span-2 mt-6">
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 border-b pb-2">
                                <i class="fas fa-calendar mr-2 text-blue-600"></i>Appointment Details
                            </h3>
                        </div>

                        <div class="md:col-span-2">
                            <label for="appointment_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Select Date for Anti-Rabies Vaccination <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="appointment_date" name="appointment_date" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   min="<?php echo date('Y-m-d'); ?>"
                                   value="<?php echo isset($_SESSION['form_data']['appointment_date']) ? $_SESSION['form_data']['appointment_date'] : ''; ?>">
                            <p class="mt-2 text-sm text-gray-600">Select your preferred date for the anti-rabies vaccination appointment.</p>
                        </div>

                        <input type="hidden" id="appointment_time" name="appointment_time" value="09:00">
                        <input type="hidden" id="appointment_type" name="appointment_type" value="vaccination">

                        <?php if (!isset($userRole) || $userRole !== ROLE_PATIENT): ?>
                            <div>
                                <label for="staff_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Assigned Staff
                                </label>
                                <select id="staff_id" name="staff_id"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Select Staff (Optional)</option>
                                    <!-- Staff options will be populated dynamically -->
                                </select>
                            </div>
                        <?php endif; ?>

                        <!-- Bite Details Section -->
                        <div class="md:col-span-2 mt-6">
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 border-b pb-2">
                                <i class="fas fa-exclamation-triangle mr-2 text-orange-600"></i>Bite Incident Details
                            </h3>
                        </div>

                        <div>
                            <label for="animal_type" class="block text-sm font-medium text-gray-700 mb-2">
                                Animal Type <span class="text-red-500">*</span>
                            </label>
                            <select id="animal_type" name="animal_type" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Animal Type</option>
                                <option value="dog" <?php echo (isset($_SESSION['form_data']['animal_type']) && $_SESSION['form_data']['animal_type'] === 'dog') ? 'selected' : ''; ?>>Dog</option>
                                <option value="cat" <?php echo (isset($_SESSION['form_data']['animal_type']) && $_SESSION['form_data']['animal_type'] === 'cat') ? 'selected' : ''; ?>>Cat</option>
                                <option value="rat" <?php echo (isset($_SESSION['form_data']['animal_type']) && $_SESSION['form_data']['animal_type'] === 'rat') ? 'selected' : ''; ?>>Rat</option>
                                <option value="other" <?php echo (isset($_SESSION['form_data']['animal_type']) && $_SESSION['form_data']['animal_type'] === 'other') ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>

                        <div>
                            <label for="body_part" class="block text-sm font-medium text-gray-700 mb-2">
                                Body Part Bitten <span class="text-red-500">*</span>
                            </label>
                            <select id="body_part" name="body_part" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Body Part</option>
                                <option value="head" <?php echo (isset($_SESSION['form_data']['body_part']) && $_SESSION['form_data']['body_part'] === 'head') ? 'selected' : ''; ?>>Head/Face</option>
                                <option value="neck" <?php echo (isset($_SESSION['form_data']['body_part']) && $_SESSION['form_data']['body_part'] === 'neck') ? 'selected' : ''; ?>>Neck</option>
                                <option value="left_arm" <?php echo (isset($_SESSION['form_data']['body_part']) && $_SESSION['form_data']['body_part'] === 'left_arm') ? 'selected' : ''; ?>>Left Arm</option>
                                <option value="right_arm" <?php echo (isset($_SESSION['form_data']['body_part']) && $_SESSION['form_data']['body_part'] === 'right_arm') ? 'selected' : ''; ?>>Right Arm</option>
                                <option value="left_hand" <?php echo (isset($_SESSION['form_data']['body_part']) && $_SESSION['form_data']['body_part'] === 'left_hand') ? 'selected' : ''; ?>>Left Hand</option>
                                <option value="right_hand" <?php echo (isset($_SESSION['form_data']['body_part']) && $_SESSION['form_data']['body_part'] === 'right_hand') ? 'selected' : ''; ?>>Right Hand</option>
                                <option value="chest" <?php echo (isset($_SESSION['form_data']['body_part']) && $_SESSION['form_data']['body_part'] === 'chest') ? 'selected' : ''; ?>>Chest</option>
                                <option value="abdomen" <?php echo (isset($_SESSION['form_data']['body_part']) && $_SESSION['form_data']['body_part'] === 'abdomen') ? 'selected' : ''; ?>>Abdomen</option>
                                <option value="left_leg" <?php echo (isset($_SESSION['form_data']['body_part']) && $_SESSION['form_data']['body_part'] === 'left_leg') ? 'selected' : ''; ?>>Left Leg</option>
                                <option value="right_leg" <?php echo (isset($_SESSION['form_data']['body_part']) && $_SESSION['form_data']['body_part'] === 'right_leg') ? 'selected' : ''; ?>>Right Leg</option>
                                <option value="left_foot" <?php echo (isset($_SESSION['form_data']['body_part']) && $_SESSION['form_data']['body_part'] === 'left_foot') ? 'selected' : ''; ?>>Left Foot</option>
                                <option value="right_foot" <?php echo (isset($_SESSION['form_data']['body_part']) && $_SESSION['form_data']['body_part'] === 'right_foot') ? 'selected' : ''; ?>>Right Foot</option>
                                <option value="other" <?php echo (isset($_SESSION['form_data']['body_part']) && $_SESSION['form_data']['body_part'] === 'other') ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>

                        <div>
                            <label for="bite_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Date of Bite <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="bite_date" name="bite_date" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   max="<?php echo date('Y-m-d'); ?>"
                                   value="<?php echo isset($_SESSION['form_data']['bite_date']) ? $_SESSION['form_data']['bite_date'] : date('Y-m-d'); ?>">
                        </div>

                        <div>
                            <label for="bite_time" class="block text-sm font-medium text-gray-700 mb-2">
                                Time of Bite <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="bite_time" name="bite_time" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   value="<?php echo isset($_SESSION['form_data']['bite_time']) ? $_SESSION['form_data']['bite_time'] : date('H:i'); ?>">
                        </div>

                        <div>
                            <label for="animal_status" class="block text-sm font-medium text-gray-700 mb-2">
                                Animal Status <span class="text-red-500">*</span>
                            </label>
                            <select id="animal_status" name="animal_status" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Animal Status</option>
                                <option value="stray" <?php echo (isset($_SESSION['form_data']['animal_status']) && $_SESSION['form_data']['animal_status'] === 'stray') ? 'selected' : ''; ?>>Stray</option>
                                <option value="owned" <?php echo (isset($_SESSION['form_data']['animal_status']) && $_SESSION['form_data']['animal_status'] === 'owned') ? 'selected' : ''; ?>>Owned</option>
                                <option value="unknown" <?php echo (isset($_SESSION['form_data']['animal_status']) && $_SESSION['form_data']['animal_status'] === 'unknown') ? 'selected' : ''; ?>>Unknown</option>
                            </select>
                        </div>

                        <div>
                            <label for="bite_type" class="block text-sm font-medium text-gray-700 mb-2">
                                Bite Type <span class="text-red-500">*</span>
                            </label>
                            <select id="bite_type" name="bite_type" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Bite Type</option>
                                <option value="bite" <?php echo (isset($_SESSION['form_data']['bite_type']) && $_SESSION['form_data']['bite_type'] === 'bite') ? 'selected' : ''; ?>>Bite</option>
                                <option value="scratch" <?php echo (isset($_SESSION['form_data']['bite_type']) && $_SESSION['form_data']['bite_type'] === 'scratch') ? 'selected' : ''; ?>>Scratch</option>
                                <option value="lick" <?php echo (isset($_SESSION['form_data']['bite_type']) && $_SESSION['form_data']['bite_type'] === 'lick') ? 'selected' : ''; ?>>Lick</option>
                                <option value="other" <?php echo (isset($_SESSION['form_data']['bite_type']) && $_SESSION['form_data']['bite_type'] === 'other') ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>

                        
                        <div>
                            <label for="washing_done" class="block text-sm font-medium text-gray-700 mb-2">
                                Wound Washed?
                            </label>
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" name="washing_done" value="1" <?php echo (isset($_SESSION['form_data']['washing_done']) && $_SESSION['form_data']['washing_done'] == '1') ? 'checked' : ''; ?> class="mr-2">
                                    <span>Yes</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="washing_done" value="0" <?php echo (isset($_SESSION['form_data']['washing_done']) && $_SESSION['form_data']['washing_done'] == '0') ? 'checked' : ''; ?> class="mr-2">
                                    <span>No</span>
                                </label>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label for="animal_description" class="block text-sm font-medium text-gray-700 mb-2">
                                Animal Description
                            </label>
                            <textarea id="animal_description" name="animal_description" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Describe the animal (size, color, behavior, etc.)"><?php echo isset($_SESSION['form_data']['animal_description']) ? $_SESSION['form_data']['animal_description'] : ''; ?></textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Additional Notes
                            </label>
                            <textarea id="notes" name="notes" rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Additional notes or special instructions"><?php echo isset($_SESSION['form_data']['notes']) ? $_SESSION['form_data']['notes'] : ''; ?></textarea>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4 mt-8">
                        <a href="<?php echo BASE_URL; ?>appointments" 
                           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-save mr-2"></i>Schedule Appointment
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        let selectedPatient = null;
        let availableSlots = [];

        <?php if (!isset($userRole) || $userRole !== ROLE_PATIENT): ?>
            // Load staff members
            function loadStaff() {
                fetch('<?php echo BASE_URL; ?>api/staff')
                    .then(response => response.json())
                    .then(data => {
                        const staffSelect = document.getElementById('staff_id');
                        data.forEach(staff => {
                            const option = document.createElement('option');
                            option.value = staff.id;
                            option.textContent = staff.full_name;
                            staffSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error loading staff:', error));
            }

            // Patient search
            document.getElementById('patient_search').addEventListener('input', function(e) {
                const query = e.target.value.trim();
                const resultsDiv = document.getElementById('patient_results');
                
                if (query.length < 2) {
                    resultsDiv.classList.add('hidden');
                    return;
                }

                fetch('<?php echo BASE_URL; ?>api/patients/search?q=' + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(data => {
                        resultsDiv.innerHTML = '';
                        
                        if (data.length === 0) {
                            resultsDiv.innerHTML = '<div class="p-3 text-gray-500">No patients found</div>';
                        } else {
                            data.forEach(patient => {
                                const div = document.createElement('div');
                                div.className = 'p-3 hover:bg-gray-100 cursor-pointer border-b';
                                div.innerHTML = `
                                    <div class="font-medium">${patient.first_name} ${patient.last_name}</div>
                                    <div class="text-sm text-gray-500">${patient.patient_id} • ${patient.phone}</div>
                                `;
                                div.onclick = () => selectPatient(patient);
                                resultsDiv.appendChild(div);
                            });
                        }
                        
                        resultsDiv.classList.remove('hidden');
                    })
                    .catch(error => {
                        console.error('Error searching patients:', error);
                        resultsDiv.innerHTML = '<div class="p-3 text-red-500">Error searching patients</div>';
                        resultsDiv.classList.remove('hidden');
                    });
            });

            // Select patient
            function selectPatient(patient) {
                selectedPatient = patient;
                document.getElementById('patient_id').value = patient.id;
                document.getElementById('patient_search').value = `${patient.first_name} ${patient.last_name} (${patient.patient_id})`;
                document.getElementById('patient_results').classList.add('hidden');
                
                // Show selected patient info
                const infoDiv = document.getElementById('selected_patient_info');
                infoDiv.innerHTML = `
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-2 rounded-full mr-3">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <div>
                            <div class="font-medium">${patient.first_name} ${patient.last_name}</div>
                            <div class="text-sm text-gray-600">${patient.patient_id} • ${patient.phone}</div>
                        </div>
                    </div>
                `;
                infoDiv.classList.remove('hidden');
            }
        <?php endif; ?>

        // Load available time slots when date changes
        document.getElementById('appointment_date').addEventListener('change', function() {
            const date = this.value;
            const timeSelect = document.getElementById('appointment_time');
            
            if (!date) {
                timeSelect.innerHTML = '<option value="">Select Date First</option>';
                return;
            }

            fetch('<?php echo BASE_URL; ?>appointments/getAvailableSlots?date=' + date)
                .then(response => response.json())
                .then(data => {
                    timeSelect.innerHTML = '<option value="">Select Time</option>';
                    
                    if (data.success && data.slots.length > 0) {
                        data.slots.forEach(slot => {
                            const option = document.createElement('option');
                            option.value = slot;
                            option.textContent = slot;
                            timeSelect.appendChild(option);
                        });
                    } else {
                        timeSelect.innerHTML = '<option value="">No available slots</option>';
                    }
                })
                .catch(error => {
                    console.error('Error loading slots:', error);
                    timeSelect.innerHTML = '<option value="">Error loading slots</option>';
                });
        });

        // Hide patient results when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#patient_search') && !e.target.closest('#patient_results')) {
                const resultsDiv = document.getElementById('patient_results');
                if (resultsDiv) {
                    resultsDiv.classList.add('hidden');
                }
            }
        });

        // Form validation
        document.getElementById('appointmentForm').addEventListener('submit', function(e) {
            const patientId = document.getElementById('patient_id').value;
            const appointmentDate = document.getElementById('appointment_date').value;
            const appointmentTime = document.getElementById('appointment_time').value;
            const appointmentType = document.getElementById('appointment_type') ? document.getElementById('appointment_type').value : 'vaccination';
            
            // Bite fields validation
            const animalType = document.getElementById('animal_type').value;
            const bodyPart = document.getElementById('body_part').value;
            const biteDate = document.getElementById('bite_date').value;
            const biteTime = document.getElementById('bite_time').value;
            const animalStatus = document.getElementById('animal_status').value;
            const biteType = document.getElementById('bite_type').value;
            
            if (!patientId) {
                e.preventDefault();
                alert('Please select a patient');
                return;
            }
            
            if (!appointmentDate) {
                e.preventDefault();
                alert('Please select an appointment date');
                return;
            }
            
            if (!appointmentTime) {
                e.preventDefault();
                alert('Please select an appointment time');
                return;
            }
            
            if (!appointmentType) {
                e.preventDefault();
                alert('Please select an appointment type');
                return;
            }
            
            // Validate bite fields
            if (!animalType) {
                e.preventDefault();
                alert('Please select the animal type');
                return;
            }
            
            if (!bodyPart) {
                e.preventDefault();
                alert('Please select the body part that was bitten');
                return;
            }
            
            if (!biteDate) {
                e.preventDefault();
                alert('Please enter the bite date');
                return;
            }
            
            if (!biteTime) {
                e.preventDefault();
                alert('Please enter the bite time');
                return;
            }
            
            if (!animalStatus) {
                e.preventDefault();
                alert('Please select the animal status');
                return;
            }
            
            if (!biteType) {
                e.preventDefault();
                alert('Please select the bite type');
                return;
            }
            
                    });

        // Initialize
        <?php if (!isset($userRole) || $userRole !== ROLE_PATIENT): ?>
            loadStaff();
        <?php endif; ?>

        // Pre-fill patient if provided in URL
        <?php if (isset($patientId) && $patientId): ?>
        fetch('<?php echo BASE_URL; ?>api/patients/<?php echo $patientId; ?>')
            .then(response => response.json())
            .then(patient => {
                if (patient) {
                    selectPatient(patient);
                }
            })
            .catch(error => console.error('Error loading patient:', error));
        <?php endif; ?>
    </script>
</body>
</html>
