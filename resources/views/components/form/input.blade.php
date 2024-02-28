@props(['name'])

<div class="mb-3">
    <label for="{{$name}}">
        {{$slot}}
    </label>
    <input class="form-control"
           name="{{$name}}"
           id="{{$name}}"
           {{$attributes}}
           {{$attributes(['value'=>old($name)])}}
           required>
    @error($name)
    <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

