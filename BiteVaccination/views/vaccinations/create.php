<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Vaccination - Animal Bite Center Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.1);
        }
    </style>
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
                    <div class="relative">
                        <i class="fas fa-bell text-gray-600 hover:text-blue-600 cursor-pointer"></i>
                    </div>
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
                        <a href="<?php echo BASE_URL; ?>appointments" class="flex items-center space-x-3 text-gray-700 hover:bg-gray-100 p-3 rounded-lg">
                            <i class="fas fa-calendar-check"></i>
                            <span>Appointments</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>vaccinations" class="flex items-center space-x-3 text-blue-600 bg-blue-50 p-3 rounded-lg">
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
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Schedule Vaccination</h1>
                        <p class="text-gray-600">Schedule upcoming vaccination doses for patients</p>
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

            <!-- Vaccination Form -->
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <form method="POST" action="<?php echo BASE_URL; ?>vaccinations/create">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Patient Selection -->
                        <div class="form-group">
                            <?php $selectedPatientId = isset($_POST['patient_id']) ? (int)$_POST['patient_id'] : (isset($patientId) ? $patientId : 0); ?>
                            <label for="patient_id">Select Patient *</label>
                            <select name="patient_id" id="patient_id" required class="w-full">
                                <option value="">Choose a patient...</option>
                                <?php if (empty($patients)): ?>
                                    <option value="" disabled>No patients found in the system</option>
                                <?php else: ?>
                                    <?php foreach ($patients as $patient): ?>
                                        <option value="<?php echo $patient['id']; ?>" <?php echo $selectedPatientId == $patient['id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Vaccination Details -->
                        <div class="form-group">
                            <label for="vaccine_type">Vaccine Type *</label>
                            <select name="vaccine_type" id="vaccine_type" required>
                                <option value="">Select vaccine type...</option>
                                <option value="anti_rabies">Anti-Rabies</option>
                                <option value="tetanus">Tetanus</option>
                                <option value="hepatitis_b">Hepatitis B</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="dose_number">Dose Number *</label>
                            <select name="dose_number" id="dose_number" required>
                                <option value="">Select dose...</option>
                                <option value="1">Dose 1</option>
                                <option value="2">Dose 2</option>
                                <option value="3">Dose 3</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="administration_date">Administration Date *</label>
                            <input type="date" name="administration_date" id="administration_date" required>
                        </div>

                        <div class="form-group">
                            <label for="administration_time">Administration Time *</label>
                            <input type="time" name="administration_time" id="administration_time" required>
                        </div>

                        <div class="form-group">
                            <label for="administration_site">Administration Site *</label>
                            <select name="administration_site" id="administration_site" required>
                                <option value="">Select site...</option>
                                <option value="left_arm">Left Arm</option>
                                <option value="right_arm">Right Arm</option>
                                <option value="left_thigh">Left Thigh</option>
                                <option value="right_thigh">Right Thigh</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea name="notes" id="notes" rows="4" placeholder="Enter any additional notes..."></textarea>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i>Schedule Vaccination
                        </button>
                        <a href="<?php echo BASE_URL; ?>vaccinations" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
