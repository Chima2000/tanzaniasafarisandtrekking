@php
    $awardBg = $awardBg ?? '/aventour/assets/images/background/award-bg.jpg';
@endphp
<section class="award-section">
    <div class="bg-layer" style="background-image: url('{{ $awardBg }}');"></div>
    <div class="auto-container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-12 text-column">
                <div class="text-box">
                    <h2>Recognized for Excellence in Travel</h2>
                    <p>Celebrated by travelers and trusted worldwide.</p>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 award-column">
                <div class="award-content">
                    <ul class="award-list">
                        <li><img src="/aventour/assets/images/icons/award-1.png" alt=""></li>
                        <li><img src="/aventour/assets/images/icons/award-2.png" alt=""></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
