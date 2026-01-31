@extends('Admin.layouts.app')

@section('title', __('Transactions'))
@section('page-title', __('Transactions'))

@section('content')
    <div class="space-y-6">
        <!-- Page Header with Action Buttons -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ __('Transactions') }}</h1>
                <p class="text-gray-600 dark:text-gray-400">{{ __('View and manage all Booking') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <button
                    class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition flex items-center gap-2">
                    <i class="fas fa-download"></i>{{ __('Export') }}
                </button>
                <button id="filterToggleBtn"
                    class="px-4 py-2 {{ request()->hasAny(['search', 'status', 'date']) ? 'bg-orange-500 dark:bg-orange-500 text-white dark:text-white border-orange-500 dark:border-orange-500' : 'bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300' }} rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition flex items-center gap-2">
                    <i class="fas fa-filter"></i>{{ __('Filter') }}
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <!-- Total Revenue Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ __('Total Revenue') }}</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['currency'] ?? __('SAR') }}
                            {{ number_format($stats['total_revenue'], 2) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Successful Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ __('Successful') }}</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">{{ number_format($stats['successful']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Pending Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ __('Pending') }}</p>
                        <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">{{ number_format($stats['pending']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600 dark:text-yellow-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Failed Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ __('Failed') }}</p>
                        <p class="text-2xl font-bold text-red-600 dark:text-red-400 mt-1">{{ number_format($stats['failed']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-times-circle text-red-600 dark:text-red-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div id="filterCard" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6 transition-all duration-300 ease-in-out {{ request()->hasAny(['search', 'status', 'date']) ? '' : 'hidden' }}">
            <form action="{{ route('admin.transactions') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('Search') }}</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="{{ __('Transaction ID, Booking ID...') }}"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('Status') }}</label>
                    <select name="status"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="All Status">{{ __('All Status') }}</option>
                        <option value="PAID" {{ request('status') == 'PAID' ? 'selected' : '' }}>{{ __('Successful') }}</option>
                        <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                        <option value="FAILED" {{ request('status') == 'FAILED' ? 'selected' : '' }}>{{ __('Failed') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('Date Range') }}</label>
                    <input type="date" name="date" value="{{ request('date') }}"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 transition">
                        {{ __('Apply Filters') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="py-4 px-6">{{ __('Transaction ID') }}</th>
                            <th class="py-4 px-6">{{ __('Booking ID') }}</th>
                            <th class="py-4 px-6">{{ __('Customer') }}</th>
                            <th class="py-4 px-6">{{ __('Amount') }}</th>
                            <th class="py-4 px-6">{{ __('Payment Method') }}</th>
                            <th class="py-4 px-6">{{ __('Status') }}</th>
                            <th class="py-4 px-6">{{ __('Date') }}</th>
                            <th class="py-4 px-6">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($transactions as $transaction)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition bg-white dark:bg-gray-800">
                                <td class="py-4 px-6">
                                    <span
                                        class="font-mono font-semibold text-gray-900 dark:text-gray-100">{{ $transaction->payment_reference ?: 'N/A' }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <a href="{{ route('admin.bookings', ['search' => $transaction->booking_reference]) }}"
                                        class="font-mono text-orange-600 dark:text-orange-400 hover:underline">#{{ $transaction->booking_reference }}</a>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        @php
                                            $initial = mb_substr($transaction->guest_name, 0, 1);
                                        @endphp
                                        <div
                                            class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white text-xs font-bold">
                                            {{ $initial }}</div>
                                        <span
                                            class="text-gray-900 dark:text-gray-100">{{ $transaction->guest_name }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="font-bold text-gray-900 dark:text-gray-100">{{ $transaction->currency }}
                                        {{ number_format($transaction->total_price, 2) }}</span>
                                </td>
                                <td class="py-4 px-6 text-gray-700 dark:text-gray-300">
                                    {{ isset($transaction->payment_details['payment_option']) ? $transaction->payment_details['payment_option'] : 'VISA' }}
                                </td>
                                <td class="py-4 px-6">
                                    @if ($transaction->booking_status === 'cancelled')
                                        <div class="flex flex-col">
                                            <span
                                                class="px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-full text-xs font-semibold w-fit">{{ __('Cancelled') }}</span>
                                            @if ($transaction->payment_status === 'paid')
                                                <span class="text-[10px] text-green-500 dark:text-green-400 mt-1 font-bold">{{ __('Paid') }}</span>
                                            @elseif($transaction->payment_status === 'refunded')
                                                <span class="text-[10px] text-blue-500 dark:text-blue-400 mt-1 font-bold">{{ __('Refunded') }}</span>
                                            @elseif($transaction->payment_status === 'pending')
                                                <span class="text-[10px] text-yellow-500 dark:text-yellow-400 mt-1 font-bold">{{ __('Pending Payment') }}</span>
                                            @else
                                                <span class="text-[10px] text-red-500 dark:text-red-400 mt-1 font-bold">{{ __('Not Paid') }}</span>
                                            @endif
                                        </div>
                                    @elseif($transaction->payment_status === 'failed' || $transaction->booking_status === 'failed')
                                        <div class="flex flex-col">
                                            <span
                                                class="px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-full text-xs font-semibold w-fit">{{ __('Failed') }}</span>
                                            @if ($transaction->payment_status === 'paid')
                                                <span class="text-[10px] text-orange-500 dark:text-orange-400 mt-1 font-bold">⚠️ {{ __('PAID BUT BOOKING FAILED') }}</span>
                                            @endif
                                        </div>
                                    @elseif($transaction->payment_status === 'paid' && $transaction->booking_status === 'confirmed')
                                        <span
                                            class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full text-xs font-semibold">{{ __('Successful') }}</span>
                                    @elseif($transaction->payment_status === 'pending' || $transaction->booking_status === 'pending')
                                        <span
                                            class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded-full text-xs font-semibold">{{ __('Pending') }}</span>
                                    @else
                                        <span
                                            class="px-3 py-1 bg-gray-100 dark:bg-gray-900/30 text-gray-700 dark:text-gray-400 rounded-full text-xs font-semibold">{{ ucfirst(__($transaction->payment_status)) }}</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-gray-600 dark:text-gray-400">
                                    {{ $transaction->created_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-2">
                                        <button
                                            class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition view-details-btn"
                                            data-transaction="{{ json_encode([
                                                'ref' => $transaction->booking_reference,
                                                'txn_id' => $transaction->payment_reference ?: 'N/A',
                                                'customer' => $transaction->guest_name,
                                                'email' => $transaction->guest_email,
                                                'hotel' => $transaction->hotel_name,
                                                'room' => $transaction->room_name,
                                                'amount' => $transaction->currency . ' ' . number_format($transaction->total_price, 2),
                                                'status' => ucfirst($transaction->payment_status),
                                                'date' => $transaction->created_at->format('Y-m-d H:i:s'),
                                                'details' => $transaction->payment_details,
                                            ]) }}"
                                            title="{{ __('View Details') }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('admin.transactions.report', $transaction->id) }}"
                                            class="p-2 text-orange-600 dark:text-orange-400 hover:bg-orange-50 dark:hover:bg-orange-900/30 rounded-lg transition"
                                            title="{{ __('Download Report') }}">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        @if ($transaction->booking_status !== 'cancelled')
                                            <form action="{{ route('admin.transactions.cancel', $transaction->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('{{ __('Are you sure you want to cancel this booking? This will NOT refund the payment, only update the status and notify the user.') }}');">
                                                @csrf
                                                <button type="submit"
                                                    class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition"
                                                    title="{{ __('Cancel Booking') }}">
                                                    <i class="fas fa-window-close"></i>
                                                </button>
                                            </form>
                                        @elseif ($transaction->booking_status === 'cancelled' && $transaction->payment_status === 'paid')
                                            <form action="{{ route('admin.transactions.refund', $transaction->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('{{ __('Mark this transaction as Refunded?') }}');">
                                                @csrf
                                                <button type="submit"
                                                    class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition"
                                                    title="{{ __('Mark as Refunded') }}">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="bg-white dark:bg-gray-800">
                                <td colspan="8" class="py-8 px-6 text-center text-gray-500 dark:text-gray-400">{{ __('No transactions found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Showing') }} <span class="font-semibold">{{ $transactions->firstItem() ?: 0 }}</span> {{ __('to') }} <span
                        class="font-semibold">{{ $transactions->lastItem() ?: 0 }}</span> {{ __('of') }} <span
                        class="font-semibold">{{ number_format($transactions->total()) }}</span> {{ __('results') }}
                </div>
                <div class="flex gap-2">
                    {{ $transactions->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Details Modal -->
    <div id="transactionModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"
                id="modalBackdrop"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white" id="modal-title">{{ __('Transaction Details') }}</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-500" id="closeModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="px-6 py-6" id="modalContent">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs text-gray-500 uppercase tracking-wider">{{ __('Booking Ref') }}</label>
                                <p id="detail-ref" class="font-mono font-bold text-gray-900 dark:text-white"></p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 uppercase tracking-wider">{{ __('Customer') }}</label>
                                <p id="detail-customer" class="text-gray-900 dark:text-white"></p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 uppercase tracking-wider">{{ __('Hotel') }}</label>
                                <p id="detail-hotel" class="text-gray-900 dark:text-white"></p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs text-gray-500 uppercase tracking-wider">{{ __('Transaction ID') }}</label>
                                <p id="detail-txn" class="font-mono text-gray-900 dark:text-white"></p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 uppercase tracking-wider">{{ __('Email') }}</label>
                                <p id="detail-email" class="text-gray-900 dark:text-white"></p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 uppercase tracking-wider">{{ __('Room') }}</label>
                                <p id="detail-room" class="text-gray-900 dark:text-white"></p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 grid grid-cols-3 gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                        <div>
                            <label class="text-xs text-gray-500 uppercase tracking-wider">{{ __('Amount') }}</label>
                            <p id="detail-amount" class="text-lg font-bold text-orange-600 dark:text-orange-400"></p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 uppercase tracking-wider">{{ __('Status') }}</label>
                            <p id="detail-status" class="font-semibold"></p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 uppercase tracking-wider">{{ __('Date') }}</label>
                            <p id="detail-date" class="text-gray-700 dark:text-gray-300"></p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="text-xs text-gray-500 uppercase tracking-wider mb-2 block">{{ __('Technical Details') }}</label>
                        <pre id="detail-raw" class="text-[10px] bg-gray-900 text-green-400 p-4 rounded-lg overflow-x-auto max-h-40"></pre>
                    </div>
                </div>
                <div
                    class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 text-right">
                    <button type="button"
                        class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white rounded-lg hover:bg-gray-400 transition"
                        id="closeModalBtn">
                        {{ __('Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('transactionModal');
            const closeBtns = [document.getElementById('closeModal'), document.getElementById('closeModalBtn'),
                document.getElementById('modalBackdrop')
            ];

            document.querySelectorAll('.view-details-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const data = JSON.parse(this.dataset.transaction);

                    document.getElementById('detail-ref').textContent = '#' + data.ref;
                    document.getElementById('detail-txn').textContent = data.txn_id;
                    document.getElementById('detail-customer').textContent = data.customer;
                    document.getElementById('detail-email').textContent = data.email;
                    document.getElementById('detail-hotel').textContent = data.hotel;
                    document.getElementById('detail-room').textContent = data.room;
                    document.getElementById('detail-amount').textContent = data.amount;
                    document.getElementById('detail-status').textContent = data.status;
                    document.getElementById('detail-date').textContent = data.date;
                    document.getElementById('detail-raw').textContent = JSON.stringify(data.details,
                        null, 2);

                    // Status color matching
                    const statusEl = document.getElementById('detail-status');
                    statusEl.className = 'font-semibold';
                    if (data.status.toLowerCase() === 'paid' || data.status.toLowerCase() ===
                        'successful') {
                        statusEl.classList.add('text-green-600');
                    } else if (data.status.toLowerCase() === 'pending') {
                        statusEl.classList.add('text-yellow-600');
                    } else {
                        statusEl.classList.add('text-red-600');
                    }

                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
            });

            closeBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                });
            });

            // Filter toggle functionality
            const filterToggleBtn = document.getElementById('filterToggleBtn');
            const filterCard = document.getElementById('filterCard');
            
            if (filterToggleBtn && filterCard) {
                filterToggleBtn.addEventListener('click', function() {
                    const isHidden = filterCard.classList.contains('hidden');
                    
                    if (isHidden) {
                        filterCard.classList.remove('hidden');
                    } else {
                        filterCard.classList.add('hidden');
                    }
                    
                    // Update button style to show active state
                    if (!isHidden) {
                        // Filter is now hidden, reset button style
                        this.classList.remove('bg-orange-500', 'text-white', 'border-orange-500', 'dark:bg-orange-500', 'dark:text-white', 'dark:border-orange-500');
                        this.classList.add('bg-white', 'dark:bg-gray-800', 'text-gray-700', 'dark:text-gray-300', 'border-gray-300', 'dark:border-gray-700');
                    } else {
                        // Filter is now visible, highlight button
                        this.classList.remove('bg-white', 'dark:bg-gray-800', 'text-gray-700', 'dark:text-gray-300', 'border-gray-300', 'dark:border-gray-700');
                        this.classList.add('bg-orange-500', 'dark:bg-orange-500', 'text-white', 'dark:text-white', 'border-orange-500', 'dark:border-orange-500');
                    }
                });
            }
        });
    </script>
@endpush
