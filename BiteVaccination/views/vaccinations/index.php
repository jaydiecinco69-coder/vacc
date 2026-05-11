<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vaccinations - Animal Bite Center Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .vaccination-row {
            transition: all 0.3s ease;
        }
        .vaccination-row:hover {
            background-color: #f3f4f6;
            transform: translateX(5px);
        }
        .dose-badge {
            transition: all 0.3s ease;
        }
        .dose-badge:hover {
            transform: scale(1.1);
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
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Vaccination Management</h1>
                        <p class="text-gray-600">Track and manage patient vaccinations</p>
                    </div>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === ROLE_ADMIN): ?>
                        <a href="<?php echo BASE_URL; ?>vaccinations/create" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i>Schedule Vaccination
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Type</label>
                        <select name="type" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Types</option>
                            <option value="anti_rabies" <?php echo $filterType == 'anti_rabies' ? 'selected' : ''; ?>>Anti-Rabies</option>
                            <option value="tetanus" <?php echo $filterType == 'tetanus' ? 'selected' : ''; ?>>Tetanus</option>
                            <option value="immunoglobulin" <?php echo $filterType == 'immunoglobulin' ? 'selected' : ''; ?>>Immunoglobulin</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
                        <select name="status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Status</option>
                            <option value="scheduled" <?php echo $filterStatus == 'scheduled' ? 'selected' : ''; ?>>Scheduled</option>
                            <option value="administered" <?php echo $filterStatus == 'administered' ? 'selected' : ''; ?>>Administered</option>
                            <option value="missed" <?php echo $filterStatus == 'missed' ? 'selected' : ''; ?>>Missed</option>
                            <option value="cancelled" <?php echo $filterStatus == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 mr-2">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                        <a href="<?php echo BASE_URL; ?>vaccinations" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                            <i class="fas fa-times mr-2"></i>Clear
                        </a>
                    </div>
                </form>
            </div>

            
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Total Vaccinations</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo number_format(isset($totalVaccinations) ? $totalVaccinations : 0); ?></p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-syringe text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Administered</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo number_format(isset($administeredVaccinations) ? $administeredVaccinations : 0); ?></p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Scheduled</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo number_format(isset($scheduledVaccinations) ? $scheduledVaccinations : 0); ?></p>
                        </div>
                        <div class="bg-yellow-100 p-3 rounded-full">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-red-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Missed</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo number_format(isset($missedVaccinations) ? $missedVaccinations : 0); ?></p>
                        </div>
                        <div class="bg-red-100 p-3 rounded-full">
                            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vaccinations Table -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vaccine</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dose</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Administered By</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (empty($vaccinations)): ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-syringe text-4xl mb-2"></i>
                                        <p>No vaccination records found</p>
                                        <?php if (isset($filterDate) || isset($filterType) || isset($filterStatus)): ?>
                                            <p class="text-sm">Try adjusting your filters</p>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($vaccinations as $vaccination): ?>
                                    <tr class="vaccination-row">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                        <i class="fas fa-user text-blue-600"></i>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?php echo $vaccination['first_name'] . ' ' . $vaccination['last_name']; ?>
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        <?php echo $vaccination['patient_id']; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo ucfirst($vaccination['vaccine_type']); ?>
                                                </div>
                                                <?php if ($vaccination['vaccine_brand']): ?>
                                                    <div class="text-sm text-gray-500">
                                                        <?php echo $vaccination['vaccine_brand']; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="dose-badge px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                                Dose <?php echo $vaccination['dose_number']; ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <?php echo date('M d, Y', strtotime($vaccination['administration_date'])); ?>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <?php echo date('H:i', strtotime($vaccination['administration_time'])); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo $vaccination['administered_by_name'] ?: 'Not administered'; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-<?php 
                                                echo $vaccination['status'] == 'administered' ? 'green' : 
                                                     ($vaccination['status'] == 'missed' ? 'red' : 'blue'); 
                                            ?>-100 text-<?php 
                                                echo $vaccination['status'] == 'administered' ? 'green' : 
                                                     ($vaccination['status'] == 'missed' ? 'red' : 'blue'); 
                                            ?>-800">
                                                <?php echo ucfirst($vaccination['status']); ?>
                                            </span>
                                            <?php if ($vaccination['adverse_reactions']): ?>
                                                <div class="text-xs text-orange-600 mt-1">
                                                    <i class="fas fa-exclamation-triangle"></i> Reaction
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                <!-- Admin controls for vaccination status -->
                                                <?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'staff')): ?>
                                                    <?php if ($vaccination['status'] === 'scheduled'): ?>
                                                        <button onclick="markAsAdministered(<?php echo $vaccination['id']; ?>)" 
                                                                class="bg-green-600 text-white px-2 py-1 rounded text-xs hover:bg-green-700" title="Mark as Completed">
                                                            <i class="fas fa-check mr-1"></i>Completed
                                                        </button>
                                                        <button onclick="markAsMissed(<?php echo $vaccination['id']; ?>)" 
                                                                class="bg-red-600 text-white px-2 py-1 rounded text-xs hover:bg-red-700" title="Mark as Missed">
                                                            <i class="fas fa-times mr-1"></i>Missed
                                                        </button>
                                                    <?php elseif ($vaccination['status'] === 'missed'): ?>
                                                        <button onclick="markAsAdministered(<?php echo $vaccination['id']; ?>)" 
                                                                class="bg-green-600 text-white px-2 py-1 rounded text-xs hover:bg-green-700" title="Mark as Completed">
                                                            <i class="fas fa-check mr-1"></i>Completed
                                                        </button>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <!-- Original admin button for non-admin users -->
                                                    <?php if ($vaccination['status'] === 'scheduled'): ?>
                                                        <button onclick="administerVaccination(<?php echo $vaccination['id']; ?>)" 
                                                                class="text-green-600 hover:text-green-900" title="Administer">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
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
                                <a href="?page=<?php echo $currentPage - 1; ?><?php echo $filterDate ? '&date=' . $filterDate : ''; ?><?php echo $filterType ? '&type=' . $filterType : ''; ?><?php echo $filterStatus ? '&status=' . $filterStatus : ''; ?>" 
                                   class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Previous
                                </a>
                            <?php endif; ?>
                            <?php if ($currentPage < $totalPages): ?>
                                <a href="?page=<?php echo $currentPage + 1; ?><?php echo $filterDate ? '&date=' . $filterDate : ''; ?><?php echo $filterType ? '&type=' . $filterType : ''; ?><?php echo $filterStatus ? '&status=' . $filterStatus : ''; ?>" 
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
                                    <span class="font-medium"><?php echo min($currentPage * 10, $totalVaccinations); ?></span>
                                    of
                                    <span class="font-medium"><?php echo $totalVaccinations; ?></span>
                                    results
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                    <?php if ($currentPage > 1): ?>
                                        <a href="?page=<?php echo $currentPage - 1; ?><?php echo $filterDate ? '&date=' . $filterDate : ''; ?><?php echo $filterType ? '&type=' . $filterType : ''; ?><?php echo $filterStatus ? '&status=' . $filterStatus : ''; ?>" 
                                           class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                                        <a href="?page=<?php echo $i; ?><?php echo $filterDate ? '&date=' . $filterDate : ''; ?><?php echo $filterType ? '&type=' . $filterType : ''; ?><?php echo $filterStatus ? '&status=' . $filterStatus : ''; ?>" 
                                           class="relative inline-flex items-center px-4 py-2 border text-sm font-medium <?php echo $i == $currentPage ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    <?php endfor; ?>
                                    
                                    <?php if ($currentPage < $totalPages): ?>
                                        <a href="?page=<?php echo $currentPage + 1; ?><?php echo $filterDate ? '&date=' . $filterDate : ''; ?><?php echo $filterType ? '&type=' . $filterType : ''; ?><?php echo $filterStatus ? '&status=' . $filterStatus : ''; ?>" 
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
        
        function administerVaccination(vaccinationId) {
            if (confirm('Are you sure you want to administer this vaccination?')) {
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
                        alert('Vaccination administered successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while administering vaccination');
                });
            }
        }
    </script>
</body>
</html>
