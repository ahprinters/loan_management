@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Customer Details</h1>
    
    <a href="{{ route('customers.index') }}" class="btn btn-secondary mb-3">Back to Customers</a>
    
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $customer->customer_name }}</h5>
            <p class="card-text"><strong>Address:</strong> {{ $customer->customer_address }}</p>
            <p class="card-text"><strong>Phone:</strong> {{ $customer->customer_phone }}</p>
            <p class="card-text"><strong>Email:</strong> {{ $customer->customer_email }}</p>
            
            <div class="mt-3">
                <a href="{{ route('customers.edit', $customer) }}" class="btn btn-primary">Edit</a>
                <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection