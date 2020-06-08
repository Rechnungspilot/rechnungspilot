@extends('layouts.home')

@section('content')

    <div class="container">
        @if (session('status'))
            <div class="mt-3 alert alert-info">{{ session('status') }}</div>
        @endif
        <div class="card mt-4 shadow-sm">
            <div class="card-header">
                <h4 class="">Rechnung für KeepSeven erstellen</h4>
            </div>
            <div class="card-body">
                <form action="/rechnungen/keepseven" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="quantity">Betrag</label>
                        <input class="form-control {{ $errors->has('quantity') ? 'is-invalid' : '' }}" type="text" name="quantity" id="quantity">
                        @error('quantity')
                            <div class="invalid-feedback">
                                {{ $message}}
                            </div>
                        @enderror
                    </div>

                    <button class="btn btn-primary">Rechnung erstellen</button>
                </form>
            </div>
        </div>
    </div>

@endsection