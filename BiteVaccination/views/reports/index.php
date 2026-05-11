<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Animal Bite Center Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .report-card {
            transition: all 0.3s ease;
        }
        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
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
                        <a href="<?php echo BASE_URL; ?>vaccinations" class="flex items-center space-x-3 text-gray-700 hover:bg-gray-100 p-3 rounded-lg">
                            <i class="fas fa-syringe"></i>
                            <span>Vaccinations</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>reports" class="flex items-center space-x-3 text-blue-600 bg-blue-50 p-3 rounded-lg">
                            <i class="fas fa-chart-line"></i>
                            <span>Reports</span>
                        </a>
                    </li>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                    <li>
                        <a href="<?php echo BASE_URL; ?>users" class="flex items-center space-x-3 text-gray-700 hover:bg-gray-100 p-3 rounded-lg">
                            <i class="fas fa-user-cog"></i>
                            <span>User Management</span>
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
                <h1 class="text-3xl font-bold text-gray-900">Reports & Analytics</h1>
                <p class="text-gray-600">Generate comprehensive reports and export data</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Total Patients</p>
                            <p class="text-2xl font-bold text-gray-900">1,250</p>
                            <p class="text-green-600 text-sm mt-1">
                                <i class="fas fa-arrow-up"></i> 12% from last month
                            </p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Total Vaccinations</p>
                            <p class="text-2xl font-bold text-gray-900">3,750</p>
                            <p class="text-green-600 text-sm mt-1">
                                <i class="fas fa-arrow-up"></i> 15% from last month
                            </p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-syringe text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Completed Treatments</p>
                            <p class="text-2xl font-bold text-gray-900">856</p>
                            <p class="text-green-600 text-sm mt-1">
                                <i class="fas fa-arrow-up"></i> 8% from last month
                            </p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-full">
                            <i class="fas fa-check-circle text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-orange-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Active Treatments</p>
                            <p class="text-2xl font-bold text-gray-900">124</p>
                            <p class="text-orange-600 text-sm mt-1">
                                <i class="fas fa-arrow-down"></i> 3% from last month
                            </p>
                        </div>
                        <div class="bg-orange-100 p-3 rounded-full">
                            <i class="fas fa-procedures text-orange-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Report Generation Form -->
            <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                <h3 class="text-xl font-semibold mb-6 text-gray-900">
                    <i class="fas fa-file-alt mr-2 text-blue-600"></i>Generate Custom Report
                </h3>
                
                <form method="POST" action="<?php echo BASE_URL; ?>reports/generate" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="report_type" class="block text-sm font-medium text-gray-700 mb-2">
                                Report Type <span class="text-red-500">*</span>
                            </label>
                            <select id="report_type" name="report_type" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Report Type</option>
                                <option value="daily">Daily Report</option>
                                <option value="weekly">Weekly Report</option>
                                <option value="monthly">Monthly Report</option>
                                <option value="annual">Annual Report</option>
                                <option value="vaccination">Vaccination Report</option>
                                <option value="patient">Patient Report</option>
                                <option value="appointment">Appointment Report</option>
                            </select>
                        </div>

                        <div>
                            <label for="format" class="block text-sm font-medium text-gray-700 mb-2">
                                Export Format
                            </label>
                            <select id="format" name="format"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="web">Web View</option>
                                <option value="pdf">PDF Document</option>
                                <option value="csv">CSV File</option>
                                <option value="excel">Excel File</option>
                            </select>
                        </div>

                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">
                                From Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="date_from" name="date_from" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">
                                To Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="date_to" name="date_to" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="setDateRange('today')" 
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                            <i class="fas fa-calendar-day mr-2"></i>Today
                        </button>
                        <button type="button" onclick="setDateRange('week')" 
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                            <i class="fas fa-calendar-week mr-2"></i>This Week
                        </button>
                        <button type="button" onclick="setDateRange('month')" 
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                            <i class="fas fa-calendar-alt mr-2"></i>This Month
                        </button>
                        <button type="button" onclick="setDateRange('year')" 
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                            <i class="fas fa-calendar mr-2"></i>This Year
                        </button>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-file-export mr-2"></i>Generate Report
                        </button>
                    </div>
                </form>
            </div>

            <!-- Quick Report Links -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="report-card bg-white p-6 rounded-xl shadow-lg cursor-pointer" onclick="generateQuickReport('daily')">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <i class="fas fa-calendar-day text-blue-600 text-xl"></i>
                        </div>
                        <i class="fas fa-arrow-right text-gray-400"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Daily Report</h4>
                    <p class="text-gray-600 text-sm mb-4">Generate today's comprehensive report including patients, appointments, and vaccinations.</p>
                    <div class="flex items-center text-blue-600 text-sm font-medium">
                        <span>Generate Now</span>
                        <i class="fas fa-chevron-right ml-2"></i>
                    </div>
                </div>

                <div class="report-card bg-white p-6 rounded-xl shadow-lg cursor-pointer" onclick="generateQuickReport('vaccination')">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-green-100 p-3 rounded-lg">
                            <i class="fas fa-syringe text-green-600 text-xl"></i>
                        </div>
                        <i class="fas fa-arrow-right text-gray-400"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Vaccination Report</h4>
                    <p class="text-gray-600 text-sm mb-4">Detailed vaccination statistics, adverse reactions, and inventory status.</p>
                    <div class="flex items-center text-blue-600 text-sm font-medium">
                        <span>Generate Now</span>
                        <i class="fas fa-chevron-right ml-2"></i>
                    </div>
                </div>

                <div class="report-card bg-white p-6 rounded-xl shadow-lg cursor-pointer" onclick="generateQuickReport('patient')">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <i class="fas fa-users text-purple-600 text-xl"></i>
                        </div>
                        <i class="fas fa-arrow-right text-gray-400"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Patient Report</h4>
                    <p class="text-gray-600 text-sm mb-4">Patient demographics, age distribution, and registration statistics.</p>
                    <div class="flex items-center text-blue-600 text-sm font-medium">
                        <span>Generate Now</span>
                        <i class="fas fa-chevron-right ml-2"></i>
                    </div>
                </div>

                <div class="report-card bg-white p-6 rounded-xl shadow-lg cursor-pointer" onclick="generateQuickReport('appointment')">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-orange-100 p-3 rounded-lg">
                            <i class="fas fa-calendar-check text-orange-600 text-xl"></i>
                        </div>
                        <i class="fas fa-arrow-right text-gray-400"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Appointment Report</h4>
                    <p class="text-gray-600 text-sm mb-4">Appointment statistics, status distribution, and staff performance.</p>
                    <div class="flex items-center text-blue-600 text-sm font-medium">
                        <span>Generate Now</span>
                        <i class="fas fa-chevron-right ml-2"></i>
                    </div>
                </div>

                <div class="report-card bg-white p-6 rounded-xl shadow-lg cursor-pointer" onclick="generateQuickReport('monthly')">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-red-100 p-3 rounded-lg">
                            <i class="fas fa-calendar-alt text-red-600 text-xl"></i>
                        </div>
                        <i class="fas fa-arrow-right text-gray-400"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Monthly Report</h4>
                    <p class="text-gray-600 text-sm mb-4">Comprehensive monthly summary with trends and analytics.</p>
                    <div class="flex items-center text-blue-600 text-sm font-medium">
                        <span>Generate Now</span>
                        <i class="fas fa-chevron-right ml-2"></i>
                    </div>
                </div>

                <div class="report-card bg-white p-6 rounded-xl shadow-lg cursor-pointer" onclick="generateQuickReport('annual')">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-yellow-100 p-3 rounded-lg">
                            <i class="fas fa-chart-line text-yellow-600 text-xl"></i>
                        </div>
                        <i class="fas fa-arrow-right text-gray-400"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Annual Report</h4>
                    <p class="text-gray-600 text-sm mb-4">Year-end comprehensive report with annual statistics.</p>
                    <div class="flex items-center text-blue-600 text-sm font-medium">
                        <span>Generate Now</span>
                        <i class="fas fa-chevron-right ml-2"></i>
                    </div>
                </div>
            </div>

            <!-- Recent Reports -->
            <div class="bg-white rounded-xl shadow-lg p-6 mt-8">
                <h3 class="text-xl font-semibold mb-6 text-gray-900">
                    <i class="fas fa-history mr-2 text-blue-600"></i>Recent Generated Reports
                </h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Report Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date Range</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Generated By</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Generated On</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Vaccination Report</span>
                                </td>
                                <td class="px-4 py-3 text-sm">Nov 1, 2024 - Nov 30, 2024</td>
                                <td class="px-4 py-3 text-sm"><?php echo $_SESSION['full_name']; ?></td>
                                <td class="px-4 py-3 text-sm">Nov 30, 2024 2:30 PM</td>
                                <td class="px-4 py-3 text-sm">
                                    <div class="flex space-x-2">
                                        <button class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="text-green-600 hover:text-green-800">
                                            <i class="fas fa-download"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Daily Report</span>
                                </td>
                                <td class="px-4 py-3 text-sm">Nov 29, 2024</td>
                                <td class="px-4 py-3 text-sm"><?php echo $_SESSION['full_name']; ?></td>
                                <td class="px-4 py-3 text-sm">Nov 29, 2024 5:15 PM</td>
                                <td class="px-4 py-3 text-sm">
                                    <div class="flex space-x-2">
                                        <button class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="text-green-600 hover:text-green-800">
                                            <i class="fas fa-download"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">Patient Report</span>
                                </td>
                                <td class="px-4 py-3 text-sm">Nov 1, 2024 - Nov 30, 2024</td>
                                <td class="px-4 py-3 text-sm"><?php echo $_SESSION['full_name']; ?></td>
                                <td class="px-4 py-3 text-sm">Nov 28, 2024 10:30 AM</td>
                                <td class="px-4 py-3 text-sm">
                                    <div class="flex space-x-2">
                                        <button class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="text-green-600 hover:text-green-800">
                                            <i class="fas fa-download"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        function setDateRange(range) {
            const today = new Date();
            const fromDate = document.getElementById('date_from');
            const toDate = document.getElementById('date_to');
            
            switch(range) {
                case 'today':
                    fromDate.value = today.toISOString().split('T')[0];
                    toDate.value = today.toISOString().split('T')[0];
                    break;
                case 'week':
                    const weekStart = new Date(today);
                    weekStart.setDate(today.getDate() - today.getDay());
                    fromDate.value = weekStart.toISOString().split('T')[0];
                    toDate.value = today.toISOString().split('T')[0];
                    break;
                case 'month':
                    const monthStart = new Date(today.getFullYear(), today.getMonth(), 1);
                    fromDate.value = monthStart.toISOString().split('T')[0];
                    toDate.value = today.toISOString().split('T')[0];
                    break;
                case 'year':
                    const yearStart = new Date(today.getFullYear(), 0, 1);
                    fromDate.value = yearStart.toISOString().split('T')[0];
                    toDate.value = today.toISOString().split('T')[0];
                    break;
            }
        }

        function generateQuickReport(type) {
            const today = new Date();
            const fromDate = document.getElementById('date_from');
            const toDate = document.getElementById('date_to');
            const reportType = document.getElementById('report_type');
            const format = document.getElementById('format');
            
            reportType.value = type;
            
            switch(type) {
                case 'daily':
                    fromDate.value = today.toISOString().split('T')[0];
                    toDate.value = today.toISOString().split('T')[0];
                    break;
                case 'vaccination':
                case 'patient':
                case 'appointment':
                    const monthStart = new Date(today.getFullYear(), today.getMonth(), 1);
                    fromDate.value = monthStart.toISOString().split('T')[0];
                    toDate.value = today.toISOString().split('T')[0];
                    break;
                case 'monthly':
                    const thisMonthStart = new Date(today.getFullYear(), today.getMonth(), 1);
                    fromDate.value = thisMonthStart.toISOString().split('T')[0];
                    toDate.value = today.toISOString().split('T')[0];
                    break;
                case 'annual':
                    const yearStart = new Date(today.getFullYear(), 0, 1);
                    fromDate.value = yearStart.toISOString().split('T')[0];
                    toDate.value = today.toISOString().split('T')[0];
                    break;
            }
            
            format.value = 'pdf';
            
            // Submit the form
            document.querySelector('form').submit();
        }

        // Set default date range to current month
        setDateRange('month');
    </script>
</body>
</html>
