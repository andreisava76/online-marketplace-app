@extends("layouts.app")
@section("content")
    <div class="container">
        <a href="javascript:history.back()" class="previous text-black font-weight-bold h5">&laquo; {{ __('Back') }}</a>
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-body bg-body-secondary">
                        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="{{ asset('storage/files/'.$images[0]->image) }}"
                                         style="height: 350px"
                                         class="img-fluid d-block mx-auto img-thumbnail rounded-3" alt="">
                                </div>
                                @foreach($images->skip(1) as $image)
                                    <div class="carousel-item">
                                        <img src="{{asset('storage/files/'.$image->image)}}"
                                             style="height: 350px"
                                             class="img-fluid d-block mx-auto img-thumbnail rounded-3" alt="">
                                    </div>
                                @endforeach
                            </div>
                            <div class="carousel-indicators">
                                @for($i=0;$i<count($images);$i++)
                                    <button type="button" data-bs-target="#carouselExampleIndicators"
                                            data-bs-slide-to="{{$i}}"
                                            class="active bg-dark-subtle" aria-current="true"
                                            aria-label="Slide {{$i+1}}"></button>
                                @endfor
                            </div>
                            <button class="carousel-control-prev" type="button"
                                    data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon bg-dark-subtle rounded-1"
                                      aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button"
                                    data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                <span class="carousel-control-next-icon bg-dark-subtle rounded-1"
                                      aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-header text-bg-secondary text-center"><h5>{{ __('Seller') }}</h5></div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-4">
                                <div class="border border-dark rounded w-75">
                                    <x-icons.user-listing-svg class="img-fluid mx-auto d-block svg-icon"/>
                                </div>
                            </div>
                            <div class="col-8">
                                <h5>{{$listing->user->name}}</h5>
                            </div>
                        </div>
                            <div class="row" id="seller_details">
                                <div class="col-6">
                                    <button type="button" class="btn btn-outline-secondary btn-block"
                                            onclick="show_phone()"
                                            id="btn-phone">
                                        {{ __('Show phone') }}
                                    </button>
                                </div>
                        @if(Auth::check() && $listing->user_id!==Auth::user()->id)
                                <div class="col-6">
                                    <form method="POST" action="{{ route('chat.rooms') }}" name="message-form"
                                          id="message-form">
                                        @csrf
                                        <input type="hidden" id="sender_id" name="sender_id"
                                               value="{{auth()->id()}}">
                                        <input type="hidden" id="recipient_id" name="recipient_id"
                                               value="{{$listing->user->id}}">
                                        <button type="submit" id="btn-message"
                                                class="btn btn-success btn-block">{{ __('Message') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-8">
                <div class="card flex-md-row">
                    <div class="card-body px-5">
                        <div class="card-body">
                            <span
                                class="border border-danger-subtle rounded-4 text-bg-secondary px-3 py-2">
                                {{$listing->category->name}}
                            </span>
                            <span class="border border-info-subtle rounded-4 text-bg-secondary px-3 py-2 ml-1">
                                Condition:
                                @if($listing->condition==="new")
                                    new
                                @else
                                    used
                                @endif
                            </span>
                            <h3 class="mb-0 mt-3">
                                <p class="text-dark card-title">{{$listing->title}}</p>
                            </h3>
                            <h3 class="font-weight-bold mt-3">
                                {{$listing->price}} euro
                            </h3>
                            <div class="text-muted mb-3"
                                 style="font-family: Baghdad">{{$listing->created_at->diffForHumans()}}</div>
                            <p class="card-text mb-auto">{{$listing->description}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row d-flex mt-3">
            <div class="col-md-8 col-lg-8">
                <div class="card shadow-0 border" style="background-color: #f0f2f5;">
                    <div class="card-body p-4">

                        @auth

                            <div class="form-outline mb-4">
                                <input type="text" id="comment_body" class="form-control"
                                       placeholder="Scrie un comentariu..."/>
                                <button class="btn btn-primary mt-2 end-0"
                                        id="btn-comment"
                                        onclick="post_comment()">{{ __('Post') }}
                                </button>
                            </div>
                        @else
                            <p class="h4 card-body pt-1">{{ __('Comments') }}</p>
                        @endauth
                        <div id="comment-section">
                            @foreach($comments as $comment)
                                @include('components._partials.comment')
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        const listing = @JSON($listing);

        function show_phone() {
            $("#btn-phone").addClass('active').text(() => {
                return '{{$listing->phone}}'.replace(/(\d{4})(\d{3})(\d{3})/, "$1-$2-$3");
            });
        }

        $(document).ready(() => {
            listenComment();
        })


        // let comments;
        {{--function getComments() {--}}
        {{--    axios.get('{{ route('listings.comments.get',['listing'=>$listing]) }}')--}}
        {{--        .then((response) => {--}}
        {{--            comments = response.data--}}
        {{--        })--}}
        {{--        .catch((error) => {--}}
        {{--            console.log(error);--}}
        {{--        })--}}
        {{--}--}}
        function listenComment() {
            Echo.channel(`listing.${listing.id}`)
                .listen('.new.comment', (comment) => {
                    console.log("consolec");
                    $('#comment-section').prepend(comment.html);
                    $('#comment_body').val("");
                })
        }
        @auth
        function post_comment() {
            if (document.getElementById("comment_body").value === '') {
                return
            }
            axios
                .post('{{ route('listings.comments.post',['listing'=>$listing]) }}', {
                    body: document.getElementById("comment_body").value,
                    user_id: {{Auth::user()->id}},
                })
                .then(() => {
                        const comment_section = document.querySelector('#comment-section');
                        const card = document.createElement('div');
                        card.className = 'card mb-4';
                        comment_section.insertBefore(card, comment_section.childNodes[0]);
                        const card_body = document.createElement('div');
                        card_body.className = 'card-body';
                        card.appendChild(card_body);
                        const p = document.createElement('p');
                        p.innerHTML = document.getElementById("comment_body").value;
                        card_body.appendChild(p);
                        const flex = document.createElement('div');
                        flex.className = 'd-flex justify-content-between';
                        card_body.appendChild(flex);
                        const flex_row = document.createElement('div');
                        flex_row.className = 'd-flex flex-row align-items-center';
                        flex.appendChild(flex_row);
                        const img_row = document.createElement('img');
                        img_row.className = 'd-flex flex-row align-items-center';
                        img_row.style.width = "25px";
                        img_row.style.height = "25px";
                        img_row.src = "https://eu.ui-avatars.com/api/?name=" + "{{Auth::user()->name}}";
                        flex_row.appendChild(img_row);
                        const name_row = document.createElement('p');
                        name_row.className = 'small mb-0 ms-2';
                        name_row.innerHTML = "{{Auth::user()->name}}";
                        flex_row.appendChild(name_row);
                        const time_row = document.createElement('p');
                        time_row.className = 'small text-muted mb-0 end-0 position-absolute p-3';
                        time_row.innerHTML = "{{Carbon\Carbon::now()->diffForHumans()}}";
                        flex_row.appendChild(time_row);
                })
                .catch((error) => {
                    console.log(error);
                })
        }
        @endauth

    </script>
@endpush
