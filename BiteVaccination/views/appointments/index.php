<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments - Animal Bite Center Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .appointment-row {
            transition: all 0.3s ease;
        }
        .appointment-row:hover {
            background-color: #f3f4f6;
            transform: translateX(5px);
        }
        .calendar-day {
            min-height: 100px;
            transition: all 0.3s ease;
        }
        .calendar-day:hover {
            background-color: #f9fafb;
        }
        .calendar-day.today {
            background-color: #dbeafe;
        }
        .calendar-day.has-appointments {
            background-color: #fef3c7;
        }
    </style>
</head>
<body class="bg-gray-50">
    <?php
        if (!isset($userRole) && isset($_SESSION['role'])) {
            $userRole = $_SESSION['role'];
        }
    ?>
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
                    <?php if (isset($userRole) && $userRole === ROLE_PATIENT): ?>
                        <li>
                            <a href="<?php echo BASE_URL; ?>dashboard" class="flex items-center space-x-3 text-gray-700 hover:bg-gray-100 p-3 rounded-lg">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>patients/view?id=<?php echo isset($patientId) ? $patientId : 0; ?>" class="flex items-center space-x-3 text-gray-700 hover:bg-gray-100 p-3 rounded-lg">
                                <i class="fas fa-user"></i>
                                <span>My Profile</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>my-appointments" class="flex items-center space-x-3 text-blue-600 bg-blue-50 p-3 rounded-lg">
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
                                            <?php endif; ?>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <?php if (isset($userRole) && $userRole === ROLE_PATIENT): ?>
                            <h1 class="text-3xl font-bold text-gray-900">My Appointments</h1>
                            <p class="text-gray-600">View your requested vaccination appointments and status.</p>
                        <?php else: ?>
                            <h1 class="text-3xl font-bold text-gray-900">Appointment Management</h1>
                            <p class="text-gray-600">Schedule and manage patient appointments</p>
                        <?php endif; ?>
                    </div>
                    <div class="flex space-x-2">
                        <?php if (!isset($userRole) || $userRole === ROLE_RECEPTIONIST): ?>
                            <a href="<?php echo BASE_URL; ?>appointments/create" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                <i class="fas fa-plus mr-2"></i>Schedule Appointment
                            </a>
                        <?php endif; ?>
                        <a href="<?php echo BASE_URL; ?>appointments/calendar" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                            <i class="fas fa-calendar-alt mr-2"></i>Calendar View
                        </a>
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

            <!-- Filters -->
            <div class="bg-white p-6 rounded-xl shadow-lg mb-6">
                <form method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Date</label>
                        <input type="date" name="date" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               value="<?php echo $filterDate; ?>">
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
                        <select name="status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Status</option>
                            <option value="scheduled" <?php echo $filterStatus == 'scheduled' ? 'selected' : ''; ?>>Scheduled</option>
                            <option value="confirmed" <?php echo $filterStatus == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                            <option value="in_progress" <?php echo $filterStatus == 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                            <option value="completed" <?php echo $filterStatus == 'completed' ? 'selected' : ''; ?>>Completed</option>
                            <option value="cancelled" <?php echo $filterStatus == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            <option value="no_show" <?php echo $filterStatus == 'no_show' ? 'selected' : ''; ?>>No Show</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 mr-2">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                        <a href="<?php echo BASE_URL; ?>appointments" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                            <i class="fas fa-times mr-2"></i>Clear
                        </a>
                    </div>
                </form>
            </div>

            
            <!-- Patient-specific header for patients -->
            <?php if (isset($userRole) && $userRole === ROLE_PATIENT): ?>
            <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg mb-6">
                <h3 class="text-lg font-semibold text-blue-800 mb-2">Your Appointments</h3>
                <p class="text-blue-600">View and manage your scheduled vaccination appointments.</p>
            </div>
            <?php endif; ?>

            <!-- Quick Stats -->
            <?php if (isset($userRole) && $userRole !== ROLE_PATIENT): ?>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Today's Appointments</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo number_format(isset($todayAppointments) ? $todayAppointments : 0); ?></p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-calendar-day text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Confirmed</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo number_format(isset($confirmedAppointments) ? $confirmedAppointments : 0); ?></p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Pending</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo number_format(isset($pendingAppointments) ? $pendingAppointments : 0); ?></p>
                        </div>
                        <div class="bg-yellow-100 p-3 rounded-full">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-red-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Cancelled</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo number_format(isset($cancelledAppointments) ? $cancelledAppointments : 0); ?></p>
                        </div>
                        <div class="bg-red-100 p-3 rounded-full">
                            <i class="fas fa-times-circle text-red-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Appointments Table -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <?php if (!isset($userRole) || $userRole !== ROLE_PATIENT): ?>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff</th>
                                <?php endif; ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (empty($appointments)): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-calendar-times text-4xl mb-2"></i>
                                        <p>No appointments found</p>
                                        <?php if ($filterDate || $filterStatus): ?>
                                            <p class="text-sm">Try adjusting your filters</p>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($appointments as $appointment): ?>
                                    <tr class="appointment-row">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                        <i class="fas fa-user text-blue-600"></i>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?php echo $appointment['first_name'] . ' ' . $appointment['last_name']; ?>
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        <?php echo $appointment['patient_id']; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <?php echo date('M d, Y', strtotime($appointment['appointment_date'])); ?>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <?php echo date('H:i', strtotime($appointment['appointment_time'])); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                <?php echo ucfirst(str_replace('_', ' ', $appointment['appointment_type'])); ?>
                                            </span>
                                        </td>
                                        <?php if (!isset($userRole) || $userRole !== ROLE_PATIENT): ?>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?php echo $appointment['staff_name'] ?: 'Not assigned'; ?>
                                            </td>
                                        <?php endif; ?>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-<?php 
                                                echo $appointment['status'] == 'completed' ? 'green' : 
                                                     ($appointment['status'] == 'cancelled' ? 'red' : 
                                                      ($appointment['status'] == 'in_progress' ? 'yellow' : 'blue')); 
                                            ?>-100 text-<?php 
                                                echo $appointment['status'] == 'completed' ? 'green' : 
                                                     ($appointment['status'] == 'cancelled' ? 'red' : 
                                                      ($appointment['status'] == 'in_progress' ? 'yellow' : 'blue')); 
                                            ?>-800">
                                                <?php echo ucfirst(str_replace('_', ' ', $appointment['status'])); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <?php if (isset($userRole) && $userRole === ROLE_ADMIN): ?>
                                                <div class="flex justify-end flex-wrap gap-2">
                                                    <?php if ($appointment['status'] === 'scheduled'): ?>
                                                        <button type="button" onclick="updateAppointmentStatus(<?php echo $appointment['id']; ?>, 'confirmed')"
                                                                class="px-3 py-1 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                                            Approve
                                                        </button>
                                                        <button type="button" onclick="cancelAppointment(<?php echo $appointment['id']; ?>)"
                                                                class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                                            Cancel
                                                        </button>
                                                    <?php elseif ($appointment['status'] === 'confirmed'): ?>
                                                        <button type="button" class="px-3 py-1 bg-gray-300 text-gray-700 rounded-lg cursor-default" disabled>
                                                            Approved
                                                        </button>
                                                    <?php elseif ($appointment['status'] === 'cancelled' || $appointment['status'] === 'completed' || $appointment['status'] === 'no_show'): ?>
                                                        <span class="text-gray-500">No actions available</span>
                                                    <?php else: ?>
                                                        <button type="button" onclick="updateAppointmentStatus(<?php echo $appointment['id']; ?>, 'confirmed')"
                                                                class="px-3 py-1 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                                            Approve
                                                        </button>
                                                        <button type="button" onclick="cancelAppointment(<?php echo $appointment['id']; ?>)"
                                                                class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                                            Cancel
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-gray-500">No actions available</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <div class="bg-gray-50 px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <?php if ($currentPage > 1): ?>
                                <a href="?page=<?php echo $currentPage - 1; ?><?php echo $filterDate ? '&date=' . $filterDate : ''; ?><?php echo $filterStatus ? '&status=' . $filterStatus : ''; ?>" 
                                   class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Previous
                                </a>
                            <?php endif; ?>
                            <?php if ($currentPage < $totalPages): ?>
                                <a href="?page=<?php echo $currentPage + 1; ?><?php echo $filterDate ? '&date=' . $filterDate : ''; ?><?php echo $filterStatus ? '&status=' . $filterStatus : ''; ?>" 
                                   class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Next
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing
                                    <span class="font-medium"><?php echo (($currentPage - 1) * 10) + 1; ?></span>
                                    to
                                    <span class="font-medium"><?php echo min($currentPage * 10, $totalAppointments); ?></span>
                                    of
                                    <span class="font-medium"><?php echo $totalAppointments; ?></span>
                                    results
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                    <?php if ($currentPage > 1): ?>
                                        <a href="?page=<?php echo $currentPage - 1; ?><?php echo $filterDate ? '&date=' . $filterDate : ''; ?><?php echo $filterStatus ? '&status=' . $filterStatus : ''; ?>" 
                                           class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                                        <a href="?page=<?php echo $i; ?><?php echo $filterDate ? '&date=' . $filterDate : ''; ?><?php echo $filterStatus ? '&status=' . $filterStatus : ''; ?>" 
                                           class="relative inline-flex items-center px-4 py-2 border text-sm font-medium <?php echo $i == $currentPage ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    <?php endfor; ?>
                                    
                                    <?php if ($currentPage < $totalPages): ?>
                                        <a href="?page=<?php echo $currentPage + 1; ?><?php echo $filterDate ? '&date=' . $filterDate : ''; ?><?php echo $filterStatus ? '&status=' . $filterStatus : ''; ?>" 
                                           class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    <?php endif; ?>
                                </nav>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        function updateAppointmentStatus(appointmentId, status) {
            if (status === 'confirmed') {
                if (!confirm('This appointment will be approved and scheduled. Continue?')) {
                    return;
                }
            } else if (status === 'cancelled') {
                if (!confirm('This appointment will be cancelled. Continue?')) {
                    return;
                }
            }
            
            fetch('<?php echo BASE_URL; ?>appointments/updateStatus', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'appointment_id=' + appointmentId + '&status=' + status
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (status === 'confirmed') {
                        alert('Appointment has been approved successfully!');
                    } else if (status === 'cancelled') {
                        alert('Appointment has been cancelled successfully!');
                    }
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating appointment status');
            });
        }

        function cancelAppointment(appointmentId) {
            if (confirm('Are you sure you want to cancel this appointment?')) {
                updateAppointmentStatus(appointmentId, 'cancelled');
            }
        }
    </script>
</body>
</html>
