<?php
foreach ($providers as $provider) {
    echo '
    <div class="col-md-4 d-flex mb-3">
        <div class="profile-card w-100 d-flex flex-column h-100">
            <div class="row flex-grow-1">
                <div class="col-4">
                    <div class="ratio ratio-1x1">
                        <img class="img-fluid object-fit-cover"
                            src="https://sabooksonline.co.za/cms-data/profile-images/' . $provider['ADMIN_PROFILE_IMAGE'] . '"
                            alt="' . $provider['ADMIN_PROFILE_IMAGE'] . ' profile image">
                    </div>
                </div>
                <div class="col-8">
                    <h5 class="fw-bold">' . (strlen($provider["ADMIN_NAME"]) > 30 ? substr($provider["ADMIN_NAME"], 0, 30) . "..." : $provider["ADMIN_NAME"]) . '</h5>
                    <small>' . $provider["SERVICE"] . '</small>
                    <hr />
                    <small class="text-muted mb-3">
                        <i class="fas fa-location-dot"></i> 
                        <em>' . $provider["ADMIN_GOOGLE"] . '</em>
                    </small>
                    <p>' . (strlen($provider["ADMIN_BIO"]) > 50 ? substr($provider["ADMIN_BIO"], 0, 50) . "..." : $provider["ADMIN_BIO"]) . '</p>
                </div>
            </div>
            <div class="mt-auto">
                <a href="creators/creator/' . $provider['USERID'] . '" class="btn btn-outline-red w-100">More Details</a>
            </div>
        </div>
    </div>
    ';
}
