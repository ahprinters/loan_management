<div class="p-4">
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-xl font-semibold">Customers</h2>
        <button type="button" class="px-3 py-2 bg-gray-800 text-white rounded" wire:click="create">
            + New Customer
        </button>
    </div>

    {{-- Alerts / Toast fallback --}}
    @if (session('success'))
        <div class="mb-3 p-3 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Form --}}
        <div class="md:col-span-1 p-4 border rounded">
            <h3 class="font-semibold mb-3">
                {{ $isEdit ? 'Edit Customer' : 'Create Customer' }}
            </h3>

            <div class="space-y-3">
                <div>
                    <label class="block text-sm mb-1">Name</label>
                    <input type="text" class="w-full border rounded px-3 py-2"
                           wire:model.defer="customer_name">
                    @error('customer_name') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="block text-sm mb-1">Address</label>
                    <input type="text" class="w-full border rounded px-3 py-2"
                           wire:model.defer="customer_address">
                    @error('customer_address') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="block text-sm mb-1">Phone</label>
                    <input type="text" class="w-full border rounded px-3 py-2"
                           wire:model.defer="customer_phone">
                    @error('customer_phone') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="block text-sm mb-1">Email</label>
                    <input type="email" class="w-full border rounded px-3 py-2"
                           wire:model.defer="customer_email">
                    @error('customer_email') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                </div>

                <div class="flex gap-2">
                    @if($isEdit)
                        <button type="button" class="px-3 py-2 bg-blue-600 text-white rounded"
                                wire:click="update">
                            Update
                        </button>
                    @else
                        <button type="button" class="px-3 py-2 bg-green-600 text-white rounded"
                                wire:click="store">
                            Save
                        </button>
                    @endif

                    <button type="button" class="px-3 py-2 bg-gray-200 rounded"
                            wire:click="resetForm">
                        Reset
                    </button>
                </div>
            </div>
        </div>

        {{-- List --}}
        <div class="md:col-span-2 p-4 border rounded">
            <h3 class="font-semibold mb-3">Customer List</h3>

            <div class="overflow-x-auto">
                <table class="w-full border">
                    <thead>
                    <tr class="bg-gray-50">
                        <th class="border px-3 py-2 text-left">Name</th>
                        <th class="border px-3 py-2 text-left">Phone</th>
                        <th class="border px-3 py-2 text-left">Email</th>
                        <th class="border px-3 py-2 text-left">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($customers as $c)
                        <tr>
                            <td class="border px-3 py-2">{{ $c->customer_name }}</td>
                            <td class="border px-3 py-2">{{ $c->customer_phone }}</td>
                            <td class="border px-3 py-2">{{ $c->customer_email }}</td>
                            <td class="border px-3 py-2">
                                <div class="flex gap-2">
                                    <button class="px-2 py-1 bg-yellow-500 text-white rounded"
                                            wire:click="edit({{ $c->id }})">
                                        Edit
                                    </button>

                                    <button class="px-2 py-1 bg-red-600 text-white rounded"
                                            onclick="confirm('Delete this customer?') || event.stopImmediatePropagation()"
                                            wire:click="destroy({{ $c->id }})">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="border px-3 py-4 text-center" colspan="4">
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

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('toast', (data) => {
                // তুমি চাইলে এখানে toastr / sweetalert বসাতে পারো
                console.log(data);
            });
        });
    </script>
</div>
