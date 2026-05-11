<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Details - BiteCare Patient Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .tab-button {
            transition: all 0.3s ease;
        }
        .tab-button.active {
            border-bottom: 3px solid #3b82f6;
            color: #3b82f6;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
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
                    <span class="font-bold text-xl text-gray-800"><?php echo isset($_SESSION['role']) && $_SESSION['role'] === ROLE_PATIENT ? 'BiteCare Patient Portal' : 'BiteCare System'; ?></span>
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
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === ROLE_PATIENT): ?>
                        <li>
                            <a href="<?php echo BASE_URL; ?>dashboard" class="flex items-center space-x-3 text-gray-700 hover:bg-gray-100 p-3 rounded-lg">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>patients/view?id=<?php echo $patient['id']; ?>" class="flex items-center space-x-3 text-blue-600 bg-blue-50 p-3 rounded-lg">
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
                    <?php endif; ?>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <a href="<?php echo BASE_URL; ?>patients" class="text-blue-600 hover:text-blue-800 mr-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Patient Details</h1>
                            <p class="text-gray-600">View and manage patient information</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <a href="<?php echo BASE_URL; ?>appointments/create?patient_id=<?php echo $patient['id']; ?>" 
                           class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                            <i class="fas fa-calendar-plus mr-2"></i>Schedule Appointment
                        </a>
                        <a href="<?php echo BASE_URL; ?>patients/edit?id=<?php echo $patient['id']; ?>" 
                           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            <i class="fas fa-edit mr-2"></i>Edit Patient
                        </a>
                        <button onclick="printPatientCard()" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                            <i class="fas fa-print mr-2"></i>Print Card
                        </button>
                    </div>
                </div>
            </div>

            <!-- Patient Information Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <div class="flex items-start justify-between">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-4 rounded-full mr-4">
                            <i class="fas fa-user text-blue-600 text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">
                                <?php echo $patient['first_name'] . ' ' . $patient['last_name']; ?>
                            </h2>
                            <p class="text-gray-600">Patient ID: <?php echo $patient['patient_id']; ?></p>
                            <div class="mt-2 space-y-1">
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-phone mr-2"></i><?php echo $patient['phone']; ?>
                                </p>
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-envelope mr-2"></i><?php echo $patient['email'] ?: 'N/A'; ?>
                                </p>
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-map-marker-alt mr-2"></i><?php echo $patient['address']; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="text-right space-y-2">
                        <div class="text-sm">
                            <span class="text-gray-500">Age:</span>
                            <span class="font-semibold"><?php 
                                $age = date_diff(date_create($patient['birth_date']), date_create('now'))->y;
                                echo $age . ' years';
                            ?></span>
                        </div>
                        <div class="text-sm">
                            <span class="text-gray-500">Gender:</span>
                            <span class="font-semibold"><?php echo ucfirst($patient['gender']); ?></span>
                        </div>
                        <div class="text-sm">
                            <span class="text-gray-500">Blood Type:</span>
                            <span class="font-semibold"><?php echo $patient['blood_type'] ?: 'Unknown'; ?></span>
                        </div>
                        <div class="text-sm">
                            <span class="text-gray-500">Registered:</span>
                            <span class="font-semibold"><?php echo date('M d, Y', strtotime($patient['created_at'])); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs Navigation -->
            <div class="bg-white rounded-xl shadow-lg">
                <div class="border-b">
                    <nav class="flex space-x-8 px-6">
                        <button onclick="showTab('overview')" class="tab-button active py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-info-circle mr-2"></i>Overview
                        </button>
                        <button onclick="showTab('bites')" class="tab-button py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-bug mr-2"></i>Animal Bites
                        </button>
                        <button onclick="showTab('appointments')" class="tab-button py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-calendar-check mr-2"></i>Appointments
                        </button>
                        <button onclick="showTab('vaccinations')" class="tab-button py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-syringe mr-2"></i>Vaccinations
                        </button>
                        <button onclick="showTab('treatment')" class="tab-button py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-procedures mr-2"></i>Treatment Progress
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    <!-- Overview Tab -->
                    <div id="overview" class="tab-content active">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-semibold mb-4">Personal Information</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between py-2 border-b">
                                        <span class="text-gray-600">Middle Name</span>
                                        <span class="font-medium"><?php echo $patient['middle_name'] ?: 'N/A'; ?></span>
                                    </div>
                                    <div class="flex justify-between py-2 border-b">
                                        <span class="text-gray-600">Birth Date</span>
                                        <span class="font-medium"><?php echo date('M d, Y', strtotime($patient['birth_date'])); ?></span>
                                    </div>
                                    <div class="flex justify-between py-2 border-b">
                                        <span class="text-gray-600">Gender</span>
                                        <span class="font-medium"><?php echo ucfirst($patient['gender']); ?></span>
                                    </div>
                                    <div class="flex justify-between py-2 border-b">
                                        <span class="text-gray-600">Blood Type</span>
                                        <span class="font-medium"><?php echo $patient['blood_type'] ?: 'Unknown'; ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-semibold mb-4">Emergency Contact</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between py-2 border-b">
                                        <span class="text-gray-600">Contact Name</span>
                                        <span class="font-medium"><?php echo $patient['emergency_contact_name'] ?: 'N/A'; ?></span>
                                    </div>
                                    <div class="flex justify-between py-2 border-b">
                                        <span class="text-gray-600">Contact Phone</span>
                                        <span class="font-medium"><?php echo $patient['emergency_contact_phone'] ?: 'N/A'; ?></span>
                                    </div>
                                </div>
                                
                                <h3 class="text-lg font-semibold mb-4 mt-6">Medical Information</h3>
                                <div class="space-y-3">
                                    <div class="py-2 border-b">
                                        <span class="text-gray-600">Allergies</span>
                                        <p class="font-medium mt-1"><?php echo $patient['allergies'] ?: 'None recorded'; ?></p>
                                    </div>
                                    <div class="py-2 border-b">
                                        <span class="text-gray-600">Medical History</span>
                                        <p class="font-medium mt-1"><?php echo $patient['medical_history'] ?: 'None recorded'; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Animal Bites Tab -->
                    <div id="bites" class="tab-content">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Animal Bite History</h3>
                            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                <i class="fas fa-plus mr-2"></i>Record Bite
                            </button>
                        </div>
                        
                        <?php if (empty($animalBites)): ?>
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-bug text-4xl mb-2"></i>
                                <p>No animal bite records found</p>
                            </div>
                        <?php else: ?>
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Animal</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bite Type</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Body Part</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Exposure</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <?php foreach ($animalBites as $bite): ?>
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-3 text-sm"><?php echo date('M d, Y', strtotime($bite['bite_date'])); ?></td>
                                                <td class="px-4 py-3 text-sm">
                                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                                        <?php echo ucfirst($bite['animal_type']); ?>
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-sm"><?php echo ucfirst($bite['bite_type']); ?></td>
                                                <td class="px-4 py-3 text-sm"><?php echo $bite['body_part']; ?></td>
                                                <td class="px-4 py-3 text-sm">
                                                    <span class="px-2 py-1 text-xs rounded-full bg-orange-100 text-orange-800">
                                                        <?php echo str_replace('_', ' ', $bite['exposure_type']); ?>
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-sm">
                                                    <button class="text-blue-600 hover:text-blue-800">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Appointments Tab -->
                    <div id="appointments" class="tab-content">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Appointment History</h3>
                            <a href="<?php echo BASE_URL; ?>appointments/create?patient_id=<?php echo $patient['id']; ?>" 
                               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                <i class="fas fa-plus mr-2"></i>Schedule Appointment
                            </a>
                        </div>
                        
                        <?php if (empty($appointments)): ?>
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-calendar-times text-4xl mb-2"></i>
                                <p>No appointments found</p>
                            </div>
                        <?php else: ?>
                            <div class="space-y-4">
                                <?php foreach ($appointments as $appointment): ?>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <div class="font-medium"><?php echo ucfirst(str_replace('_', ' ', $appointment['appointment_type'])); ?></div>
                                                <div class="text-sm text-gray-600">
                                                    <i class="fas fa-calendar mr-1"></i><?php echo date('M d, Y', strtotime($appointment['appointment_date'])); ?>
                                                    <i class="fas fa-clock ml-2 mr-1"></i><?php echo date('H:i', strtotime($appointment['appointment_time'])); ?>
                                                </div>
                                                <?php if ($appointment['staff_name']): ?>
                                                    <div class="text-sm text-gray-600">
                                                        <i class="fas fa-user-md mr-1"></i> <?php echo $appointment['staff_name']; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="text-sm text-gray-600">
                                                        <i class="fas fa-calendar-check mr-1"></i> Scheduled
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="text-right">
                                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] !== ROLE_PATIENT): ?>
                                                    <!-- Admin Actions -->
                                                    <?php if ($appointment['status'] === 'scheduled'): ?>
                                                        <button onclick="approveAppointment(<?php echo $appointment['id']; ?>)" class="bg-green-600 text-white px-3 py-1 text-xs rounded hover:bg-green-700 mr-2">
                                                            <i class="fas fa-check mr-1"></i> Approve
                                                        </button>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($appointment['status'] === 'scheduled' || $appointment['status'] === 'confirmed'): ?>
                                                        <button onclick="cancelAppointment(<?php echo $appointment['id']; ?>)" class="bg-red-600 text-white px-3 py-1 text-xs rounded hover:bg-red-700 mr-2">
                                                            <i class="fas fa-times mr-1"></i> Cancel
                                                        </button>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <!-- Patient View - No Actions -->
                                                    <span class="px-2 py-1 text-xs rounded-full bg-<?php 
                                                        echo $appointment['status'] == 'completed' ? 'green' : 
                                                             ($appointment['status'] == 'cancelled' ? 'red' : 'blue'); 
                                                    ?>-100 text-<?php 
                                                        echo $appointment['status'] == 'completed' ? 'green' : 
                                                             ($appointment['status'] == 'cancelled' ? 'red' : 'blue'); 
                                                    ?>-800">
                                                        <?php echo ucfirst($appointment['status']); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php if ($appointment['notes']): ?>
                                            <div class="mt-2 text-sm text-gray-600">
                                                <i class="fas fa-notes-medical mr-1"></i> <?php echo $appointment['notes']; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Vaccinations Tab -->
                    <div id="vaccinations" class="tab-content">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Vaccination Records</h3>
                            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                <i class="fas fa-plus mr-2"></i>Record Vaccination
                            </button>
                        </div>
                        
                        <?php if (empty($vaccinations)): ?>
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-syringe text-4xl mb-2"></i>
                                <p>No vaccination records found</p>
                            </div>
                        <?php else: ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <?php foreach ($vaccinations as $vaccination): ?>
                                    <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-<?php 
                                        echo $vaccination['status'] == 'administered' ? 'green' : 'blue'; 
                                    ?>-500">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="font-medium">
                                                <?php echo ucfirst($vaccination['vaccine_type']); ?> - Dose <?php echo $vaccination['dose_number']; ?>
                                            </div>
                                            <span class="text-xs bg-<?php 
                                                echo $vaccination['status'] == 'administered' ? 'green' : 'blue'; 
                                            ?>-100 text-<?php 
                                                echo $vaccination['status'] == 'administered' ? 'green' : 'blue'; 
                                            ?>-800 px-2 py-1 rounded-full">
                                                <?php echo ucfirst($vaccination['status']); ?>
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            <?php if ($vaccination['status'] == 'administered'): ?>
                                                Administered on <?php echo date('M d, Y', strtotime($vaccination['administration_date'])); ?>
                                                by <?php echo $vaccination['administered_by_name']; ?>
                                            <?php else: ?>
                                                Scheduled for <?php echo date('M d, Y', strtotime($vaccination['administration_date'])); ?>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ($vaccination['vaccine_brand']): ?>
                                            <div class="text-sm text-gray-600 mt-1">
                                                Brand: <?php echo $vaccination['vaccine_brand']; ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($vaccination['adverse_reactions']): ?>
                                            <div class="text-sm text-red-600 mt-2">
                                                <i class="fas fa-exclamation-triangle mr-1"></i> Adverse reaction reported
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- Admin controls for marking vaccination as completed -->
                                        <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'staff'): ?>
                                            <?php if ($vaccination['status'] === 'scheduled'): ?>
                                                <div class="mt-3 flex space-x-2">
                                                    <button onclick="markAsAdministered(<?php echo $vaccination['id']; ?>)" 
                                                            class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                                                        <i class="fas fa-check mr-1"></i>Mark as Completed
                                                    </button>
                                                    <button onclick="markAsMissed(<?php echo $vaccination['id']; ?>)" 
                                                            class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">
                                                        <i class="fas fa-times mr-1"></i>Mark as Missed
                                                    </button>
                                                </div>
                                            <?php elseif ($vaccination['status'] === 'missed'): ?>
                                                <div class="mt-3">
                                                    <button onclick="markAsAdministered(<?php echo $vaccination['id']; ?>)" 
                                                            class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                                                        <i class="fas fa-check mr-1"></i>Mark as Completed
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Treatment Progress Tab -->
                    <div id="treatment" class="tab-content">
                        <h3 class="text-lg font-semibold mb-4">Treatment Progress</h3>
                        
                        <?php if (empty($treatmentProgress)): ?>
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-procedures text-4xl mb-2"></i>
                                <p>No treatment progress records found</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($treatmentProgress as $progress): ?>
                                <div class="bg-gray-50 p-6 rounded-lg mb-4">
                                    <div class="flex items-center justify-between mb-4">
                                        <div>
                                            <h4 class="font-semibold">Treatment Started: <?php echo date('M d, Y', strtotime($progress['treatment_start_date'])); ?></h4>
                                            <p class="text-sm text-gray-600">
                                                Animal: <?php echo ucfirst($progress['animal_type']); ?> • 
                                                Bite Date: <?php echo date('M d, Y', strtotime($progress['bite_date'])); ?>
                                            </p>
                                        </div>
                                        <span class="px-3 py-1 text-sm rounded-full bg-<?php 
                                            echo $progress['treatment_status'] == 'completed' ? 'green' : 
                                                 ($progress['treatment_status'] == 'ongoing' ? 'blue' : 'red'); 
                                        ?>-100 text-<?php 
                                            echo $progress['treatment_status'] == 'completed' ? 'green' : 
                                                 ($progress['treatment_status'] == 'ongoing' ? 'blue' : 'red'); 
                                        ?>-800">
                                            <?php echo ucfirst($progress['treatment_status']); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <div class="flex justify-between text-sm mb-2">
                                            <span>Progress</span>
                                            <span><?php echo $progress['doses_completed']; ?> / <?php echo $progress['total_doses_required']; ?> doses</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo ($progress['doses_completed'] / $progress['total_doses_required']) * 100; ?>%"></div>
                                        </div>
                                    </div>
                                    
                                    <?php if ($progress['next_appointment_date']): ?>
                                        <div class="text-sm text-gray-600">
                                            <i class="fas fa-calendar-alt mr-1"></i>
                                            Next appointment: <?php echo date('M d, Y', strtotime($progress['next_appointment_date'])); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($progress['notes']): ?>
                                        <div class="mt-3 p-3 bg-white rounded text-sm">
                                            <strong>Notes:</strong> <?php echo $progress['notes']; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
                content.style.display = 'none';
            });
            
            // Remove active class from all tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active');
                button.classList.remove('border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Show selected tab
            const selectedTab = document.getElementById(tabName);
            if (selectedTab) {
                selectedTab.classList.add('active');
                selectedTab.style.display = 'block';
            }
            
            // Highlight active tab button
            const activeButton = document.querySelector(`button[onclick="showTab('${tabName}')"]`);
            if (activeButton) {
                activeButton.classList.add('active', 'border-blue-500', 'text-blue-600');
                activeButton.classList.remove('border-transparent', 'text-gray-500');
            }
        }
        
        // Initialize tabs on page load
        document.addEventListener('DOMContentLoaded', function() {
            showTab('overview');
        });
        
        function approveAppointment(appointmentId) {
            if (confirm('Are you sure you want to approve this appointment?')) {
                fetch('<?php echo BASE_URL; ?>appointments/updateStatus', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'appointment_id=' + appointmentId + '&status=confirmed'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Appointment approved successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
        
        function cancelAppointment(appointmentId) {
            if (confirm('Are you sure you want to cancel this appointment?')) {
                fetch('<?php echo BASE_URL; ?>appointments/updateStatus', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'appointment_id=' + appointmentId + '&status=cancelled'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Appointment cancelled successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }

        function markAsAdministered(vaccinationId) {
            if (confirm('Are you sure you want to mark this vaccination as completed?')) {
                fetch('<?php echo BASE_URL; ?>vaccinations/updateStatus', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'vaccination_id=' + vaccinationId + '&status=administered'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Vaccination marked as completed successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating vaccination status');
                });
            }
        }
        
        function markAsMissed(vaccinationId) {
            if (confirm('Are you sure you want to mark this vaccination as missed?')) {
                fetch('<?php echo BASE_URL; ?>vaccinations/updateStatus', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'vaccination_id=' + vaccinationId + '&status=missed'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Vaccination marked as missed successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating vaccination status');
                });
            }
        }

        function printPatientCard() {
            window.print();
        }
    </script>
</body>
</html>
