@aware([ 'for' => null, 'option'])

{{--
Livewire will select the radio button whose value matches that of the
public property as such it means the checked attribute no longer works.
NK::TD This may need to be altered to handle "laravel only" forms.
--}}


<label><input {{ $attributes }} name="{{ $for }}" type="radio"> {{ $option }} </label>
