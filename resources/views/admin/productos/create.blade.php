@extends('layouts.admin')

@section('title', 'Nuevo producto')

@section('content')
<p class="admin-subtitle">Registra una nueva prenda, sus variantes y el stock inicial.</p>
<div class="panel">
    @include('admin.productos._form', ['producto' => null])
</div>
@endsection
