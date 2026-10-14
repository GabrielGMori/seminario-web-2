@extends('layouts.app')

@section('content')
<div class="container py-5">
    <livewire:produto-form :produto="request()->route('produto')" />
</div>
@endsection
