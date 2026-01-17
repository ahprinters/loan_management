<div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-sm border p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold">Create account</h1>
            <p class="text-sm text-gray-600 mt-1">Register to start managing customers and loans.</p>
        </div>

        <form wire:submit.prevent="register" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" wire:model.defer="name"
                       class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring"
                       placeholder="Your name" autocomplete="name" />
                @error('name') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" wire:model.defer="email"
                       class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring"
                       placeholder="you@example.com" autocomplete="username" />
                @error('email') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" wire:model.defer="password"
                       class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring"
                       placeholder="Minimum 8 characters" autocomplete="new-password" />
                @error('password') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm password</label>
                <input type="password" wire:model.defer="password_confirmation"
                       class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring"
                       placeholder="Repeat your password" autocomplete="new-password" />
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('login') }}" class="text-sm text-gray-800 underline">Already have an account?</a>
            </div>

            <button type="submit"
                    class="w-full bg-gray-900 text-white rounded-lg px-4 py-2 font-medium hover:bg-gray-800">
                Create account
            </button>
        </form>
    </div>
</div>
