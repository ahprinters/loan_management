<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
            <p class="text-sm text-gray-600 mt-1">Quick overview of customers and loans.</p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('customers.index') }}" class="inline-flex items-center px-3 py-2 rounded-lg bg-gray-900 text-white text-sm hover:bg-gray-800">
                Manage Customers
            </a>
            <a href="{{ route('loans.index') }}" class="inline-flex items-center px-3 py-2 rounded-lg bg-gray-100 text-gray-800 text-sm hover:bg-gray-200">
                Manage Loans
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white border rounded-xl p-4">
            <div class="text-sm text-gray-600">Customers</div>
            <div class="text-2xl font-semibold mt-1">{{ number_format($customersCount) }}</div>
        </div>

        <div class="bg-white border rounded-xl p-4">
            <div class="text-sm text-gray-600">Total loans</div>
            <div class="text-2xl font-semibold mt-1">{{ number_format($loansCount) }}</div>
        </div>

        <div class="bg-white border rounded-xl p-4">
            <div class="text-sm text-gray-600">Active loans</div>
            <div class="text-2xl font-semibold mt-1">{{ number_format($activeLoans) }}</div>
        </div>

        <div class="bg-white border rounded-xl p-4">
            <div class="text-sm text-gray-600">Overdue</div>
            <div class="text-2xl font-semibold mt-1">{{ number_format($overdueLoans) }}</div>
            <div class="text-xs text-gray-500 mt-1">Due date passed</div>
        </div>

        <div class="bg-white border rounded-xl p-4">
            <div class="text-sm text-gray-600">Total amount</div>
            <div class="text-2xl font-semibold mt-1">{{ number_format((float) $totalLoanAmount, 2) }}</div>
            <div class="text-xs text-gray-500 mt-1">Sum of loan amounts</div>
        </div>
    </div>

    <div class="bg-white border rounded-xl p-5">
        <h2 class="text-lg font-semibold">Smart suggestions</h2>
        <ul class="list-disc pl-5 text-sm text-gray-700 mt-3 space-y-2">
            <li><span class="font-medium">Protect routes:</span> Customers and Dashboard are now behind login.</li>
            <li><span class="font-medium">Loans module:</span> you can now create loans with amount, interest, term, status, due date and customer.</li>
            <li><span class="font-medium">Roles:</span> add Admin / Staff permissions if multiple users will manage loans.</li>
            <li><span class="font-medium">Reports:</span> monthly disbursement, overdue list, and customer ledger.</li>
        </ul>
    </div>
</div>
