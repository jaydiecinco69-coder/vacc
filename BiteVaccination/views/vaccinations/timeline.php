<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vaccination Timeline - Animal Bite Center Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .timeline-item {
            transition: all 0.3s ease;
        }
        .timeline-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .progress-line {
            background: linear-gradient(to right, #10b981 0%, #10b981 var(--progress), #e5e7eb var(--progress), #e5e7eb 100%);
        }
        .timeline-dot {
            transition: all 0.3s ease;
        }
        .timeline-dot:hover {
            transform: scale(1.2);
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
                <div class="flex items-center">
                    <a href="<?php echo BASE_URL; ?>vaccinations" class="text-blue-600 hover:text-blue-800 mr-3">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Vaccination Timeline</h1>
                        <p class="text-gray-600">Track patient vaccination progress</p>
                    </div>
                </div>
            </div>

            <!-- Patient Info Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-4 rounded-full mr-4">
                            <i class="fas fa-user text-blue-600 text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">
                                <?php echo $patient['first_name'] . ' ' . $patient['last_name']; ?>
                            </h2>
                            <p class="text-gray-600">Patient ID: <?php echo $patient['patient_id']; ?></p>
                            <div class="mt-2 text-sm text-gray-600">
                                <i class="fas fa-phone mr-2"></i><?php echo $patient['phone']; ?>
                                <span class="mx-2">•</span>
                                <i class="fas fa-envelope mr-2"></i><?php echo $patient['email'] ?: 'N/A'; ?>
                            </div>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <a href="<?php echo BASE_URL; ?>patients/view?id=<?php echo $patient['id']; ?>" 
                           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                            <i class="fas fa-user mr-2"></i>Patient Profile
                        </a>
                        <a href="<?php echo BASE_URL; ?>vaccinations/generateCard?patient_id=<?php echo $patient['id']; ?>" 
                           class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                            <i class="fas fa-id-card mr-2"></i>Vaccination Card
                        </a>
                    </div>
                </div>
            </div>

            <!-- Treatment Progress Overview -->
            <?php if (!empty($treatmentProgress)): ?>
                <?php foreach ($treatmentProgress as $progress): ?>
                    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold">Treatment Progress</h3>
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
                                <span>Progress: <?php echo $progress['doses_completed']; ?> / <?php echo $progress['total_doses_required']; ?> doses</span>
                                <span><?php echo round(($progress['doses_completed'] / $progress['total_doses_required']) * 100, 1); ?>%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="progress-line h-3 rounded-full" style="--progress: <?php echo ($progress['doses_completed'] / $progress['total_doses_required']) * 100; ?>%"></div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Treatment Started:</span>
                                <div class="font-medium"><?php echo date('M d, Y', strtotime($progress['treatment_start_date'])); ?></div>
                            </div>
                            <?php if ($progress['next_appointment_date']): ?>
                                <div>
                                    <span class="text-gray-500">Next Appointment:</span>
                                    <div class="font-medium"><?php echo date('M d, Y', strtotime($progress['next_appointment_date'])); ?></div>
                                </div>
                            <?php endif; ?>
                            <?php if ($progress['treatment_end_date']): ?>
                                <div>
                                    <span class="text-gray-500">Treatment Ended:</span>
                                    <div class="font-medium"><?php echo date('M d, Y', strtotime($progress['treatment_end_date'])); ?></div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($progress['notes']): ?>
                            <div class="mt-4 p-3 bg-gray-50 rounded text-sm">
                                <strong>Notes:</strong> <?php echo $progress['notes']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <!-- Vaccination Timeline -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold mb-6">Vaccination Schedule</h3>
                
                <?php if (empty($vaccinations)): ?>
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-syringe text-4xl mb-2"></i>
                        <p>No vaccination records found</p>
                    </div>
                <?php else: ?>
                    <div class="relative">
                        <!-- Timeline Line -->
                        <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-gray-300"></div>
                        
                        <div class="space-y-6">
                            <?php foreach ($vaccinations as $index => $vaccination): ?>
                                <div class="timeline-item relative flex items-start">
                                    <!-- Timeline Dot -->
                                    <div class="timeline-dot absolute left-6 w-4 h-4 rounded-full border-4 border-white <?php 
                                        echo $vaccination['status'] == 'administered' ? 'bg-green-500' : 
                                             ($vaccination['status'] == 'missed' ? 'bg-red-500' : 'bg-blue-500'); 
                                    ?>"></div>
                                    
                                    <!-- Content -->
                                    <div class="ml-16 flex-1 bg-gray-50 p-4 rounded-lg border-l-4 border-<?php 
                                        echo $vaccination['status'] == 'administered' ? 'green' : 
                                             ($vaccination['status'] == 'missed' ? 'red' : 'blue'); 
                                    ?>-500">
                                        <div class="flex items-center justify-between mb-2">
                                            <div>
                                                <h4 class="font-semibold">
                                                    <?php echo ucfirst($vaccination['vaccine_type']); ?> - Dose <?php echo $vaccination['dose_number']; ?>
                                                </h4>
                                                <p class="text-sm text-gray-600">
                                                    <?php if ($vaccination['status'] == 'administered'): ?>
                                                        Administered on <?php echo date('M d, Y', strtotime($vaccination['administration_date'])); ?> at <?php echo date('H:i', strtotime($vaccination['administration_time'])); ?>
                                                    <?php else: ?>
                                                        Scheduled for <?php echo date('M d, Y', strtotime($vaccination['administration_date'])); ?> at <?php echo date('H:i', strtotime($vaccination['administration_time'])); ?>
                                                    <?php endif; ?>
                                                </p>
                                            </div>
                                            <span class="px-2 py-1 text-xs rounded-full bg-<?php 
                                                echo $vaccination['status'] == 'administered' ? 'green' : 
                                                     ($vaccination['status'] == 'missed' ? 'red' : 'blue'); 
                                            ?>-100 text-<?php 
                                                echo $vaccination['status'] == 'administered' ? 'green' : 
                                                     ($vaccination['status'] == 'missed' ? 'red' : 'blue'); 
                                            ?>-800">
                                                <?php echo ucfirst($vaccination['status']); ?>
                                            </span>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                            <div>
                                                <span class="text-gray-500">Vaccine Brand:</span>
                                                <div class="font-medium"><?php echo $vaccination['vaccine_brand'] ?: 'Standard'; ?></div>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">Administration Site:</span>
                                                <div class="font-medium"><?php echo ucfirst(str_replace('_', ' ', $vaccination['administration_site'])); ?></div>
                                            </div>
                                            <?php if ($vaccination['administered_by_name']): ?>
                                                <div>
                                                    <span class="text-gray-500">Administered By:</span>
                                                    <div class="font-medium"><?php echo $vaccination['administered_by_name']; ?></div>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($vaccination['batch_number']): ?>
                                                <div>
                                                    <span class="text-gray-500">Batch Number:</span>
                                                    <div class="font-medium"><?php echo $vaccination['batch_number']; ?></div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <?php if ($vaccination['adverse_reactions']): ?>
                                            <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded text-sm">
                                                <strong class="text-red-800">Adverse Reaction:</strong> <?php echo $vaccination['adverse_reactions']; ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($vaccination['next_dose_date']): ?>
                                            <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded text-sm">
                                                <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                                                <strong>Next Dose:</strong> <?php echo date('M d, Y', strtotime($vaccination['next_dose_date'])); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="mt-3 flex space-x-2">
                                            <?php if ($vaccination['status'] === 'scheduled'): ?>
                                                <button onclick="markAsAdministered(<?php echo $vaccination['id']; ?>)" 
                                                        class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                                                    <i class="fas fa-check mr-1"></i>Mark as Administered
                                                </button>
                                            <?php endif; ?>
                                            <button onclick="printVaccinationRecord(<?php echo $vaccination['id']; ?>)" 
                                                    class="bg-gray-600 text-white px-3 py-1 rounded text-sm hover:bg-gray-700">
                                                <i class="fas fa-print mr-1"></i>Print
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Upcoming Vaccinations Alert -->
            <?php if (!empty($upcomingVaccinations)): ?>
                <div class="mt-6 bg-yellow-50 border border-yellow-200 p-6 rounded-xl">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-bell text-yellow-600 text-xl mr-3"></i>
                        <h3 class="text-lg font-semibold text-yellow-800">Upcoming Vaccinations</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach (array_slice($upcomingVaccinations, 0, 4) as $vaccination): ?>
                            <div class="bg-white p-4 rounded-lg border border-yellow-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-medium"><?php echo $vaccination['first_name'] . ' ' . $vaccination['last_name']; ?></div>
                                        <div class="text-sm text-gray-600">
                                            <?php echo ucfirst($vaccination['vaccine_type']); ?> - Dose <?php echo $vaccination['dose_number']; ?>
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            <?php echo date('M d, Y', strtotime($vaccination['administration_date'])); ?>
                                        </div>
                                    </div>
                                    <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">
                                        <?php 
                                            $daysUntil = (strtotime($vaccination['administration_date']) - strtotime(date('Y-m-d'))) / 86400;
                                            echo $daysUntil <= 0 ? 'Overdue' : $daysUntil . ' days';
                                        ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <script>
        function markAsAdministered(vaccinationId) {
            if (confirm('Are you sure you want to mark this vaccination as administered?')) {
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

        function printVaccinationRecord(vaccinationId) {
            window.print();
        }

        // Add smooth scroll behavior
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
