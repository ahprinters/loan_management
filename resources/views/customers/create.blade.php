@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add New Customer</h1>
    
    <a href="{{ route('customers.index') }}" class="btn btn-secondary mb-3">Back to Customers</a>
    
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('customers.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="customer_name" class="form-label">Name</label>
            <input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
        </div>
        <div class="mb-3">
            <label for="customer_address" class="form-label">Address</label>
            <textarea class="form-control" id="customer_address" name="customer_address" required>{{ old('customer_address') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="customer_phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" required>
        </div>
        <div class="mb-3">
            <label for="customer_email" class="form-label">Email</label>
            <input type="email" class="form-control" id="customer_email" name="customer_email" value="{{ old('customer_email') }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Customer</button>
    </form>
</div>
@endsection