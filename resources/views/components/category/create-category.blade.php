@extends('layouts.app')
@section('content')
    <div class="px-5 py-5 mx-auto w-25">
        <div class="card">
            <div class="card-header">{{ __('Categorie') }}</div>

            <div class="card-body">
                <form method="POST" action="/admin/categories/store">
                    @csrf
                    <div class="form-group">
                        <x-form.input name="name" autofocus type="text">{{ __('Name') }}</x-form.input>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">{{ __('Submit') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
