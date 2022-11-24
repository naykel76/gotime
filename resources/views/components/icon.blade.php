@props(['icon', 'text'])

   <svg {{ $attributes->merge(['class' => 'icon']) }}>
      <use xlink:href="/svg/naykel-ui-SVG-sprite.svg#{{ $icon }}"></use>
   </svg>

   @isset($text)
      <span>{{ $text }}</span>
   @endisset