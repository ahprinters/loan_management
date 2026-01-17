<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    public $customer_id = null;

    public $customer_name = '';
    public $customer_address = '';
    public $customer_phone = '';
    public $customer_email = '';

    public $isEdit = false;

    protected function rules()
    {
        return [
            'customer_name' => 'required|string|max:100',
            'customer_address' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'required|email|max:100',
        ];
    }

    public function render()
    {
        return view('livewire.customers.index', [
            'customers' => Customer::query()
                ->where('user_id', auth::id())
                ->latest()
                ->paginate(10),
        ]);
    }

    public function resetForm()
    {
        $this->reset(['customer_id', 'customer_name', 'customer_address', 'customer_phone', 'customer_email', 'isEdit']);
        $this->resetValidation();
        $this->dispatch('toast', type: 'info', message: 'Form reset');
    }

    public function create()
    {
        $this->resetForm();
        $this->isEdit = false;
    }

    public function store()
    {
        $validated = $this->validate();

        Customer::create(array_merge($validated, [
            'user_id' => auth::id(),
        ]));

        $this->resetForm();
        $this->dispatch('toast', type: 'success', message: 'Customer created successfully.');
    }

    public function edit(Customer $customer)
    {
        abort_unless($customer->user_id === auth::id(), 403);

        $this->customer_id = $customer->id;
        $this->customer_name = $customer->customer_name;
        $this->customer_address = $customer->customer_address;
        $this->customer_phone = $customer->customer_phone;
        $this->customer_email = $customer->customer_email;

        $this->isEdit = true;
        $this->resetValidation();
    }

    public function update()
    {
        $validated = $this->validate();

        $customer = Customer::query()
            ->where('user_id', auth::id())
            ->findOrFail($this->customer_id);

        $customer->update($validated);

        $this->resetForm();
        $this->dispatch('toast', type: 'success', message: 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        abort_unless($customer->user_id === auth::id(), 403);

        $customer->delete();

        // যদি last page থেকে delete করে empty হয়ে যায়, pagination ঠিক রাখতে
        $this->resetPage();

        $this->dispatch('toast', type: 'success', message: 'Customer deleted successfully.');
    }
}
