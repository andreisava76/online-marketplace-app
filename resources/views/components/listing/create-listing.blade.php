@extends("layouts.app")
@section("content")
    <div class="px-5 py-5 mx-auto w-25">
        <div class="card">
            <div class="card-header">{{ __('New listing') }}</div>

            <div class="card-body">
                <form method="POST" action="{{ route('listings.create') }}" name="listing-form" id="listing-form"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="card mb-3 mt-1">
                        <div class="card-body">
                            <x-form.input name="title" type="text" autofocus
                                          placeholder="{{ __('e.g. Dacia Duster for sale') }}">{{ __('Add title') }}
                            </x-form.input>

                            <label for="category_id">{{ __('Category') }}</label>
                            <select class="form-select" name="category_id" id="category_id" required>
                                @foreach ($categories as $category)
                                    <option
                                        value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}
                                    >{{ ucwords($category->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="image_upload">
                                    <p class="btn btn-primary mb-0">{{ __('Select images') }}</p>
                                </label>
                                <input class="form-control" name="image_upload[]"
                                       type="file" accept=".jpeg,.jpg,.png"
                                       multiple style="display: none"
                                       id="image_upload" required>
                                <div id="img-preview" class="card p-2">
                                    <div class="card-body">
                                        <p class="text-muted small text-center">{{ __('Select maximum 6 images (JPG, PNG)') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <x-form.textarea rows="4" cols="50" placeholder="{{ __('Listing details') }}"
                                             name="description" id="descriere" type="text"></x-form.textarea>

                            <label for="input-group">{{ __('Price') }}</label>
                            <div class="input-group mb-3">
                                <input type="text"
                                       class="form-control"
                                       name="price_left"
                                       style="width:50%"
                                       maxlength="15"
                                />
                                <span class="input-group-text">,</span>
                                <input type="text"
                                       class="form-control"
                                       name="price_right"
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
                                       autocomplete="off"
                                       checked>
                                <label class="btn btn-outline-primary" for="new">{{ __('New') }}</label>

                                <input type="radio" class="btn-check" name="condition" value="used" id="used"
                                       autocomplete="off">
                                <label class="btn btn-outline-primary" for="used">{{ __('Used') }}</label>
                            </div>

                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="fw-semibold mb-2">{{ __('Contact info') }}</h5>
                            <x-form.input name="email" type="email" autocomplete="email">{{ __('Email adress') }}
                            </x-form.input>
                            <x-form.input name="phone" type="text" autocomplete="on">{{ __('Telephone number') }}
                            </x-form.input>

                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-1" name="submit" id="submit">{{ __('Submit') }}</button>
                </form>
            </div>
        </div>
    </div>
    <script type="module" src='{{asset('js/check-max-img.js')}}'></script>
    <script type="module" src='{{asset('js/img-preview.js')}}'></script>
@endsection
