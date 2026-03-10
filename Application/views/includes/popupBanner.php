<?php
// $uri = $_SERVER["REQUEST_URI"];
// $popupBanners = [];

// if (isset($homeController)) {
//     $bannersData = $homeController->banners();
//     $popupBanners = $bannersData['banners']['popup_banners'] ?? [];
// }

// if ($popupBanners):
//     foreach ($popupBanners as $banner):
?>

<div class="popup-banner-bg hide-banner-bg"></div>
<div class="popup-banner hide-banner">
    <div class="popup-banner-container">
        <div class="popup-banner-cover">
            <img src="/public/images/banners/The Afrocentric Agenda.jpeg" alt="The Afrocentric Agenda" class="popup-banner-img-bg">
            <img src="/public/images/banners/The Afrocentric Agenda.jpeg" alt="The Afrocentric Agenda" class="popup-banner-img">
        </div>
        <div class="popup-banner-info">
            <div>
                <span class="bk-tag popup-sponsored">Event</span>
            </div>
            <span class="popup-tag">The Afrocentric Agenda · Event Series</span>
            <h1 class="typo-heading">What It Really Means to "Publish African"</h1>
            <i class="popup-tag text-lowercase text-capitalize">Presented by: SA Books Online & UJ Press</i>
            <p class="popup-desc">
                The Journey of UJ Press — in conversation with <strong>Wikus Van Zyl</strong>, UJ Press Director, and <strong>Dr JJ Klaas</strong>, author of <em>Triangle of One Hundred Years Wars</em>.
            </p>
            <div class="popup-subtext">
                <p>📅 23 March 2026 &nbsp;·&nbsp; 🕓 16:00 &nbsp;·&nbsp; University of Johannesburg Library, Nadine Gordimer Auditorium (Level 5), Auckland Park Kingsway Campus</p>
            </div>
            <div>
                <a href="https://zoom.us/webinar/register/WN_Uhmnbry7SMGaVm0Ex_j2kg" target="_blank" class="btn btn-white">Register Now <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
    <button type="button" id="close-popup-banner" aria-label="Close banner" class="btn-close-icon">
        <i class="fas fa-times"></i>
    </button>
</div>

<?php
//     endforeach;
// endif;
?>