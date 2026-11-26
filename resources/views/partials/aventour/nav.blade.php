@php
    $current = $current ?? '';
@endphp
<ul class="navigation clearfix">
    <li @class(['current' => $current === 'safari'])><a href="{{ route('packages.safari') }}">Safari</a></li>
    <li @class(['current' => $current === 'kilimanjaro'])><a href="{{ route('packages.trekking') }}">Kilimanjaro Treks</a></li>
    <li @class(['current' => $current === 'day-trips'])><a href="{{ route('packages.day-trips') }}">Day Trips</a></li>
    <li @class(['current' => $current === 'destinations'])><a href="{{ route('destinations.index') }}">Destinations</a></li>
    <li @class(['current' => $current === 'blog'])><a href="{{ route('blog') }}">Blog</a></li>
    <li @class(['current' => $current === 'about'])><a href="{{ route('about') }}">About Us</a></li>
    <li @class(['current' => $current === 'contact'])><a href="{{ route('contact') }}">Contact Us</a></li>
</ul>
