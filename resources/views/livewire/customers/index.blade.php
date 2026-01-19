<div class="p-4">
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold">Customers</h2>
            <p class="text-sm text-gray-500 mt-1">Create and manage your own customers.</p>
        </div>

        <button
            type="button"
            class="px-3 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800"
            wire:click="create"
        >
            + New Customer
        </button>
    </div>

    {{-- Alerts / Toast fallback --}}
    @if (session('success'))
        <div class="mb-3 p-3 bg-green-100 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Form --}}
        <div class="md:col-span-1 p-4 border rounded-xl bg-white">
            <h3 class="font-semibold mb-3">
                {{ $isEdit ? 'Edit Customer' : 'Create Customer' }}
            </h3>

            <div class="space-y-3">
                <div>
                    <label class="block text-sm mb-1 text-gray-700">Name</label>
                    <input
                        type="text"
                        class="w-full border rounded-lg px-3 py-2 focus:border-gray-900 focus:ring-gray-900"
                        wire:model.defer="customer_name"
                        placeholder="Customer name"
                    >
                    @error('customer_name') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="block text-sm mb-1 text-gray-700">Address</label>
                    <input
                        type="text"
                        class="w-full border rounded-lg px-3 py-2 focus:border-gray-900 focus:ring-gray-900"
                        wire:model.defer="customer_address"
                        placeholder="Customer address"
                    >
                    @error('customer_address') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="block text-sm mb-1 text-gray-700">Phone</label>
                    <input
                        type="text"
                        class="w-full border rounded-lg px-3 py-2 focus:border-gray-900 focus:ring-gray-900"
                        wire:model.defer="customer_phone"
                        placeholder="e.g. 017xxxxxxxx"
                    >
                    @error('customer_phone') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="block text-sm mb-1 text-gray-700">Email</label>
                    <input
                        type="email"
                        class="w-full border rounded-lg px-3 py-2 focus:border-gray-900 focus:ring-gray-900"
                        wire:model.defer="customer_email"
                        placeholder="customer@email.com"
                    >
                    @error('customer_email') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="flex gap-2 pt-1">
                    @if($isEdit)
                        <button
                            type="button"
                            class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                            wire:click="update"
                            wire:loading.attr="disabled"
                        >
                            <span wire:loading.remove>Update</span>
                            <span wire:loading>Updating...</span>
                        </button>
                    @else
                        <button
                            type="button"
                            class="px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
                            wire:click="store"
                            wire:loading.attr="disabled"
                        >
                            <span wire:loading.remove>Save</span>
                            <span wire:loading>Saving...</span>
                        </button>
                    @endif

                    <button
                        type="button"
                        class="px-3 py-2 bg-gray-100 rounded-lg hover:bg-gray-200"
                        wire:click="resetForm"
                    >
                        Reset
                    </button>
                </div>
            </div>
        </div>

        {{-- List --}}
        <div class="md:col-span-2 p-4 border rounded-xl bg-white">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold">Customer List</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                    <tr class="bg-gray-50 text-gray-600 border-b">
                        <th class="px-3 py-2 text-left">Name</th>
                        <th class="px-3 py-2 text-left">Phone</th>
                        <th class="px-3 py-2 text-left">Email</th>
                        <th class="px-3 py-2 text-left">Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($customers as $c)
                        <tr class="border-b last:border-0">
                            <td class="px-3 py-2 font-medium text-gray-900">
                                {{ $c->customer_name }}
                            </td>
                            <td class="px-3 py-2 text-gray-700">
                                {{ $c->customer_phone }}
                            </td>
                            <td class="px-3 py-2 text-gray-700">
                                {{ $c->customer_email }}
                            </td>
                            <td class="px-3 py-2">
                                <div class="flex gap-2">
                                    <button
                                        class="px-2 py-1 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600"
                                        wire:click="edit({{ $c->id }})"
                                    >
                                        Edit
                                    </button>

                                    {{-- Livewire 3 friendly confirm --}}
                                    <button
                                        class="px-2 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700"
                                        wire:click="destroy({{ $c->id }})"
                                        wire:confirm="Delete this customer?"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-3 py-6 text-center text-gray-500" colspan="4">
                                No customers found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
</div>
