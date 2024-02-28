@extends("layouts.app")
@section("content")
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content w-75">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{ __('Delete the listing?') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <form method="POST" id="delete_form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-primary">{{ __('Delete') }}</button>
                    </form>

                </div>
            </div>
        </div>
    </div>


    <div class="album py-3">
        <div class="container">
            <h1 class="heading">
                My listings
            </h1>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                @if ($listings->count()>=1)
                    @foreach ($listings as $listing)
                        <div class="col">
                            <div class="card shadow-sm h-100">
                                <a href="{{ route('listing-listing:slug',['listing'=>$listing->slug]) }}">
                                    <img src="{{ asset($listing->thumbnail) }}"
                                         alt="image" class="card-img-top" id="listing-img"></a>
                                <div class="card-body pb-3">
                                    <a href="{{ route('listing-listing:slug',['listing'=>$listing->slug]) }}"
                                       class="card-text text-dark lead" id="listing-body">{{ $listing->title }}
                                    </a>
                                    <div class="justify-content-between align-items-center">
                                        <div class="btn-group position-absolute bottom-0 end-0 m-2">
                                            <a href="{{ $listing->slug }}/edit" type="button"
                                               class="btn btn-sm btn-outline-secondary">{{ __('Edit') }}
                                            </a>
                                            <btn type="button"
                                                 data-listing_slug="{{$listing->slug}}"
                                                 data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                                                 class="btn btn-sm btn-outline-secondary">{{ __('Delete') }}
                                            </btn>
                                        </div>
                                        <dl class="row mb-4">
                                            <dt class="col">{{ $listing->price }} lei</dt>
                                            <dd class="col col-md-auto small">{{ $listing->created_at->locale('ro')->diffForHumans() }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endforeach

                @else
                    <div class="card mx-auto">
                        <div class="card-body">
                            <p>{{ __('No listings available.') }}</p>
                        </div>
                    </div>
                @endif
            </div>
            {{$listings->links( )}}
        </div>
    </div>
@endsection
@push('js')
    <script src='{{asset('js/listing-delete.js')}}'></script>
@endpush
