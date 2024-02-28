@extends("layouts.app")
@section("content")
    @can('admin')
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content w-75">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Delete the listing?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" id="delete_form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-primary">Delete</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
    @endcan
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card p-3">
                    <div class="row mt-2">
                        <div class="col-md-3">
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-expanded="false">{{ __('Categories') }}
                                </button>
                                <ul class="dropdown-menu overflow-auto" style="height: 200px"
                                    aria-labelledby="dropdownMenuButton">
                                    @foreach ($categories as $category)
                                        <li>
                                            <a class="dropdown-item{{isset($currentCategory) && $currentCategory->id===$category->id ? ' active' : ''}}"
                                               href="?category={{ $category->slug }}">
                                                {{ ucwords($category->name) }}
                                            </a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <form action="/" method="GET">
                                <div class="input-group rounded">
                                    <input type="text" class="form-control rounded" placeholder="Search"
                                           aria-label="Search"
                                           value="{{request('search')}}" name="search"/>
                                    <button type="submit" class="btn btn-primary pb-0" id="search-addon">
                                        <span id="icon" class="material-icons">&#xe8b6;</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="album py-5">
        <div class="container">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                @if ($listings->count()>=1)
                    @foreach ($listings as $listing)
                        <div class="col">
                            <div class="card shadow-sm h-100">
                                <a href="listing/{{ $listing->slug }}">
                                    <img src="{{ asset($listing->thumbnail) }}"
                                         alt="image" class="card-img-top" id="listing-img"></a>
                                <div class="card-body pb-3">
                                    <a href="{{ route('listing-listing:slug',['listing'=>$listing->slug]) }}"
                                       class="card-text text-dark lead" id="listing-body">{{ $listing->title }}
                                    </a>
                                    <div class="justify-content-between align-items-center">
                                        <div class="btn-group position-absolute bottom-0 end-0 m-2">
                                            @can('admin')
                                                <a href="{{ route('admin.listing.edit',['listing'=>$listing->slug]) }}"
                                                   type="button"
                                                   class="btn btn-sm btn-outline-secondary">Edit
                                                </a>
                                                <btn type="button"
                                                     data-listing_slug="{{$listing->slug}}"
                                                     data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                                                     class="btn btn-sm btn-outline-secondary">Delete
                                                </btn>
                                            @endcan
                                        </div>
                                        <dl class="row mb-4">
                                            <dt class="col">{{ $listing->price }} euro</dt>
                                            <dd class="col col-md-auto small">{{ $listing->created_at->locale('ro')->diffForHumans() }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                @else
                    <div class="card mx-auto">
                        <div class="card-body align-self-center">
                            <p>{{ __('No listing available.') }}</p>
                        </div>
                    </div>
                @endif
            </div>
            {{$listings->links( )}}
        </div>
    </div>

@endsection
@can('admin')
    @push('js')
        <script src='{{asset('js/listing-delete.js')}}'></script>
    @endpush
@endcan
