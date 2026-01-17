<header class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="h-16 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="/" class="font-semibold text-gray-900">Loan Management</a>

                <nav class="hidden sm:flex items-center gap-4 text-sm">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                        <a href="{{ route('customers.index') }}" class="text-gray-700 hover:text-gray-900">Customers</a>
                        <a href="{{ route('loans.index') }}" class="text-gray-700 hover:text-gray-900">Loans</a>
                    @endauth
                </nav>
            </div>

            <div class="flex items-center gap-3">
                @guest
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900">Login</a>
                    <a href="{{ route('register') }}" class="text-sm bg-gray-900 text-white px-3 py-2 rounded-lg hover:bg-gray-800">Register</a>
                @endguest

                @auth
                    <span class="hidden sm:inline text-sm text-gray-600">Hi, {{ auth()->user()->name }}</span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-700 hover:text-gray-900">Logout</button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</header>
