@extends('Admin.layouts.app')

@section('title', 'User Reports')
@section('page-title', 'User Reports')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">User Reports</h1>
        <p class="text-gray-600 dark:text-gray-400">Manage issues and reports submitted by users</p>
    </div>
    
    <!-- Action Buttons -->
    <div class="flex items-center justify-end gap-3 mb-6">
        <button class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition flex items-center gap-2">
            <i class="fas fa-download"></i>Export
        </button>
        <button class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition flex items-center gap-2">
            <i class="fas fa-filter"></i>Filter
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Reports</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">156</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Pending</p>
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">45</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">In Progress</p>
                    <p class="text-2xl font-bold text-orange-600 dark:text-orange-400 mt-1">23</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-spinner text-orange-600 dark:text-orange-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Resolved</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">88</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Search</label>
                <input type="text" placeholder="Report ID, User name..." 
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Category</label>
                <select class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    <option>All Categories</option>
                    <option>Booking Issue</option>
                    <option>Payment Problem</option>
                    <option>Service Complaint</option>
                    <option>Technical Issue</option>
                    <option>Other</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status</label>
                <select class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    <option>All Status</option>
                    <option>Pending</option>
                    <option>In Progress</option>
                    <option>Resolved</option>
                    <option>Closed</option>
                </select>
            </div>
            <div class="flex items-end">
                <button class="w-full px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 transition">
                    Apply Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Reports List -->
    <div class="space-y-4">
        <!-- Report Card 1 - Pending -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded-full text-xs font-semibold">Pending</span>
                        <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-full text-xs font-semibold">Booking Issue</span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">#RPT-2024-001</span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">•</span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">2 hours ago</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Booking Cancellation Request</h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">
                        "I need to cancel my booking #BK-2024-001 due to an emergency. I haven't received a refund confirmation yet."
                    </p>
                    <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                        <span><i class="fas fa-user mr-1"></i>أحمد محمد</span>
                        <span><i class="fas fa-envelope mr-1"></i>ahmed@example.com</span>
                        <span><i class="fas fa-phone mr-1"></i>+966 50 123 4567</span>
                    </div>
                </div>
                <div class="flex flex-col gap-2 ml-4">
                    <button class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition" title="View Details">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="p-2 text-orange-600 dark:text-orange-400 hover:bg-orange-50 dark:hover:bg-orange-900/30 rounded-lg transition" title="Start Processing">
                        <i class="fas fa-play"></i>
                    </button>
                    <button class="p-2 text-green-600 dark:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/30 rounded-lg transition" title="Resolve">
                        <i class="fas fa-check"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Report Card 2 - In Progress -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-l-4 border-orange-500">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="px-3 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 rounded-full text-xs font-semibold">In Progress</span>
                        <span class="px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-full text-xs font-semibold">Payment Problem</span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">#RPT-2024-002</span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">•</span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">1 day ago</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Double Charge on Credit Card</h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">
                        "I was charged twice for booking #BK-2024-002. The amount was deducted from my card twice. Please investigate and refund the duplicate charge."
                    </p>
                    <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400 mb-3">
                        <span><i class="fas fa-user mr-1"></i>سارة علي</span>
                        <span><i class="fas fa-envelope mr-1"></i>sara@example.com</span>
                        <span><i class="fas fa-credit-card mr-1"></i>Transaction: #TXN-2024-002</span>
                    </div>
                    <div class="p-3 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
                        <p class="text-xs text-orange-700 dark:text-orange-400">
                            <i class="fas fa-user-tie mr-1"></i>Assigned to: Admin User • Started: 1 day ago
                        </p>
                    </div>
                </div>
                <div class="flex flex-col gap-2 ml-4">
                    <button class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition" title="View Details">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="p-2 text-green-600 dark:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/30 rounded-lg transition" title="Resolve">
                        <i class="fas fa-check"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Report Card 3 - Resolved -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full text-xs font-semibold">Resolved</span>
                        <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 rounded-full text-xs font-semibold">Service Complaint</span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">#RPT-2024-003</span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">•</span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">3 days ago</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Room Service Issue</h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">
                        "Room service was very slow and the food was cold when it arrived. This was disappointing for a 5-star hotel."
                    </p>
                    <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400 mb-3">
                        <span><i class="fas fa-user mr-1"></i>خالد أحمد</span>
                        <span><i class="fas fa-envelope mr-1"></i>khalid@example.com</span>
                        <span><i class="fas fa-hotel mr-1"></i>Booking: #BK-2024-003</span>
                    </div>
                    <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <p class="text-xs text-green-700 dark:text-green-400 mb-1">
                            <i class="fas fa-check-circle mr-1"></i>Resolved by: Admin User • 2 days ago
                        </p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">
                            Resolution: Apologized to customer and provided complimentary meal voucher. Customer satisfied.
                        </p>
                    </div>
                </div>
                <div class="flex flex-col gap-2 ml-4">
                    <button class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition" title="View Details">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="p-2 text-gray-400 dark:text-gray-500 rounded-lg transition cursor-not-allowed" title="Already Resolved" disabled>
                        <i class="fas fa-check"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between mt-6">
        <div class="text-sm text-gray-600 dark:text-gray-400">
            Showing <span class="font-semibold">1</span> to <span class="font-semibold">10</span> of <span class="font-semibold">156</span> results
        </div>
        <div class="flex gap-2">
            <button class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition disabled:opacity-50" disabled>
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="px-4 py-2 bg-orange-600 text-white rounded-lg">1</button>
            <button class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">2</button>
            <button class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">3</button>
            <button class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</div>
@endsection

