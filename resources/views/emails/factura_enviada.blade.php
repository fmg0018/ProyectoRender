@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Factura {{ $factura->numero ?? $factura->numero_factura }}</h1>
        <p>Estimado cliente,</p>
        <p>Adjuntamos su factura.</p>
        <p>Gracias.</p>
    </div>
@endsection
