<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Vaccination Schedule - BiteCare Patient Portal</title>
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
                        <a href="<?php echo BASE_URL; ?>vaccinations/myVaccinations" class="flex items-center space-x-3 text-gray-700 hover:bg-gray-100 p-3 rounded-lg">
                            <i class="fas fa-syringe"></i>
                            <span>My Vaccinations</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>vaccinations/patientSchedule" class="flex items-center space-x-3 text-blue-600 bg-blue-50 p-3 rounded-lg">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Vaccination Schedule</span>
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
                        <h1 class="text-3xl font-bold text-gray-900">My Vaccination Schedule</h1>
                        <p class="text-gray-600">View your complete vaccination schedule based on confirmed appointments</p>
                    </div>
                    <a href="<?php echo BASE_URL; ?>appointments/create" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-calendar-plus mr-2"></i>Book Appointment
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

            <!-- Schedule Overview -->
            <?php
            $upcomingCount = 0;
            $completedCount = 0;
            $todayCount = 0;
            $today = date('Y-m-d');
            
            foreach ($schedule as $vaccination) {
                if ($vaccination['status'] === 'administered') {
                    $completedCount++;
                } else {
                    $upcomingCount++;
                    if ($vaccination['administration_date'] === $today) {
                        $todayCount++;
                    }
                }
            }
            ?>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Upcoming</h3>
                            <p class="text-2xl font-bold text-blue-600"><?php echo $upcomingCount; ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-full mr-4">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Completed</h3>
                            <p class="text-2xl font-bold text-green-600"><?php echo $completedCount; ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center">
                        <div class="bg-purple-100 p-3 rounded-full mr-4">
                            <i class="fas fa-clock text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Today</h3>
                            <p class="text-2xl font-bold text-purple-600"><?php echo $todayCount; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vaccination Schedule Timeline -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-semibold mb-6 text-gray-900">
                    <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>Vaccination Schedule Timeline
                </h3>
                
                <?php if (empty($schedule)): ?>
                    <div class="text-center py-8">
                        <i class="fas fa-calendar-times text-gray-400 text-6xl mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No Vaccination Schedule Found</h3>
                        <p class="text-gray-600 mb-4">You don't have any confirmed appointments with vaccination schedules.</p>
                        <a href="<?php echo BASE_URL; ?>appointments/create" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i>Book Appointment
                        </a>
                    </div>
                <?php else: ?>
                    <div class="space-y-6">
                        <?php foreach ($schedule as $vaccination): ?>
                            <div class="relative">
                                <!-- Timeline line -->
                                <div class="absolute left-6 top-8 bottom-0 w-0.5 bg-gray-300"></div>
                                
                                <!-- Timeline item -->
                                <div class="flex items-start space-x-4">
                                    <!-- Timeline dot -->
                                    <div class="relative z-10">
                                        <?php if ($vaccination['status'] === 'administered'): ?>
                                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                                                <i class="fas fa-check text-white"></i>
                                            </div>
                                        <?php elseif ($vaccination['administration_date'] < $today): ?>
                                            <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center">
                                                <i class="fas fa-times text-white"></i>
                                            </div>
                                        <?php elseif ($vaccination['administration_date'] === $today): ?>
                                            <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center animate-pulse">
                                                <i class="fas fa-clock text-white"></i>
                                            </div>
                                        <?php else: ?>
                                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                                                <i class="fas fa-calendar text-white"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Content -->
                                    <div class="flex-1 bg-gray-50 rounded-lg p-6">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <h4 class="text-lg font-semibold text-gray-900">
                                                    <?php echo ucfirst(str_replace('_', ' ', $vaccination['vaccine_type'])); ?> - Dose <?php echo $vaccination['dose_number']; ?>
                                                </h4>
                                                
                                                <div class="mt-2 space-y-1">
                                                    <p class="text-gray-600">
                                                        <i class="fas fa-calendar mr-2"></i>
                                                        <?php echo date('l, F j, Y', strtotime($vaccination['administration_date'])); ?>
                                                    </p>
                                                    
                                                    <?php if (!empty($vaccination['administration_time'])): ?>
                                                        <p class="text-gray-600">
                                                            <i class="fas fa-clock mr-2"></i>
                                                            <?php echo date('g:i A', strtotime($vaccination['administration_time'])); ?>
                                                        </p>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (!empty($vaccination['vaccine_brand'])): ?>
                                                        <p class="text-gray-600">
                                                            <i class="fas fa-prescription-bottle mr-2"></i>
                                                            <?php echo htmlspecialchars($vaccination['vaccine_brand']); ?>
                                                        </p>
                                                    <?php endif; ?>
                                                    
                                                    <p class="text-gray-600">
                                                        <i class="fas fa-map-marker-alt mr-2"></i>
                                                        <?php echo ucfirst(str_replace('_', ' ', $vaccination['administration_site'])); ?>
                                                    </p>
                                                </div>
                                                
                                                <?php if (!empty($vaccination['appointment_status'])): ?>
                                                    <div class="mt-3">
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                                            <?php echo $vaccination['appointment_status'] === 'confirmed' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'; ?>">
                                                            <i class="fas fa-<?php echo $vaccination['appointment_status'] === 'confirmed' ? 'check' : 'check-double'; ?> mr-1"></i>
                                                            Appointment <?php echo ucfirst($vaccination['appointment_status']); ?>
                                                        </span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <!-- Status Badge -->
                                            <div>
                                                <?php
                                                $statusClass = 'bg-blue-100 text-blue-800';
                                                $statusIcon = 'clock';
                                                $statusText = 'Scheduled';
                                                
                                                if ($vaccination['status'] === 'administered') {
                                                    $statusClass = 'bg-green-100 text-green-800';
                                                    $statusIcon = 'check';
                                                    $statusText = 'Administered';
                                                } elseif ($vaccination['status'] === 'missed') {
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
                                        
                                        <!-- Alert for today's vaccination -->
                                        <?php if ($vaccination['administration_date'] === $today && $vaccination['status'] === 'scheduled'): ?>
                                        <div class="mt-4 p-3 bg-purple-100 border border-purple-300 rounded-lg">
                                            <p class="text-purple-800 text-sm">
                                                <i class="fas fa-bell mr-2"></i>
                                                <strong>Today's vaccination!</strong> Please arrive 15 minutes before your scheduled time.
                                            </p>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <!-- Alert for overdue vaccination -->
                                        <?php if ($vaccination['administration_date'] < $today && $vaccination['status'] === 'scheduled'): ?>
                                        <div class="mt-4 p-3 bg-red-100 border border-red-300 rounded-lg">
                                            <p class="text-red-800 text-sm">
                                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                                <strong>Overdue vaccination!</strong> Please contact the clinic immediately to reschedule.
                                            </p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
