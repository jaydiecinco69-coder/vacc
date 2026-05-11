<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard - Animal Bite Center Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .appointment-card {
            transition: all 0.3s ease;
        }
        .appointment-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .progress-step {
            transition: all 0.3s ease;
        }
        .progress-step.completed {
            background-color: #10b981;
        }
        .progress-step.upcoming {
            background-color: #3b82f6;
        }
        .progress-step.missed {
            background-color: #ef4444;
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
                    <span class="font-bold text-xl text-gray-800">BiteCare Patient Portal</span>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <i class="fas fa-bell text-gray-600 hover:text-blue-600 cursor-pointer"></i>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">1</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <img src="https://picsum.photos/seed/patient/40/40.jpg" alt="Profile" class="w-8 h-8 rounded-full">
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
                        <a href="<?php echo BASE_URL; ?>dashboard" class="flex items-center space-x-3 text-blue-600 bg-blue-50 p-3 rounded-lg">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $patientInfo['id'] ? BASE_URL . 'patients/view?id=' . $patientInfo['id'] : BASE_URL . 'dashboard'; ?>" class="flex items-center space-x-3 text-gray-700 hover:bg-gray-100 p-3 rounded-lg">
                            <i class="fas fa-user"></i>
                            <span>My Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>my-appointments" class="flex items-center space-x-3 text-gray-700 hover:bg-gray-100 p-3 rounded-lg">
                            <i class="fas fa-calendar-check"></i>
                            <span>My Appointments</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>vaccinations/myVaccinations" class="flex items-center space-x-3 text-gray-700 hover:bg-gray-100 p-3 rounded-lg">
                            <i class="fas fa-syringe"></i>
                            <span>Vaccination Records</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Patient Dashboard</h1>
                <p class="text-gray-600">Welcome, <?php echo $patientInfo['first_name']; ?>!</p>
            </div>

            <?php if (!empty($profileMissing)): ?>
                <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-800 rounded-lg">
                    <strong>Notice:</strong> Your patient profile has not been completed yet. Some patient-specific details may be missing.
                </div>
            <?php endif; ?>

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

            <!-- Patient Info Card -->
            <div class="bg-white p-6 rounded-xl shadow-lg mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-4 rounded-full mr-4">
                            <i class="fas fa-user text-blue-600 text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold"><?php echo $patientInfo['first_name'] . ' ' . $patientInfo['last_name']; ?></h2>
                            <p class="text-gray-600">Patient ID: <?php echo $patientInfo['patient_id']; ?></p>
                            <p class="text-sm text-gray-500">
                                <i class="fas fa-phone mr-1"></i> <?php echo $patientInfo['phone']; ?>
                                <span class="mx-2">•</span>
                                <i class="fas fa-envelope mr-1"></i> <?php echo $patientInfo['email']; ?>
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <a href="<?php echo BASE_URL; ?>auth/change-password" class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-100 flex items-center gap-2">
                            <i class="fas fa-key"></i>
                            Change Password
                        </a>
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            <i class="fas fa-edit mr-2"></i>Edit Profile
                        </button>
                    </div>
                </div>
            </div>

            <!-- Treatment Progress -->
            <?php if (!empty($treatmentProgress)): ?>
                <div class="bg-white p-6 rounded-xl shadow-lg mb-8">
                    <h3 class="text-lg font-semibold mb-4">Treatment Progress</h3>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="progress-step completed w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="flex-1 h-1 bg-green-500 mx-2"></div>
                            </div>
                            <div class="flex items-center">
                                <div class="progress-step <?php echo $treatmentProgress[0]['doses_completed'] >= 1 ? 'completed' : 'upcoming'; ?> w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                    <?php echo $treatmentProgress[0]['doses_completed'] >= 1 ? '<i class="fas fa-check"></i>' : '1'; ?>
                                </div>
                                <div class="flex-1 h-1 <?php echo $treatmentProgress[0]['doses_completed'] >= 1 ? 'bg-green-500' : 'bg-gray-300'; ?> mx-2"></div>
                            </div>
                            <div class="flex items-center">
                                <div class="progress-step <?php echo $treatmentProgress[0]['doses_completed'] >= 2 ? 'completed' : 'upcoming'; ?> w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                    <?php echo $treatmentProgress[0]['doses_completed'] >= 2 ? '<i class="fas fa-check"></i>' : '2'; ?>
                                </div>
                                <div class="flex-1 h-1 <?php echo $treatmentProgress[0]['doses_completed'] >= 2 ? 'bg-green-500' : 'bg-gray-300'; ?> mx-2"></div>
                            </div>
                            <div class="flex items-center">
                                <div class="progress-step <?php echo $treatmentProgress[0]['doses_completed'] >= 3 ? 'completed' : 'upcoming'; ?> w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                    <?php echo $treatmentProgress[0]['doses_completed'] >= 3 ? '<i class="fas fa-check"></i>' : '3'; ?>
                                </div>
                                <div class="flex-1 h-1 <?php echo $treatmentProgress[0]['doses_completed'] >= 3 ? 'bg-green-500' : 'bg-gray-300'; ?> mx-2"></div>
                            </div>
                            <div class="flex items-center">
                                <div class="progress-step <?php echo $treatmentProgress[0]['doses_completed'] >= 4 ? 'completed' : 'upcoming'; ?> w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                    <?php echo $treatmentProgress[0]['doses_completed'] >= 4 ? '<i class="fas fa-check"></i>' : '4'; ?>
                                </div>
                                <div class="flex-1 h-1 <?php echo $treatmentProgress[0]['doses_completed'] >= 4 ? 'bg-green-500' : 'bg-gray-300'; ?> mx-2"></div>
                            </div>
                            <div class="flex items-center">
                                <div class="progress-step <?php echo $treatmentProgress[0]['doses_completed'] >= 5 ? 'completed' : 'upcoming'; ?> w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                    <?php echo $treatmentProgress[0]['doses_completed'] >= 5 ? '<i class="fas fa-check"></i>' : '5'; ?>
                                </div>
                            </div>
                        </div>
                        <div class="text-center text-sm text-gray-600">
                            <p>Progress: <?php echo $treatmentProgress[0]['doses_completed']; ?> of <?php echo $treatmentProgress[0]['total_doses_required']; ?> doses completed</p>
                            <p class="text-xs mt-1">Next appointment: <?php echo date('M d, Y', strtotime($treatmentProgress[0]['next_appointment_date'])); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Upcoming Appointments -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Upcoming Anti-Rabies Vaccination Doses</h3>
                        <a href="<?php echo BASE_URL; ?>appointments/create?type=vaccination&patient_id=<?php echo $patientInfo['id']; ?>" class="text-blue-600 hover:text-blue-800 flex items-center gap-2">
                            <i class="fas fa-calendar-plus"></i> Schedule New Appointment
                        </a>
                    </div>
                    
                    <?php if (empty($upcomingAppointments)): ?>
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-calendar-times text-4xl mb-2"></i>
                            <p>No upcoming appointments</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($upcomingAppointments as $appointment): ?>
                                <div class="appointment-card bg-gray-50 p-4 rounded-lg border-l-4 border-blue-500">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="font-medium"><?php echo ucfirst(str_replace('_', ' ', $appointment['appointment_type'])); ?></div>
                                            <div class="text-sm text-gray-500">
                                                <i class="fas fa-calendar mr-1"></i><?php echo date('M d, Y', strtotime($appointment['appointment_date'])); ?>
                                                <i class="fas fa-clock ml-2 mr-1"></i><?php echo date('H:i', strtotime($appointment['appointment_time'])); ?>
                                            </div>
                                            <?php if ($appointment['staff_name']): ?>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    <i class="fas fa-user-md mr-1"></i> Dr. <?php echo $appointment['staff_name']; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <?php if ($_SESSION['role'] === ROLE_PATIENT && $appointment['status'] === 'scheduled'): ?>
                                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-sm">
                                                    <i class="fas fa-hourglass-half mr-2"></i>Pending approval
                                                </span>
                                            <?php else: ?>
                                                <div class="flex space-x-2">
                                                    <button class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600">
                                                        <i class="fas fa-check"></i> Confirm
                                                    </button>
                                                    <button class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
                                                        <i class="fas fa-times"></i> Cancel
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Vaccination Status -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Vaccination Status</h3>
                        <button class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-download"></i> Download Card
                        </button>
                    </div>
                    
                    <?php if (empty($vaccinationStatus)): ?>
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-syringe text-4xl mb-2"></i>
                            <p>No vaccination records</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($vaccinationStatus as $vaccination): ?>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center">
                                            <div class="bg-<?php echo $vaccination['status'] == 'administered' ? 'green' : 'blue'; ?>-100 p-2 rounded-lg mr-3">
                                                <i class="fas fa-syringe text-<?php echo $vaccination['status'] == 'administered' ? 'green' : 'blue'; ?>-600"></i>
                                            </div>
                                            <div>
                                                <div class="font-medium"><?php echo ucfirst($vaccination['vaccine_type']); ?> - Dose <?php echo $vaccination['dose_number']; ?></div>
                                                <div class="text-sm text-gray-500">
                                                    <?php if ($vaccination['status'] == 'administered'): ?>
                                                        Administered on <?php echo date('M d, Y', strtotime($vaccination['administration_date'])); ?>
                                                    <?php else: ?>
                                                        Scheduled for <?php echo date('M d, Y', strtotime($vaccination['administration_date'])); ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="text-xs bg-<?php echo $vaccination['status'] == 'administered' ? 'green' : 'blue'; ?>-100 text-<?php echo $vaccination['status'] == 'administered' ? 'green' : 'blue'; ?>-800 px-2 py-1 rounded-full">
                                            <?php echo ucfirst($vaccination['status']); ?>
                                        </span>
                                    </div>
                                    <?php if ($vaccination['vaccine_brand']): ?>
                                        <div class="text-xs text-gray-500">
                                            <i class="fas fa-prescription-bottle mr-1"></i> <?php echo $vaccination['vaccine_brand']; ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($vaccination['adverse_reactions']): ?>
                                        <div class="text-xs text-orange-600 mt-2">
                                            <i class="fas fa-exclamation-triangle mr-1"></i> Adverse reaction reported
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($healthTip)): ?>
            <div class="mt-8 bg-white border border-gray-200 p-6 rounded-xl shadow-sm">
                <div class="flex items-center mb-4">
                    <i class="fas fa-hand-holding-medical text-green-600 text-xl mr-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Health Tip</h3>
                </div>
                <p class="text-sm text-gray-700 font-semibold mb-2"><?php echo htmlspecialchars($healthTip['title']); ?></p>
                <p class="text-sm text-gray-600 mb-4"><?php echo htmlspecialchars($healthTip['description']); ?></p>
                <?php if (!empty($healthTip['link'])): ?>
                    <a href="<?php echo htmlspecialchars($healthTip['link']); ?>" target="_blank" class="text-blue-600 hover:text-blue-800 font-medium">
                        Learn more <i class="fas fa-external-link-alt ml-1"></i>
                    </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Important Information -->
            <div class="mt-8 bg-blue-50 border border-blue-200 p-6 rounded-xl">
                <div class="flex items-center mb-4">
                    <i class="fas fa-info-circle text-blue-600 text-xl mr-3"></i>
                    <h3 class="text-lg font-semibold text-blue-800">Important Information</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white p-4 rounded-lg">
                        <h4 class="font-medium text-sm mb-2">Emergency Contact</h4>
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-phone mr-1"></i> <?php echo $patientInfo['emergency_contact_phone'] ?: 'Not provided'; ?>
                        </p>
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-user mr-1"></i> <?php echo $patientInfo['emergency_contact_name'] ?: 'Not provided'; ?>
                        </p>
                    </div>
                    
                    <div class="bg-white p-4 rounded-lg">
                        <h4 class="font-medium text-sm mb-2">Medical Information</h4>
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-tint mr-1"></i> Blood Type: <?php echo $patientInfo['blood_type'] ?: 'Not provided'; ?>
                        </p>
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-allergies mr-1"></i> Allergies: <?php echo $patientInfo['allergies'] ?: 'None recorded'; ?>
                        </p>
                    </div>
                </div>
                
                <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-sm text-yellow-800">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Reminder:</strong> Please arrive 15 minutes before your scheduled appointment. 
                        Bring your vaccination card and any relevant medical documents.
                    </p>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Handle appointment actions
        document.querySelectorAll('.appointment-card button').forEach(button => {
            button.addEventListener('click', function() {
                const action = this.textContent.trim();
                const card = this.closest('.appointment-card');
                
                if (action.includes('Confirm')) {
                    card.style.borderLeftColor = '#10b981';
                    this.innerHTML = '<i class="fas fa-check"></i> Confirmed';
                    this.disabled = true;
                    this.classList.remove('bg-green-500', 'hover:bg-green-600');
                    this.classList.add('bg-gray-400');
                } else if (action.includes('Cancel')) {
                    if (confirm('Are you sure you want to cancel this appointment?')) {
                        card.style.opacity = '0.5';
                        card.style.borderLeftColor = '#ef4444';
                        this.innerHTML = '<i class="fas fa-times"></i> Cancelled';
                        this.disabled = true;
                        this.classList.remove('bg-red-500', 'hover:bg-red-600');
                        this.classList.add('bg-gray-400');
                    }
                }
            });
        });

        // Handle vaccination card download
        document.querySelector('.fa-download').parentElement.addEventListener('click', function() {
            alert('Vaccination card download feature will be available soon.');
        });
    </script>
</body>
</html>
