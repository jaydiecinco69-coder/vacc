<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Vaccinations - BiteCare Patient Portal</title>
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
                    <span class="font-bold text-xl text-gray-800">BiteCare Patient Portal</span>
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
                        <a href="<?php echo BASE_URL; ?>my-appointments" class="flex items-center space-x-3 text-gray-700 hover:bg-gray-100 p-3 rounded-lg">
                            <i class="fas fa-calendar-check"></i>
                            <span>My Appointments</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>appointments/create" class="flex items-center space-x-3 text-gray-700 hover:bg-gray-100 p-3 rounded-lg">
                            <i class="fas fa-calendar-plus"></i>
                            <span>Book Appointment</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>vaccinations/myVaccinations" class="flex items-center space-x-3 text-blue-600 bg-blue-50 p-3 rounded-lg">
                            <i class="fas fa-syringe"></i>
                            <span>My Vaccinations</span>
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
                        <h1 class="text-3xl font-bold text-gray-900">My Anti-Rabies Vaccinations</h1>
                        <p class="text-gray-600">View your upcoming vaccination schedule and history</p>
                    </div>
                    <a href="<?php echo BASE_URL; ?>appointments/create" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-calendar-plus mr-2"></i>Book New Appointment
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

            <!-- Patient Info Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-full mr-4">
                        <i class="fas fa-user text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900"><?php echo htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']); ?></h2>
                        <p class="text-gray-600">Patient ID: <?php echo htmlspecialchars($patient['patient_id']); ?></p>
                    </div>
                </div>
            </div>

            <!-- Integration Notice -->
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                <div class="flex items-start">
                    <div class="bg-green-100 p-2 rounded-full mr-3">
                        <i class="fas fa-info-circle text-green-600"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-green-900">Automatic Vaccination Scheduling</h4>
                        <p class="text-green-800 text-sm mt-1">
                            When your appointment is confirmed by the admin, your first vaccination dose will be automatically scheduled. 
                            You'll receive all subsequent dose appointments according to the anti-rabies vaccination schedule.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Upcoming Vaccinations -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <h3 class="text-xl font-semibold mb-4 text-gray-900">
                    <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>Upcoming Vaccinations
                </h3>
                
                <?php if (empty($upcomingVaccinations)): ?>
                    <div class="text-center py-8">
                        <i class="fas fa-calendar-check text-gray-400 text-5xl mb-4"></i>
                        <p class="text-gray-600">No upcoming vaccinations scheduled</p>
                        <a href="<?php echo BASE_URL; ?>appointments/create" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i>Schedule Vaccination
                        </a>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($upcomingVaccinations as $vaccination): ?>
                            <div class="border-l-4 border-blue-500 bg-blue-50 p-4 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Dose <?php echo $vaccination['dose_number']; ?> - Anti-Rabies Vaccine</h4>
                                        <p class="text-gray-600 mt-1">
                                            <i class="fas fa-calendar mr-2"></i><?php echo date('F j, Y', strtotime($vaccination['administration_date'])); ?>
                                            <i class="fas fa-clock ml-4 mr-2"></i><?php echo date('g:i A', strtotime($vaccination['administration_time'])); ?>
                                        </p>
                                        <?php if (!empty($vaccination['vaccine_brand'])): ?>
                                            <p class="text-sm text-gray-500 mt-1">
                                                <i class="fas fa-prescription-bottle mr-2"></i><?php echo htmlspecialchars($vaccination['vaccine_brand']); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-right">
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                            Scheduled
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Vaccination History -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-semibold mb-4 text-gray-900">
                    <i class="fas fa-history mr-2 text-green-600"></i>Vaccination History
                </h3>
                
                <?php if (empty($vaccinationHistory)): ?>
                    <div class="text-center py-8">
                        <i class="fas fa-syringe text-gray-400 text-5xl mb-4"></i>
                        <p class="text-gray-600">No vaccination records found</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-3 px-4 font-semibold text-gray-900">Dose</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-900">Date</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-900">Time</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-900">Vaccine</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-900">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($vaccinationHistory as $vaccination): ?>
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-4">
                                            <?php if ($vaccination['vaccine_type'] === 'anti_rabies'): ?>
                                                Dose <?php echo $vaccination['dose_number']; ?>
                                            <?php else: ?>
                                                <?php echo ucfirst(str_replace('_', ' ', $vaccination['vaccine_type'])); ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3 px-4"><?php echo date('M j, Y', strtotime($vaccination['administration_date'])); ?></td>
                                        <td class="py-3 px-4"><?php echo date('g:i A', strtotime($vaccination['administration_time'])); ?></td>
                                        <td class="py-3 px-4">
                                            <?php if (!empty($vaccination['vaccine_brand'])): ?>
                                                <?php echo htmlspecialchars($vaccination['vaccine_brand']); ?>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3 px-4">
                                            <?php if ($vaccination['status'] === 'administered'): ?>
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                                    <i class="fas fa-check mr-1"></i>Administered
                                                </span>
                                            <?php else: ?>
                                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                                    <i class="fas fa-clock mr-1"></i>Scheduled
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
