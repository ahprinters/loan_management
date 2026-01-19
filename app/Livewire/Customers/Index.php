<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    public ?int $customer_id = null;

    public string $customer_name = '';
    public string $customer_address = '';
    public string $customer_phone = '';
    public string $customer_email = '';

    public bool $isEdit = false;

    protected function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:100'],
            'customer_address' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'customer_email' => ['required', 'email', 'max:100'],
        ];
    }

    private function uid(): int
    {
        // Safe: will return int id or abort if not logged in
        $id = Auth::id();
        abort_unless($id, 403, 'Unauthorized');
        return (int) $id;
    }

    public function render()
    {
        $uid = $this->uid();

        return view('livewire.customers.index', [
            'customers' => Customer::query()
                ->where('user_id', $uid)
                ->latest()
                ->paginate(10),
        ]);
    }

    public function resetForm(): void
    {
        $this->reset([
            'customer_id',
            'customer_name',
            'customer_address',
            'customer_phone',
            'customer_email',
            'isEdit',
        ]);

        $this->resetValidation();

        // toast optional: comment out if your frontend doesn't listen this event
        $this->dispatch('toast', type: 'info', message: 'Form reset');
    }

    public function create(): void
    {
        $this->resetForm();
        $this->isEdit = false;
    }

    public function store(): void
    {
        $uid = $this->uid();

        $validated = $this->validate();

        Customer::create($validated + [
            'user_id' => $uid,
        ]);

        $this->resetForm();
        $this->dispatch('toast', type: 'success', message: 'Customer created successfully.');
    }

    public function edit(int $customerId): void
    {
        $uid = $this->uid();

        $customer = Customer::query()
            ->where('user_id', $uid)
            ->findOrFail($customerId);

        $this->customer_id = $customer->id;
        $this->customer_name = (string) $customer->customer_name;
        $this->customer_address = (string) $customer->customer_address;
        $this->customer_phone = (string) $customer->customer_phone;
        $this->customer_email = (string) $customer->customer_email;

        $this->isEdit = true;
        $this->resetValidation();
    }

    public function update(): void
    {
        $uid = $this->uid();

        abort_unless($this->customer_id, 422, 'No customer selected.');

        $validated = $this->validate();

        $customer = Customer::query()
            ->where('user_id', $uid)
            ->findOrFail($this->customer_id);

        $customer->update($validated);

        $this->resetForm();
        $this->dispatch('toast', type: 'success', message: 'Customer updated successfully.');
    }

    public function destroy(int $customerId): void
    {
        $uid = $this->uid();

        $customer = Customer::query()
            ->where('user_id', $uid)
            ->findOrFail($customerId);

        $customer->delete();

        // If last page becomes empty after delete
        $this->resetPage();

        $this->dispatch('toast', type: 'success', message: 'Customer deleted successfully.');
    }
}
