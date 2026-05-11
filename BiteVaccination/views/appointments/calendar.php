<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Calendar - Animal Bite Center Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .calendar-day {
            min-height: 120px;
            transition: all 0.3s ease;
        }
        .calendar-day:hover {
            background-color: #f9fafb;
            transform: scale(1.02);
        }
        .calendar-day.today {
            background-color: #dbeafe;
            border: 2px solid #3b82f6;
        }
        .calendar-day.has-appointments {
            background-color: #fef3c7;
        }
        .appointment-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin: 1px;
        }
        .month-nav {
            transition: all 0.3s ease;
        }
        .month-nav:hover {
            background-color: #f3f4f6;
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
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Appointment Calendar</h1>
                        <p class="text-gray-600">View and manage appointments by calendar</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="<?php echo BASE_URL; ?>appointments" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                            <i class="fas fa-list mr-2"></i>List View
                        </a>
                        <a href="<?php echo BASE_URL; ?>appointments/create" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i>Schedule Appointment
                        </a>
                    </div>
                </div>
            </div>

            <!-- Calendar Navigation -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <a href="?month=<?php echo $currentMonth == 1 ? 12 : $currentMonth - 1; ?>&year=<?php echo $currentMonth == 1 ? $currentYear - 1 : $currentYear; ?>" 
                       class="month-nav p-3 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    
                    <div class="text-center">
                        <h2 class="text-2xl font-bold text-gray-900">
                            <?php echo date('F Y', mktime(0, 0, 0, $currentMonth, 1, $currentYear)); ?>
                        </h2>
                        <div class="flex justify-center space-x-2 mt-2">
                            <a href="?month=<?php echo date('n'); ?>&year=<?php echo date('Y'); ?>" 
                               class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-lg hover:bg-blue-200">
                                Today
                            </a>
                            <button onclick="goToCurrentMonth()" 
                                    class="px-3 py-1 text-sm bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200">
                                Current Month
                            </button>
                        </div>
                    </div>
                    
                    <a href="?month=<?php echo $currentMonth == 12 ? 1 : $currentMonth + 1; ?>&year=<?php echo $currentMonth == 12 ? $currentYear + 1 : $currentYear; ?>" 
                       class="month-nav p-3 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>

                <!-- Calendar Grid -->
                <div class="grid grid-cols-7 gap-px bg-gray-200">
                    <!-- Weekday Headers -->
                    <div class="bg-gray-50 p-3 text-center font-semibold text-gray-700">Sun</div>
                    <div class="bg-gray-50 p-3 text-center font-semibold text-gray-700">Mon</div>
                    <div class="bg-gray-50 p-3 text-center font-semibold text-gray-700">Tue</div>
                    <div class="bg-gray-50 p-3 text-center font-semibold text-gray-700">Wed</div>
                    <div class="bg-gray-50 p-3 text-center font-semibold text-gray-700">Thu</div>
                    <div class="bg-gray-50 p-3 text-center font-semibold text-gray-700">Fri</div>
                    <div class="bg-gray-50 p-3 text-center font-semibold text-gray-700">Sat</div>
                    
                    <!-- Calendar Days -->
                    <?php foreach ($calendarData as $week): ?>
                        <?php foreach ($week as $day): ?>
                            <?php if ($day['day'] === null): ?>
                                <div class="bg-gray-50 p-2 min-h-[120px]"></div>
                            <?php else: ?>
                                <div class="calendar-day bg-white p-2 <?php echo $day['is_today'] ? 'today' : ''; ?> <?php echo !empty($day['appointments']) ? 'has-appointments' : ''; ?> <?php echo $day['is_weekend'] ? 'bg-gray-50' : ''; ?>"
                                     onclick="showDayAppointments('<?php echo $day['date']; ?>')">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="font-semibold text-sm <?php echo $day['is_today'] ? 'text-blue-600' : ($day['is_weekend'] ? 'text-gray-500' : 'text-gray-900'); ?>">
                                            <?php echo $day['day']; ?>
                                        </span>
                                        <?php if (!empty($day['appointments'])): ?>
                                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                                <?php echo count($day['appointments']); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if (!empty($day['appointments'])): ?>
                                        <div class="space-y-1">
                                            <?php foreach (array_slice($day['appointments'], 0, 3) as $appointment): ?>
                                                <div class="text-xs p-1 rounded bg-<?php 
                                                    echo $appointment['status'] == 'completed' ? 'green' : 
                                                         ($appointment['status'] == 'cancelled' ? 'red' : 'blue'); 
                                                ?>-100 text-<?php 
                                                    echo $appointment['status'] == 'completed' ? 'green' : 
                                                         ($appointment['status'] == 'cancelled' ? 'red' : 'blue'); 
                                                ?>-800 truncate">
                                                    <div class="font-medium"><?php echo date('H:i', strtotime($appointment['appointment_time'])); ?></div>
                                                    <div><?php echo $appointment['first_name']; ?></div>
                                                </div>
                                            <?php endforeach; ?>
                                            
                                            <?php if (count($day['appointments']) > 3): ?>
                                                <div class="text-xs text-gray-500 text-center">
                                                    +<?php echo count($day['appointments']) - 3; ?> more
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>

                <!-- Legend -->
                <div class="mt-6 flex flex-wrap items-center justify-center space-x-6 text-sm">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-blue-100 border border-blue-300 rounded mr-2"></div>
                        <span>Scheduled</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-100 border border-green-300 rounded mr-2"></div>
                        <span>Completed</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-red-100 border border-red-300 rounded mr-2"></div>
                        <span>Cancelled</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-blue-200 border-2 border-blue-500 rounded mr-2"></div>
                        <span>Today</span>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">This Month</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo count($appointments); ?></p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Completed</p>
                            <p class="text-2xl font-bold text-gray-900">
                                <?php echo count(array_filter($appointments, fn($a) => $a['status'] == 'completed')); ?>
                            </p>
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
                            <p class="text-2xl font-bold text-gray-900">
                                <?php echo count(array_filter($appointments, fn($a) => in_array($a['status'], ['scheduled', 'confirmed']))); ?>
                            </p>
                        </div>
                        <div class="bg-yellow-100 p-3 rounded-full">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Available Slots</p>
                            <p class="text-2xl font-bold text-gray-900">45</p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-full">
                            <i class="fas fa-hourglass-half text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Day Appointments Modal -->
    <div id="dayModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Appointments</h3>
                    <button onclick="closeDayModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div id="modalContent" class="max-h-96 overflow-y-auto">
                    <!-- Appointments will be loaded here -->
                </div>
                <div class="mt-4 flex justify-end">
                    <button onclick="closeDayModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showDayAppointments(date) {
            const modal = document.getElementById('dayModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalContent = document.getElementById('modalContent');
            
            modalTitle.textContent = `Appointments for ${new Date(date).toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}`;
            
            // Load appointments for the selected date
            fetch(`<?php echo BASE_URL; ?>api/appointments?date=${date}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        modalContent.innerHTML = `
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-calendar-times text-4xl mb-2"></i>
                                <p>No appointments scheduled for this day</p>
                            </div>
                        `;
                    } else {
                        modalContent.innerHTML = data.map(appointment => `
                            <div class="bg-gray-50 p-4 rounded-lg mb-3">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="font-medium">${appointment.first_name} ${appointment.last_name}</div>
                                        <div class="text-sm text-gray-600">${appointment.patient_id}</div>
                                        <div class="text-sm text-gray-600 mt-1">
                                            <i class="fas fa-clock mr-1"></i>${appointment.appointment_time}
                                            <span class="ml-2">•</span>
                                            <i class="fas fa-tag mr-1"></i>${appointment.appointment_type.replace('_', ' ')}
                                        </div>
                                        ${appointment.staff_name ? `<div class="text-sm text-gray-600 mt-1"><i class="fas fa-user-md mr-1"></i>${appointment.staff_name}</div>` : ''}
                                        ${appointment.notes ? `<div class="text-sm text-gray-600 mt-2"><i class="fas fa-notes-medical mr-1"></i>${appointment.notes}</div>` : ''}
                                    </div>
                                    <div class="text-right">
                                        <span class="px-2 py-1 text-xs rounded-full bg-${getStatusColor(appointment.status)}-100 text-${getStatusColor(appointment.status)}-800">
                                            ${appointment.status.replace('_', ' ')}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        `).join('');
                    }
                })
                .catch(error => {
                    console.error('Error loading appointments:', error);
                    modalContent.innerHTML = `
                        <div class="text-center py-8 text-red-500">
                            <i class="fas fa-exclamation-triangle text-4xl mb-2"></i>
                            <p>Error loading appointments</p>
                        </div>
                    `;
                });
            
            modal.classList.remove('hidden');
        }

        function closeDayModal() {
            document.getElementById('dayModal').classList.add('hidden');
        }

        function getStatusColor(status) {
            switch (status) {
                case 'completed': return 'green';
                case 'cancelled': return 'red';
                case 'in_progress': return 'yellow';
                default: return 'blue';
            }
        }

        function goToCurrentMonth() {
            const today = new Date();
            window.location.href = `?month=${today.getMonth() + 1}&year=${today.getFullYear()}`;
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('dayModal');
            if (event.target == modal) {
                closeDayModal();
            }
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDayModal();
            }
        });
    </script>
</body>
</html>
