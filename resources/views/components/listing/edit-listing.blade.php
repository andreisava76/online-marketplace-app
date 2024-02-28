@extends("layouts.app")
@section("content")
    <div class="px-5 py-5 mx-auto w-25">
        <div class="card">
            <div class="card-header">{{ __('Edit the listing') }}</div>

            <div class="card-body">
                <form method="POST" action="{{ route('listings.update',$listing) }}" name="listing-form"
                      id="listing-form"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="card mb-3 mt-1">
                        <div class="card-body">
                            <x-form.input name="title" type="text" autofocus value="{{$listing->title}}"
                                          placeholder="{{ __('e.g. Dacia Duster for sale') }}">{{ __('Title') }}
                            </x-form.input>

                            <label for="category_id">{{ __('Category') }}</label>
                            <select class="form-select" name="category_id" id="category_id" required>
                                @foreach (\App\Models\Category::all() as $category)
                                    <option
                                        value="{{ $category->id }}"
                                        {{ old('category_id',$listing->category_id) == $category->id ? 'selected' : '' }}
                                    >{{ ucwords($category->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="image_upload">
                                    <p class="btn btn-primary mb-0">{{ __('Select the images') }}</p>
                                </label>
                                <input class="form-control" name="image_upload[]"
                                       type="file" accept=".jpeg,.jpg,.png"
                                       multiple style="display: none"
                                       id="image_upload">
                                <div id="img-preview" class="card p-2">
                                    <ul class="list-unstyled d-flex flex-wrap justify-content-center">
                                        @foreach($images as $image)
                                            <li class="d-inline">
                                                <img src="{{asset('storage/files/'.$image->image)}}"
                                                     class="img-fluid d-block rounded-3 m-1 border shadow-sm"
                                                     style="height: 90px"
                                                     alt="">
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <x-form.textarea rows="4" cols="50" placeholder="{{ __('Listing details') }}"
                                             name="description" id="descriere"
                                             type="text">{{old('description',$listing->description)}}</x-form.textarea>

                            <label for="input-group">{{ __('Price') }}</label>
                            <div class="input-group mb-3">
                                <input type="text"
                                       class="form-control"
                                       name="price_left"
                                       style="width:50%"
                                       value="{{substr($listing->price, 0, -3)}}"
                                />
                                <span class="input-group-text">,</span>
                                <input type="text"
                                       class="form-control"
                                       name="price_right"
                                       value="{{substr($listing->price, -2)}}"
                                       maxlength="2"
                                />

                            </div>
                            @error('price')
                            <div class="row mt-0 mb-3">
                                <small class="text-danger col">{{ $message }}</small>
                            </div>
                            @enderror
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" name="condition" value="new" id="new"
                                       {{ $listing->condition==='new' ? 'checked' : '' }}
                                       autocomplete="off">
                                <label class="btn btn-outline-primary" for="new">{{ __('New') }}</label>

                                <input type="radio" class="btn-check" name="condition" value="used" id="used"
                                       {{ $listing->condition==='used' ? 'checked' : '' }}
                                       autocomplete="off">
                                <label class="btn btn-outline-primary" for="used">{{ __('Used') }}</label>
                            </div>

                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="fw-semibold mb-2">{{ __('Contact info') }}</h5>
                            <x-form.input name="email" type="email" autocomplete="email" value="{{$listing->email}}">
                                {{ __('Email adress') }}
                            </x-form.input>
                            <x-form.input name="phone" type="text" autocomplete="on" value="{{$listing->phone}}">
                                {{ __('Phone number') }}
                            </x-form.input>

                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-1" name="submit">{{ __('Submit') }}</button>
                </form>
            </div>
        </div>
    </div>

    {{--    <script>--}}
    {{--        Dropzone.autoDiscover = false;--}}
    {{--        let myDropzone = new Dropzone('div#dropzoneDragArea', {--}}
    {{--            paramName: "file",--}}
    {{--            url: {{storage_path("app/public/files")}},--}}
    {{--            previewsContainer: 'div.dropzone-previews',--}}
    {{--            addRemoveLinks: true,--}}
    {{--            autoProcessQueue: false,--}}
    {{--            parallelUploads: 100,--}}
    {{--            maxFiles: 100,--}}
    {{--            thumbnailWidth: 300,--}}
    {{--            acceptedFiles: ".jpeg,.jpg,.png",--}}
    {{--            init: function () {--}}
    {{--                let myDropzone = this;--}}
    {{--                //form submission code goes here--}}
    {{--                $("form[name='listing-form']").submit(function (event) {--}}
    {{--                    //Make sure that the form isn't actully being sent.--}}
    {{--                    event.preventDefault();--}}
    {{--                    URL = $("#listing-form").attr('action');--}}
    {{--                    let formData = $('#listing-form').serialize();--}}
    {{--                    $.ajax({--}}
    {{--                        type: 'POST',--}}
    {{--                        url: URL,--}}
    {{--                        data: formData,--}}
    {{--                        success: function (result) {--}}
    {{--                            if (result.status === "success") {--}}
    {{--                                myDropzone.processQueue();--}}
    {{--                            } else {--}}
    {{--                                console.log("error");--}}
    {{--                            }--}}
    {{--                        }--}}
    {{--                    })--}}
    {{--                })--}}
    {{--            }--}}
    {{--        })--}}

    {{--    </script>--}}
    {{--    <script>--}}
    {{--        const constants = {--}}
    {{--            url : @JSON(route("listings.create"))--}}
    {{--        }--}}

    {{--    </script>--}}
    {{--    <script src='{{mix('js/dropzone.js')}}'></script>--}}
@endsection
@push('js')
    <script src='{{asset('js/check-max-img.js')}}'></script>
    <script src='{{asset('js/img-preview.js')}}'></script>
@endpush
