@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <livewire:produto-form :produto="$produto ?? null" />
    </div>
@endsection
