<?php

namespace App\Livewire\Loans;

use Carbon\Carbon;
use App\Models\Loan;
use Livewire\Component;
use App\Models\Customer;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = 'all';
    public bool $showForm = false;
    public ?int $editingId = null;

    // Form fields
    public ?int $customer_id = null;
    public $amount = null;
    public $interest_rate = null;
    public $term_months = null;
    public string $status = 'active';
    public ?string $due_date = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    // Open form for creating or editing
    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    // Open form for editing loan
    public function openEdit(int $id): void
    {
        $this->authorize('update', Loan::class);  // Authorization check

        $loan = Loan::query()
            ->where('user_id', auth::id())
            ->findOrFail($id);

        $this->editingId = $loan->id;
        $this->customer_id = $loan->customer_id;
        $this->amount = $loan->amount;
        $this->interest_rate = $loan->interest_rate;
        $this->term_months = $loan->term_months;
        $this->status = $loan->status;
        $this->due_date = $loan->due_date ? Carbon::parse($loan->due_date)->format('Y-m-d') : null;

        $this->showForm = true;
    }

    // Close form
    public function closeForm(): void
    {
        $this->showForm = false;
        $this->resetForm();
    }

    // Save new loan or update
    public function save(): void
    {
        $this->authorize('create', Loan::class);  // Authorization check

        $data = $this->validate($this->rules());

        $payload = [
            'user_id' => auth::id(),
            'customer_id' => (int) $data['customer_id'],
            'amount' => (float) $data['amount'],
            'interest_rate' => (float) $data['interest_rate'],
            'term_months' => (int) $data['term_months'],
            'status' => $data['status'],
            'due_date' => $data['due_date'] ? Carbon::parse($data['due_date'])->format('Y-m-d') : null,
        ];

        if ($this->editingId) {
            $loan = Loan::query()
                ->where('user_id', auth::id())
                ->findOrFail($this->editingId);

            $loan->update($payload);
            session()->flash('success', 'Loan updated successfully.');
        } else {
            Loan::create($payload);
            session()->flash('success', 'Loan created successfully.');
        }

        $this->closeForm();
    }

    // Delete loan
    public function delete(int $id): void
    {
        $this->authorize('delete', Loan::class);  // Authorization check

        $loan = Loan::query()
            ->where('user_id', auth::id())
            ->findOrFail($id);

        $loan->delete();
        session()->flash('success', 'Loan deleted successfully.');

        $this->resetPage();
    }

    // Reset form fields
    private function resetForm(): void
    {
        $this->reset([
            'editingId', 'customer_id', 'amount', 'interest_rate', 'term_months', 'status', 'due_date'
        ]);
        $this->status = 'active';
    }

    // Validation rules
    private function rules(): array
    {
        return [
            'customer_id' => [
                'required',
                'integer',
                Rule::exists('customers', 'id')->where(fn ($q) => $q->where('user_id', auth::id()))
            ],
            'amount' => ['required', 'numeric', 'min:0'],
            'interest_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'term_months' => ['required', 'integer', 'min:1', 'max:600'],
            'status' => ['required', 'string', Rule::in(['active', 'pending', 'closed', 'overdue'])],
            'due_date' => ['required', 'date', 'after_or_equal:today'],
        ];
    }

    // Render the loans view with pagination, search and filter applied
    public function render()
    {
        $this->authorize('viewAny', Loan::class);  // Authorization check

        $customers = Customer::query()
            ->where('user_id', auth::id())
            ->orderBy('customer_name')
            ->get(['id', 'customer_name']);

        $loans = Loan::query()
            ->with('customer:id,customer_name')
            ->where('user_id', auth::id())
            ->when($this->statusFilter !== 'all', fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->search !== '', function ($q) {
                $term = '%' . $this->search . '%';
                $q->where(function ($qq) use ($term) {
                    $qq->where('status', 'like', $term)
                        ->orWhere('amount', 'like', $term)
                        ->orWhereHas('customer', fn ($qc) => $qc->where('customer_name', 'like', $term));
                });
            })
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.loans.index', [
            'customers' => $customers,
            'loans' => $loans,
        ]);
    }
}
