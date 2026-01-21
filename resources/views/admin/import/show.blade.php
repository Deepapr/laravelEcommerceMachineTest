@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-xl font-semibold mb-4">Import Products</h2>

    @if(session('success'))
        <div class="bg-green-200 p-2 mb-3">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="bg-red-200 p-2 mb-3">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('import.process') }}" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" required class="border p-2">
        <button class="ml-3 bg-blue-600 text-white px-4 py-2 rounded">
            Import
        </button>
    </form>
</div>
@endsection
