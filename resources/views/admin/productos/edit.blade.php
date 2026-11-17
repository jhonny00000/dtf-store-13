@extends('layouts.admin')

@section('title', 'Editar producto')

@section('content')
<p class="admin-subtitle">Actualiza la información comercial del producto {{ $producto->nombre }}.</p>
<div class="panel">
    @include('admin.productos._form', ['producto' => $producto])
</div>
@endsection
