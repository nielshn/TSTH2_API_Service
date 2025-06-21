@props(['label', 'name', 'value' => '', 'type' => 'text', 'required' => false])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <input type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $value) }}" class="form-control"
        {{ $required ? 'required' : '' }}>
</div>
