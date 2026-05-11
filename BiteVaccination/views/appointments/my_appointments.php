<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments - BiteCare Patient Portal</title>
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
                        <a href="<?php echo BASE_URL; ?>my-appointments" class="flex items-center space-x-3 text-blue-600 bg-blue-50 p-3 rounded-lg">
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
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">My Appointments</h1>
                        <p class="text-gray-600">View and manage your vaccination appointments</p>
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

            <!-- Appointments List -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-semibold mb-4 text-gray-900">
                    <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>Appointment History
                </h3>
                
                <?php if (empty($appointments)): ?>
                    <div class="text-center py-8">
                        <i class="fas fa-calendar-times text-gray-400 text-5xl mb-4"></i>
                        <p class="text-gray-600">No appointments found</p>
                        <a href="<?php echo BASE_URL; ?>appointments/create" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i>Book Your First Appointment
                        </a>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($appointments as $appointment): ?>
                            <div class="border-l-4 <?php echo $appointment['status'] === 'confirmed' ? 'border-green-500 bg-green-50' : ($appointment['status'] === 'scheduled' ? 'border-blue-500 bg-blue-50' : 'border-gray-300 bg-gray-50'); ?> p-4 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">
                                            <?php echo ucfirst(str_replace('_', ' ', $appointment['appointment_type'])); ?>
                                        </h4>
                                        <p class="text-gray-600 mt-1">
                                            <i class="fas fa-calendar mr-2"></i><?php echo date('F j, Y', strtotime($appointment['appointment_date'])); ?>
                                            <i class="fas fa-clock ml-4 mr-2"></i><?php echo date('g:i A', strtotime($appointment['appointment_time'])); ?>
                                        </p>
                                        <?php if (!empty($appointment['notes'])): ?>
                                            <p class="text-sm text-gray-500 mt-2">
                                                <i class="fas fa-sticky-note mr-2"></i><?php echo htmlspecialchars($appointment['notes']); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-right">
                                        <?php if ($appointment['status'] === 'confirmed'): ?>
                                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                                <i class="fas fa-check mr-1"></i>Confirmed
                                            </span>
                                        <?php elseif ($appointment['status'] === 'scheduled'): ?>
                                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                                <i class="fas fa-clock mr-1"></i>Pending Approval
                                            </span>
                                        <?php else: ?>
                                            <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium">
                                                <?php echo ucfirst($appointment['status']); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <div class="flex justify-center mt-6">
                            <nav class="flex space-x-2">
                                <?php if ($currentPage > 1): ?>
                                    <a href="?page=<?php echo $currentPage - 1; ?>" 
                                       class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                <?php endif; ?>
                                
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <?php if ($i == $currentPage): ?>
                                        <span class="px-3 py-2 bg-blue-600 text-white rounded-lg">
                                            <?php echo $i; ?>
                                        </span>
                                    <?php else: ?>
                                        <a href="?page=<?php echo $i; ?>" 
                                           class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                                            <?php echo $i; ?>
                                        </a>
                                    <?php endif; ?>
                                <?php endfor; ?>
                                
                                <?php if ($currentPage < $totalPages): ?>
                                    <a href="?page=<?php echo $currentPage + 1; ?>" 
                                       class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                <?php endif; ?>
                            </nav>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
