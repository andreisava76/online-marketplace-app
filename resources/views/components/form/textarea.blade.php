@props(['name','id'])

<div class="mb-3">
    <label for="{{$name}}">
        {{ucwords($id)}}
    </label>
    <textarea class="form-control"
              name="{{$name}}"
              id="{{$name}}"
              {{$attributes}}
              required>{{ $slot ?? old($name) }}</textarea>
    @error($name)
    <small class="text-danger">{{ $message }}</small>
    @enderror
</div>
