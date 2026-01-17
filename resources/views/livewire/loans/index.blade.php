<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Loans</h1>
            <p class="text-sm text-gray-600 mt-1">Create, update, and track loans for your customers.</p>
        </div>

        <div class="flex gap-2">
            <button
                type="button"
                wire:click="openCreate"
                class="inline-flex items-center px-3 py-2 rounded-lg bg-gray-900 text-white text-sm hover:bg-gray-800"
            >
                + New Loan
            </button>
            <a href="{{ route('customers.index') }}" class="inline-flex items-center px-3 py-2 rounded-lg bg-gray-100 text-gray-800 text-sm hover:bg-gray-200">
                Customers
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-3 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border rounded-xl p-4">
        <div class="flex flex-col md:flex-row gap-3 md:items-center md:justify-between">
            <div class="flex-1">
                <input
                    type="text"
                    wire:model.live="search"
                    placeholder="Search by customer name, amount, or status..."
                    class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                />
            </div>

            <div class="flex gap-2">
                <select wire:model.defer="statusFilter" class="rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 text-sm">
                    <option value="all">All status</option>
                    <option value="active">Active</option>
                    <option value="pending">Pending</option>
                    <option value="overdue">Overdue</option>
                    <option value="closed">Closed</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto mt-4">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-600 border-b">
                        <th class="py-2 pr-4">Customer</th>
                        <th class="py-2 pr-4">Amount</th>
                        <th class="py-2 pr-4">Interest</th>
                        <th class="py-2 pr-4">Term</th>
                        <th class="py-2 pr-4">Status</th>
                        <th class="py-2 pr-4">Due date</th>
                        <th class="py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($loans as $loan)
                        <tr class="border-b last:border-0">
                            <td class="py-3 pr-4">
                                <div class="font-medium text-gray-900">{{ $loan->customer?->customer_name ?? '—' }}</div>
                                <div class="text-xs text-gray-500">#{{ $loan->id }}</div>
                            </td>
                            <td class="py-3 pr-4 text-gray-900">{{ number_format((float) $loan->amount, 2) }}</td>
                            <td class="py-3 pr-4 text-gray-700">{{ number_format((float) $loan->interest_rate, 2) }}%</td>
                            <td class="py-3 pr-4 text-gray-700">{{ (int) $loan->term_months }} mo</td>
                            <td class="py-3 pr-4">
                                @php
                                    $badge = match($loan->status) {
                                        'active' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                        'overdue' => 'bg-red-50 text-red-700 border-red-200',
                                        'closed' => 'bg-gray-50 text-gray-700 border-gray-200',
                                        default => 'bg-gray-50 text-gray-700 border-gray-200'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-1 rounded-lg border text-xs {{ $badge }}">
                                    {{ ucfirst($loan->status) }}
                                </span>
                            </td>
                            <td class="py-3 pr-4 text-gray-700">
                                {{ optional($loan->due_date)->format('Y-m-d') ?? '—' }}
                            </td>
                            <td class="py-3">
                                <div class="flex items-center gap-2">
                                    <button wire:click="openEdit({{ $loan->id }})" class="text-sm text-gray-900 hover:underline">Edit</button>
                                    <button
                                        wire:click="delete({{ $loan->id }})"
                                        wire:confirm="Are you sure you want to delete this loan?"
                                        class="text-sm text-red-600 hover:underline"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-gray-500">
                                No loans found. Create your first loan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $loans->links() }}
        </div>
    </div>

    {{-- Form modal --}}
    @if ($showForm)
        <div class="fixed inset-0 z-50">
            <div class="absolute inset-0 bg-black/30" wire:click="closeForm"></div>

            <div class="relative max-w-2xl mx-auto mt-16 bg-white rounded-2xl shadow-xl border p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">
                            {{ $editingId ? 'Edit Loan' : 'New Loan' }}
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">Fill amount, interest, term, status, due date and customer.</p>
                    </div>
                    <button type="button" wire:click="closeForm" class="text-gray-500 hover:text-gray-900">✕</button>
                </div>

                <form wire:submit.prevent="save" class="mt-5 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Customer</label>
                        <select wire:model="customer_id" class="mt-1 w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                            <option value="">Select a customer</option>
                            @foreach ($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->customer_name }}</option>
                            @endforeach
                        </select>
                        @error('customer_id') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Amount</label>
                            <input type="number" step="0.01" wire:model="amount" class="mt-1 w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900" placeholder="e.g. 50000" />
                            @error('amount') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Interest rate (%)</label>
                            <input type="number" step="0.01" wire:model="interest_rate" class="mt-1 w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900" placeholder="e.g. 12" />
                            @error('interest_rate') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Term (months)</label>
                            <input type="number" wire:model="term_months" class="mt-1 w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900" placeholder="e.g. 24" />
                            @error('term_months') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select wire:model="status" class="mt-1 w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                                <option value="active">Active</option>
                                <option value="pending">Pending</option>
                                <option value="overdue">Overdue</option>
                                <option value="closed">Closed</option>
                            </select>
                            @error('status') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Due date</label>
                            <input type="date" wire:model="due_date" class="mt-1 w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900" />
                            @error('due_date') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" wire:click="closeForm" class="px-4 py-2 rounded-lg border text-sm hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-gray-900 text-white text-sm hover:bg-gray-800">
                            {{ $editingId ? 'Update' : 'Create' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
