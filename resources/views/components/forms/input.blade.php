@props([
    'type' => 'text',
    'class' => 'form-control',
    'placeholder' => '',
    'name' => '',
    'value' => '',
    'required' => 'required'
])
<input type="{{ $type }}" class="{{ $class }}" placeholder="{{ $placeholder }}" name="{{ $name }}" value="{{ $value }}" required="{{ $required }}"/>
