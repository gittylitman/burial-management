
@php
$url = config('services.gov.url_maps').session('burial_city').",".session('cemetery') ;
@endphp

<div>
    <a href="{{ $url }}" target="_blank">לחץ כאן</a>
</div>