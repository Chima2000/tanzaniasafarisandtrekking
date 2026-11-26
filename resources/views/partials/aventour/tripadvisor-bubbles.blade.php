@php
    $rating = (float) ($rating ?? 5);
    $size = $size ?? 'md';
    $showLabel = $showLabel ?? false;
    $label = $label ?? null;
    $fullBubbles = (int) floor($rating);
    $hasHalf = ($rating - $fullBubbles) >= 0.25 && ($rating - $fullBubbles) < 0.75;
    if (($rating - $fullBubbles) >= 0.75) {
        $fullBubbles++;
        $hasHalf = false;
    }
    $emptyBubbles = 5 - $fullBubbles - ($hasHalf ? 1 : 0);
@endphp
<span class="ta-bubbles ta-bubbles--{{ $size }}" aria-label="{{ number_format($rating, 1) }} out of 5 bubbles on Tripadvisor">
    @for ($i = 0; $i < $fullBubbles; $i++)
        <span class="ta-bubble ta-bubble--full" aria-hidden="true"></span>
    @endfor
    @if ($hasHalf)
        <span class="ta-bubble ta-bubble--half" aria-hidden="true"></span>
    @endif
    @for ($i = 0; $i < $emptyBubbles; $i++)
        <span class="ta-bubble ta-bubble--empty" aria-hidden="true"></span>
    @endfor
    @if ($showLabel && $label)
        <span class="ta-bubbles__label">{{ $label }}</span>
    @endif
</span>
