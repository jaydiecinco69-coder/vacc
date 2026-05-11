<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vaccination Schedule - BiteCare Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .schedule-card {
            transition: all 0.3s ease;
        }
        .schedule-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .dose-indicator {
            transition: all 0.3s ease;
        }
        .dose-indicator:hover {
            transform: scale(1.05);
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
                        <a href="<?php echo BASE_URL; ?>vaccinations/schedule" class="flex items-center space-x-3 text-blue-600 bg-blue-50 p-3 rounded-lg">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Vaccination Schedule</span>
                        </a>
                    </li>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                    <li>
                        <a href="<?php echo BASE_URL; ?>vaccinations/create-schedule" class="flex items-center space-x-3 text-green-600 hover:bg-green-50 p-3 rounded-lg">
                            <i class="fas fa-plus-circle"></i>
                            <span>Create Schedule</span>
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
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Vaccination Schedule</h1>
                        <p class="text-gray-600">View vaccination schedules for patients with confirmed appointments</p>
                    </div>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="<?php echo BASE_URL; ?>vaccinations/create-schedule" 
                       class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        <i class="fas fa-plus mr-2"></i>Create New Schedule
                    </a>
                    <?php endif; ?>
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

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-lg p-6 schedule-card">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Total Patients</h3>
                            <p class="text-2xl font-bold text-blue-600"><?php echo count($patients); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-6 schedule-card">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-full mr-4">
                            <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Scheduled Today</h3>
                            <p class="text-2xl font-bold text-green-600"><?php echo $todayCount; ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-6 schedule-card">
                    <div class="flex items-center">
                        <div class="bg-purple-100 p-3 rounded-full mr-4">
                            <i class="fas fa-clock text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Upcoming This Week</h3>
                            <p class="text-2xl font-bold text-purple-600"><?php echo $weekCount; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Tabs -->
            <div class="bg-white rounded-xl shadow-lg p-4 mb-6">
                <div class="flex space-x-4 border-b">
                    <button onclick="filterSchedules('all')" class="filter-tab px-4 py-2 font-medium text-blue-600 border-b-2 border-blue-600" data-filter="all">
                        All Schedules
                    </button>
                    <button onclick="filterSchedules('today')" class="filter-tab px-4 py-2 font-medium text-gray-600 hover:text-blue-600" data-filter="today">
                        Today
                    </button>
                    <button onclick="filterSchedules('week')" class="filter-tab px-4 py-2 font-medium text-gray-600 hover:text-blue-600" data-filter="week">
                        This Week
                    </button>
                    <button onclick="filterSchedules('overdue')" class="filter-tab px-4 py-2 font-medium text-gray-600 hover:text-blue-600" data-filter="overdue">
                        Overdue
                    </button>
                </div>
            </div>

            <!-- Vaccination Schedules -->
            <div class="space-y-6">
                <?php if (empty($schedules)): ?>
                    <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                        <i class="fas fa-calendar-times text-gray-400 text-6xl mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No Vaccination Schedules Found</h3>
                        <p class="text-gray-600 mb-4">There are no vaccination schedules for patients with confirmed appointments.</p>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a href="<?php echo BASE_URL; ?>vaccinations/create-schedule" class="inline-block px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <i class="fas fa-plus mr-2"></i>Create First Schedule
                        </a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <?php foreach ($schedules as $schedule): ?>
                        <div class="bg-white rounded-xl shadow-lg p-6 schedule-card" data-date="<?php echo $schedule['administration_date']; ?>">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <!-- Patient Info -->
                                    <div class="flex items-center mb-4">
                                        <div class="bg-blue-100 p-2 rounded-full mr-3">
                                            <i class="fas fa-user text-blue-600"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">
                                                <?php echo htmlspecialchars($schedule['first_name'] . ' ' . $schedule['last_name']); ?>
                                            </h3>
                                            <p class="text-sm text-gray-600">Patient ID: <?php echo htmlspecialchars($schedule['patient_id']); ?></p>
                                            <?php if (!empty($schedule['phone'])): ?>
                                                <p class="text-sm text-gray-600">
                                                    <i class="fas fa-phone mr-1"></i><?php echo htmlspecialchars($schedule['phone']); ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Vaccination Details -->
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div class="flex items-center">
                                            <div class="dose-indicator bg-purple-100 p-2 rounded-lg mr-3">
                                                <i class="fas fa-syringe text-purple-600"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-600">Vaccine Type</p>
                                                <p class="font-medium text-gray-900">
                                                    <?php echo ucfirst(str_replace('_', ' ', $schedule['vaccine_type'])); ?>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex items-center">
                                            <div class="dose-indicator bg-blue-100 p-2 rounded-lg mr-3">
                                                <i class="fas fa-hashtag text-blue-600"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-600">Dose Number</p>
                                                <p class="font-medium text-gray-900">Dose <?php echo $schedule['dose_number']; ?></p>
                                            </div>
                                        </div>

                                        <div class="flex items-center">
                                            <div class="dose-indicator bg-green-100 p-2 rounded-lg mr-3">
                                                <i class="fas fa-calendar text-green-600"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-600">Scheduled Date</p>
                                                <p class="font-medium text-gray-900">
                                                    <?php echo date('M j, Y', strtotime($schedule['administration_date'])); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Additional Details -->
                                    <?php if (!empty($schedule['vaccine_brand']) || !empty($schedule['administration_time'])): ?>
                                    <div class="mt-4 pt-4 border-t grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <?php if (!empty($schedule['administration_time'])): ?>
                                        <div class="flex items-center text-sm">
                                            <i class="fas fa-clock text-gray-400 mr-2"></i>
                                            <span class="text-gray-600">Time:</span>
                                            <span class="ml-2 font-medium"><?php echo date('g:i A', strtotime($schedule['administration_time'])); ?></span>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($schedule['vaccine_brand'])): ?>
                                        <div class="flex items-center text-sm">
                                            <i class="fas fa-prescription-bottle text-gray-400 mr-2"></i>
                                            <span class="text-gray-600">Brand:</span>
                                            <span class="ml-2 font-medium"><?php echo htmlspecialchars($schedule['vaccine_brand']); ?></span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>

                                    <!-- Appointment Status -->
                                    <?php if (!empty($schedule['appointment_status'])): ?>
                                    <div class="mt-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                            <?php echo $schedule['appointment_status'] === 'confirmed' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'; ?>">
                                            <i class="fas fa-<?php echo $schedule['appointment_status'] === 'confirmed' ? 'check' : 'check-double'; ?> mr-1"></i>
                                            Appointment <?php echo ucfirst($schedule['appointment_status']); ?>
                                        </span>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Status Badge -->
                                <div class="ml-4">
                                    <?php
                                    $statusClass = 'bg-blue-100 text-blue-800';
                                    $statusIcon = 'clock';
                                    $statusText = 'Scheduled';
                                    
                                    if ($schedule['status'] === 'administered') {
                                        $statusClass = 'bg-green-100 text-green-800';
                                        $statusIcon = 'check';
                                        $statusText = 'Administered';
                                    } elseif ($schedule['status'] === 'missed') {
                                        $statusClass = 'bg-red-100 text-red-800';
                                        $statusIcon = 'times';
                                        $statusText = 'Missed';
                                    }
                                    ?>
                                    <span class="inline-flex items-center px-3 py-2 rounded-full text-sm font-medium <?php echo $statusClass; ?>">
                                        <i class="fas fa-<?php echo $statusIcon; ?> mr-2"></i>
                                        <?php echo $statusText; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        function filterSchedules(filter) {
            // Update tab styles
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
                tab.classList.add('text-gray-600');
            });
            
            const activeTab = document.querySelector(`[data-filter="${filter}"]`);
            activeTab.classList.remove('text-gray-600');
            activeTab.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
            
            // Filter schedules
            const schedules = document.querySelectorAll('.schedule-card');
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            schedules.forEach(card => {
                const scheduleDate = new Date(card.dataset.date);
                scheduleDate.setHours(0, 0, 0, 0);
                
                let show = false;
                
                switch(filter) {
                    case 'all':
                        show = true;
                        break;
                    case 'today':
                        show = scheduleDate.getTime() === today.getTime();
                        break;
                    case 'week':
                        const weekFromNow = new Date(today);
                        weekFromNow.setDate(weekFromNow.getDate() + 7);
                        show = scheduleDate >= today && scheduleDate <= weekFromNow;
                        break;
                    case 'overdue':
                        show = scheduleDate < today;
                        break;
                }
                
                card.style.display = show ? 'block' : 'none';
            });
        }
    </script>
</body>
</html>
