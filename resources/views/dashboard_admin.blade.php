<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Gaya kustom untuk scrollbar (hanya di webkit browsers) */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #93c5fd; /* blue-300 */
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background-color: #f1f5f9; /* slate-100 */
        }
    </style>
</head>
<body class="bg-gray-50">

    <div class="h-screen overflow-y-auto custom-scrollbar">

        <nav class="flex items-center justify-between px-6 py-3 bg-white shadow-md sticky top-0 z-10">
            <div class="flex items-center space-x-4">
                <div class="h-8 w-8 bg-blue-800 rounded-full flex items-center justify-center text-white font-bold text-sm">
                    ITP
                </div>
                <span class="font-semibold text-lg text-blue-800">ITP MASAGI</span>
            </div>
            <div class="flex items-center space-x-8">
                <a href="#" class="text-blue-600 font-bold border-b-2 border-blue-600 pb-1">Dashboard</a>
                <a href="#" class="text-gray-600 hover:text-blue-600 flex items-center">Master <i class="fas fa-caret-down ml-1 text-sm"></i></a>
                <a href="#" class="text-gray-600 hover:text-blue-600">Schedule</a>
                <a href="#" class="text-gray-600 hover:text-blue-600">TUK</a>
                <button class="relative text-gray-600 hover:text-blue-600">
                    <i class="fas fa-bell text-xl"></i>
                    <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
                </button>
                <div class="flex items-center space-x-2">
                    <span class="text-gray-800 font-medium text-sm">Roihan Enrico</span>
                    <div class="h-8 w-8 bg-gray-200 rounded-full border-2 border-gray-300 overflow-hidden">
                        <i class="fas fa-user-circle text-2xl text-gray-500"></i>
                    </div>
                </div>
            </div>
        </nav>

        <div class="p-6">

            <header class="mb-6">
                <p class="text-sm text-gray-500 mb-1">Hi, Roihan's</p>
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Dashboard</h2>
                <div class="flex items-start justify-between">
                    <div class="relative w-1/3">
                        <input type="text" placeholder="Search" class="w-full pl-10 pr-4 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <i class="fas fa-search absolute left-3 top-4 text-gray-400"></i>
                    </div>
                    <div class="flex space-x-1 p-1 bg-white border border-gray-200 rounded-xl shadow-sm">
                        <button class="px-4 py-2 text-white bg-blue-600 rounded-xl text-sm font-medium">Today</button>
                        <button class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-xl text-sm">Year</button>
                        <button class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-xl text-sm">Week</button>
                        <button class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-xl text-sm">Month</button>
                    </div>
                </div>
            </header>

            <div class="grid grid-cols-4 gap-6 mb-8">
                
                <div class="bg-white p-6 rounded-xl shadow-lg flex items-center space-x-6 border-b-4 border-blue-600/30">
                    <div class="text-5xl text-blue-600/80">
                        <i class="far fa-calendar-alt"></i> 
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Asesmen yang sedang berlangsung</p>
                        <p class="text-4xl font-extrabold text-gray-900 mt-1">33</p>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-lg flex items-center space-x-6">
                    <div class="text-5xl text-gray-500">
                        <i class="far fa-calendar-check"></i> 
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Asesmen yang selesai</p>
                        <p class="text-4xl font-extrabold text-gray-900 mt-1">3</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg flex items-center space-x-6">
                    <div class="text-5xl text-gray-500">
                        <i class="fas fa-book-reader"></i> 
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Jumlah Asesi</p>
                        <p class="text-4xl font-extrabold text-gray-900 mt-1">34567</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg flex items-center space-x-6">
                    <div class="text-5xl text-gray-500">
                        <i class="fas fa-chalkboard-teacher"></i> 
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Jumlah Asesor</p>
                        <p class="text-4xl font-extrabold text-gray-900 mt-1">90</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-6 mb-8">
                
                <div class="bg-white p-4 rounded-xl shadow-lg col-span-1">
                    <h3 class="text-md font-semibold mb-2">Statistik skema</h3>
                    <div class="h-64 flex items-center justify-center border border-gray-200 rounded-lg overflow-hidden">
                        <img src="https://via.placeholder.com/400x256/f87171/ffffff?text=Statistik+Skema" alt="Line Chart Placeholder" class="object-cover w-full h-full" />
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl shadow-lg col-span-1">
                    <h3 class="text-md font-semibold mb-2">Statistik asesi yang mengikuti skema</h3>
                    <div class="h-64 flex items-center justify-center border border-gray-200 rounded-lg overflow-hidden">
                        <img src="https://via.placeholder.com/400x256/3b82f6/ffffff?text=Total+Revenue+Bar+Chart" alt="Bar Chart Placeholder" class="object-cover w-full h-full" />
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl shadow-lg col-span-1">
                    <h3 class="text-md font-semibold mb-2">Progress Skema</h3>
                    <div class="h-64 flex items-center justify-center border border-gray-200 rounded-lg overflow-hidden">
                        <img src="https://via.placeholder.com/400x256/10b981/ffffff?text=Product+Statistic+Doughnut+Chart" alt="Doughnut Chart Placeholder" class="object-cover w-full h-full" />
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl shadow-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">Course Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">id</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 mr-3 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-microchip text-blue-600"></i>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900">Data Scientist</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">201939</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">3</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 mr-3 bg-red-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-fingerprint text-red-600"></i>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900">Blockchain Architect</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">name</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">id</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Amount</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Status</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="px-6 py-3 text-sm text-gray-500">
                                Showing 1 - 2 of 50
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</body>
</html>