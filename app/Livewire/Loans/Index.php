<?php

namespace App\Livewire\Loans;

use Carbon\Carbon;
use App\Models\Loan;
use Livewire\Component;
use App\Models\Customer;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

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

    public function openCreate(): void
    {
        // create policy: class-level is OK
        $this->authorize('create', Loan::class);

        $this->resetForm();
        $this->showForm = true;
    }

    public function openEdit(int $id): void
    {
        $loan = Loan::query()
            ->where('user_id', auth()->user->id())
            ->findOrFail($id);

        // update policy: MUST pass the loan instance
        $this->authorize('update', $loan);

        $this->editingId = $loan->id;
        $this->customer_id = $loan->customer_id;
        $this->amount = $loan->amount;
        $this->interest_rate = $loan->interest_rate;
        $this->term_months = $loan->term_months;
        $this->status = $loan->status;
        $this->due_date = $loan->due_date ? Carbon::parse($loan->due_date)->format('Y-m-d') : null;

        $this->showForm = true;
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function save(): void
    {
        // Validation first (ensure all fields are validated before processing)
        $data = $this->validate($this->rules());

        // loan term মাসে based due_date ক্যালকুলেট করুন
        if ($data['term_months'] && $data['amount']) {
            // Disburse date (current date or custom)
            $disbursedAt = now();

            // Adding months to disburse date
            $due_date = Carbon::parse($disbursedAt)->addMonths((int) $data['term_months'])->format('Y-m-d');
        }

        // Preparing the payload for database
        $payload = [
            'user_id' => auth()->user->id(),
            'customer_id' => (int) $data['customer_id'],
            'amount' => (float) $data['amount'],
            'interest_rate' => (float) $data['interest_rate'],
            'term_months' => (int) $data['term_months'],
            'status' => $data['status'],
            'due_date' => $due_date, // using calculated due_date
        ];

        // Update or Create the loan based on the editing status
        if ($this->editingId) {
            $loan = Loan::query()
                ->where('user_id', auth()->user->id())
                ->findOrFail($this->editingId);

            // Authorization: ensuring only authorized users can edit their loans
            $this->authorize('update', $loan);

            // Update loan with the payload
            $loan->update($payload);
            session()->flash('success', 'Loan updated successfully.');
        } else {
            // Authorization: ensuring only authorized users can create a loan
            $this->authorize('create', Loan::class);

            // Create new loan with the payload
            Loan::create($payload);
            session()->flash('success', 'Loan created successfully.');
        }

        // Close the form and reset the fields
        $this->closeForm();
    }


    public function delete(int $id): void
    {
        $loan = Loan::query()
            ->where('user_id', auth()->user->id())
            ->findOrFail($id);

        // delete policy: instance
        $this->authorize('delete', $loan);

        $loan->delete();
        session()->flash('success', 'Loan deleted successfully.');

        $this->resetPage();
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingId',
            'customer_id',
            'amount',
            'interest_rate',
            'term_months',
            'status',
            'due_date',
        ]);

        $this->status = 'active';
    }

    private function rules(): array
    {
        return [
            'customer_id' => [
                'required',
                'integer',
                Rule::exists('customers', 'id')
                    ->where(fn ($q) => $q->where('user_id', auth()->user->id())),
            ],
            'amount' => ['required', 'numeric', 'min:0'],
            'interest_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'term_months' => ['required', 'integer', 'min:1', 'max:600'],
            'status' => ['required', 'string', Rule::in(['active', 'pending', 'closed', 'overdue'])],
            'due_date' => ['required', 'date'],
        ];
    }

    public function render()
    {
        // viewAny policy: class-level OK
        $this->authorize('viewAny', Loan::class);

        $customers = Customer::query()
            ->where('user_id', auth()->id())
            ->orderBy('customer_name')
            ->get(['id', 'customer_name']);

        $loans = Loan::query()
            ->with('customer:id,customer_name')
            ->where('user_id', auth()->user->id())
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
