<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Animal Bite Center Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stat-card {
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
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
                    <span class="font-bold text-xl text-gray-800">BiteCare Admin</span>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <i class="fas fa-bell text-gray-600 hover:text-blue-600 cursor-pointer"></i>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">3</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <img src="https://picsum.photos/seed/admin/40/40.jpg" alt="Profile" class="w-8 h-8 rounded-full">
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
                        <a href="<?php echo BASE_URL; ?>appointments" class="flex items-center space-x-3 text-gray-700 hover:bg-gray-100 p-3 rounded-lg">
                            <i class="fas fa-calendar-check"></i>
                            <span>Appointments</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>patients" class="flex items-center space-x-3 text-gray-700 hover:bg-gray-100 p-3 rounded-lg">
                            <i class="fas fa-users"></i>
                            <span>Patients</span>
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
                <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
                <p class="text-gray-600">Welcome back, <?php echo $_SESSION['full_name']; ?>!</p>
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

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="stat-card bg-white p-6 rounded-xl shadow-lg border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Total Patients</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo number_format($stats['total_patients']); ?></p>
                            <p class="text-green-600 text-sm mt-1">
                                <i class="fas fa-arrow-up"></i> 12% from last month
                            </p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card bg-white p-6 rounded-xl shadow-lg border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Total Appointments</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo number_format($stats['total_appointments']); ?></p>
                            <p class="text-green-600 text-sm mt-1">
                                <i class="fas fa-arrow-up"></i> 8% from last month
                            </p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card bg-white p-6 rounded-xl shadow-lg border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Completed Vaccinations</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo number_format($stats['completed_vaccinations']); ?></p>
                            <p class="text-green-600 text-sm mt-1">
                                <i class="fas fa-arrow-up"></i> 15% from last month
                            </p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-full">
                            <i class="fas fa-syringe text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card bg-white p-6 rounded-xl shadow-lg border-l-4 border-orange-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Active Users</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo number_format($stats['active_users']); ?></p>
                            <p class="text-green-600 text-sm mt-1">
                                <i class="fas fa-arrow-up"></i> 5% from last month
                            </p>
                        </div>
                        <div class="bg-orange-100 p-3 rounded-full">
                            <i class="fas fa-user-shield text-orange-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            
            <!-- Tables Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Activities -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h3 class="text-lg font-semibold mb-4">Recent Activities</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-2">User</th>
                                    <th class="text-left py-2">Action</th>
                                    <th class="text-left py-2">Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentActivities as $activity): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-2">
                                        <div class="flex items-center">
                                            <img src="https://picsum.photos/seed/<?php echo $activity['full_name']; ?>/32/32.jpg" 
                                                 alt="<?php echo $activity['full_name']; ?>" 
                                                 class="w-8 h-8 rounded-full mr-2">
                                            <span class="text-sm"><?php echo $activity['full_name']; ?></span>
                                        </div>
                                    </td>
                                    <td class="py-2">
                                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                            <?php echo $activity['action']; ?>
                                        </span>
                                    </td>
                                    <td class="py-2 text-gray-500 text-xs">
                                        <?php echo date('M d, H:i', strtotime($activity['created_at'])); ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Upcoming Appointments -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h3 class="text-lg font-semibold mb-4">Upcoming Appointments</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-2">Patient</th>
                                    <th class="text-left py-2">Date</th>
                                    <th class="text-left py-2">Time</th>
                                    <th class="text-left py-2">Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($upcomingAppointments as $appointment): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-2">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                                <i class="fas fa-user text-blue-600 text-xs"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium"><?php echo $appointment['first_name'] . ' ' . $appointment['last_name']; ?></div>
                                                <div class="text-xs text-gray-500"><?php echo $appointment['patient_id']; ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-2 text-xs">
                                        <?php echo date('M d', strtotime($appointment['appointment_date'])); ?>
                                    </td>
                                    <td class="py-2 text-xs">
                                        <?php echo date('H:i', strtotime($appointment['appointment_time'])); ?>
                                    </td>
                                    <td class="py-2">
                                        <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">
                                            <?php echo ucfirst(str_replace('_', ' ', $appointment['appointment_type'])); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Vaccination Trends Chart
        const vaccinationCtx = document.getElementById('vaccinationChart').getContext('2d');
        new Chart(vaccinationCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Anti-Rabies',
                    data: [65, 78, 90, 81, 96, 105],
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4
                }, {
                    label: 'Tetanus',
                    data: [28, 35, 40, 38, 45, 52],
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // User Role Distribution Chart
        const userRoleCtx = document.getElementById('userRoleChart').getContext('2d');
        new Chart(userRoleCtx, {
            type: 'doughnut',
            data: {
                labels: ['Admin', 'Staff', 'Receptionist', 'Patient'],
                datasets: [{
                    data: [
                        <?php echo $stats['user_stats']['admin_count']; ?>,
                        <?php echo $stats['user_stats']['staff_count']; ?>,
                        <?php echo $stats['user_stats']['receptionist_count']; ?>,
                        <?php echo $stats['user_stats']['patient_count']; ?>
                    ],
                    backgroundColor: [
                        'rgb(239, 68, 68)',
                        'rgb(59, 130, 246)',
                        'rgb(34, 197, 94)',
                        'rgb(251, 191, 36)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });

        // Auto-refresh dashboard data every 30 seconds
        setInterval(() => {
            // Add refresh logic here if needed
            console.log('Dashboard data refreshed');
        }, 30000);
    </script>
</body>
</html>
