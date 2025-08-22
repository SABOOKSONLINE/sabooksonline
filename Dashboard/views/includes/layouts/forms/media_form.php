<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../../../../database/connection.php";
require_once __DIR__ . "/../../../../models/MediaModel.php";
require_once __DIR__ . "/../../../../controllers/MediaController.php";

$action = $_GET['action'] ?? '';
$type = $_GET['type'] ?? '';
$mediaId = $_GET['id'] ?? '';

$mediaController = new MediaController($conn);
$magazine = [];
$newspaper = [];

function clean($data): string
{
    if ($data === null) {
        return '';
    }
    $data = (string)$data;
    $data = trim($data);
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Initialize default values
$magazine = [
    'public_key' => '',
    'title' => '',
    'editor' => '',
    'category' => '',
    'issn' => '',
    'price' => '',
    'frequency' => '',
    'language' => '',
    'country' => '',
    'publish_date' => '',
    'description' => '',
    'cover_image_path' => '',
    'pdf_path' => ''
];

$newspaper = [
    'public_key' => '',
    'title' => '',
    'description' => '',
    'cover_image_path' => '',
    'pdf_path' => '',
    'category' => '',
    'price' => '',
    'publish_date' => ''
];

$categories = [
    "News & Politics",
    "Business & Finance",
    "Technology & Gadgets",
    "Science & Nature",
    "Health & Fitness",
    "Lifestyle & Culture",
    "Travel & Adventure",
    "Fashion & Beauty",
    "Food & Cooking",
    "Sports & Recreation",
    "Arts & Entertainment",
    "Photography & Design",
    "Education & Learning",
    "Parenting & Family",
    "Automotive & Motorcycles",
    "Music & Performing Arts",
    "Gaming & Comics",
    "Hobbies & DIY",
    "History & Literature",
    "Environmental & Sustainability"
];

// Fetch data based on media type
if ($type === 'magazine' && $mediaId) {
    $fetchedMagazine = $mediaController->getMagazineById($mediaId, $userId);
    if ($fetchedMagazine) {
        $magazine = array_merge($magazine, $fetchedMagazine);
    }
} elseif ($type === 'newspaper' && $mediaId) {
    $fetchedNewspaper = $mediaController->getNewspaperById($mediaId, $userId);
    if ($fetchedNewspaper) {
        $newspaper = array_merge($newspaper, $fetchedNewspaper);
    }
}

// Determine initial form visibility
$magazineFormClass = ($type === 'newspaper') ? 'd-none' : '';
$newspaperFormClass = ($type === 'newspaper') ? '' : 'd-none';

// If no type is specified, default to magazine form visible
if (!$type) {
    $magazineFormClass = '';
    $newspaperFormClass = 'd-none';
}

// Set button styles based on active form
$magazineBtnClass = ($type !== 'newspaper') ? 'btn-dark' : 'btn-outline-dark';
$newspaperBtnClass = ($type === 'newspaper') ? 'btn-dark' : 'btn-outline-dark';
?>

<?php if (!$type): ?>
    <div class="d-flex justify-content-center mb-4">
        <div class="btn-group" role="group">
            <button type="button" class="btn <?= $magazineBtnClass ?>" id="magazineBtn">
                <i class="fas fa-book me-2"></i> Magazine
            </button>
            <button type="button" class="btn <?= $newspaperBtnClass ?>" id="newspaperBtn">
                <i class="fas fa-newspaper me-2"></i> Newspaper
            </button>
        </div>
    </div>
<?php endif; ?>

<div id="formsContainer">
    <form method="POST"
        action="<?= $mediaId ? "/dashboards/media/magazine/update/$mediaId" : "/dashboards/media/magazine/insert" ?>"
        class="bg-white rounded shadow-sm p-4 mb-4 magazine-form <?= $magazineFormClass ?>"
        enctype="multipart/form-data">
        <h4 class="fw-bold mb-4">Basic Magazine Information</h4>
        <div class="row g-3">

            <input type="text" name="publisher_id" class="form-control" value="<?= $userId ?>" hidden required>
            <input type="text" name="public_key" class="form-control" value="<?= $magazine['public_key'] ?>" hidden>

            <!-- Magazine Title -->
            <div class="col-md-12">
                <label class="form-label">Magazine Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control" placeholder="e.g. Tech Today" value="<?= clean($magazine['title']) ?>" required>
            </div>

            <!-- Magazine Editor -->
            <div class="col-md-6">
                <label class="form-label">Magazine Editor <span class="text-danger">*</span></label>
                <input type="text" name="editor" class="form-control" placeholder="e.g. Lindiwe Zwane" value="<?= clean($magazine['editor']) ?>" required>
            </div>

            <!-- Category -->
            <div class="col-md-6">
                <label class="form-label">Category <span class="text-danger">*</span></label>
                <select name="category" class="form-select" required>
                    <option value="">Choose a category</option>
                    <?php
                    // FIX: Changed from $newspaper to $magazine
                    $selectedCategory = $magazine['category'] ?? '';

                    foreach ($categories as $cat) {
                        $selected = (strcasecmp(trim($cat), trim($selectedCategory)) === 0) ? 'selected' : '';
                        echo "<option value=\"" . htmlspecialchars($cat) . "\" $selected>" .
                            htmlspecialchars($cat) . "</option>";
                    }
                    ?>
                </select>
            </div>


            <!-- ISSN -->
            <div class="col-md-6">
                <label class="form-label">ISSN Number</label>
                <input type="text" name="issn" class="form-control" placeholder="e.g. 1234-5678" value="<?= clean($magazine['issn']) ?>">
            </div>

            <!-- Price -->
            <div class="col-md-6">
                <label class="form-label">Price <span class="text-secondary">(ZAR)</span></label>
                <input type="text" name="price" class="form-control" placeholder="e.g. 19" value="<?= clean($magazine['price']) ?>">
            </div>

            <!-- Frequency -->
            <div class="col-md-6">
                <label class="form-label">Frequency <span class="text-danger">*</span></label>
                <select class="form-select" name="frequency" required>
                    <option value="">Choose frequency</option>
                    <?php
                    $frequencies = [
                        "weekly" => "Weekly",
                        "biweekly" => "Biweekly",
                        "monthly" => "Monthly",
                        "bimonthly" => "Bimonthly",
                        "quarterly" => "Quarterly",
                        "annual" => "Annual"
                    ];

                    $currentFrequency = clean($magazine['frequency'] ?? '');

                    foreach ($frequencies as $value => $label) {
                        $selected = ($value == $currentFrequency) ? 'selected' : '';
                        echo "<option value=\"$value\" $selected>$label</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Language -->
            <div class="col-md-6">
                <label class="form-label">Language</label>
                <select name="language" class="form-select">
                    <option value="">Choose a language</option>
                    <?php
                    $languages = [
                        "Afrikaans",
                        "English",
                        "isiZulu",
                        "isiXhosa",
                        "Sesotho",
                        "Setswana",
                        "Sepedi",
                        "Xitsonga",
                        "siSwati",
                        "Tshivenda",
                        "isiNdebele"
                    ];

                    $selectedLanguage = clean($magazine['language']) ?? '';

                    foreach ($languages as $lang) {
                        $safeLang = htmlspecialchars($lang, ENT_QUOTES);
                        $selected = ($lang === $selectedLanguage) ? 'selected' : '';
                        echo "<option value=\"$safeLang\" $selected>$safeLang</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Country -->
            <div class="col-md-6">
                <label class="form-label">Country</label>
                <input type="text" name="country" class="form-control" placeholder="e.g. South Africa" value="<?= clean($magazine['country']) ?>">
            </div>

            <!-- Publish Date -->
            <div class="col-md-6">
                <label class="form-label">Publish Date</label>
                <input type="date" name="publish_date" class="form-control" value="<?= clean($magazine['publish_date']) ?>">
            </div>

            <!-- Description -->
            <div class="col-12">
                <label class="form-label">Description <small class="text-muted">(Max 600 characters)</small></label>
                <textarea name="description" class="form-control" rows="4" maxlength="600" placeholder="Brief summary of the magazine..."><?= clean($magazine['description']) ?></textarea>
            </div>

            <!-- Cover Image -->
            <div class="col-md-6">
                <label class="form-label">Cover Image</label>
                <input type="file" name="cover" class="form-control">
                <input type="text" name="existing_cover" class="form-control" value="<?= $magazine['cover_image_path'] ?>" hidden>
                <?php if (!empty($magazine['cover_image_path'])): ?>
                    <div class="mt-2">
                        <small class="text-muted">Current Cover:</small><br>
                        <img src="/cms-data/magazine/covers/<?= $magazine['cover_image_path'] ?>" class="img-thumbnail" style="max-height: 150px;">
                    </div>
                <?php endif; ?>
            </div>

            <!-- PDF Upload -->
            <div class="col-md-6">
                <label class="form-label">PDF Upload</label>
                <input type="file" name="pdf" class="form-control">
                <input type="text" name="existing_pdf" class="form-control" value="<?= $magazine['cover_image_path'] ?>" hidden>
                <?php if (!empty($magazine['pdf_path'])): ?>
                    <div class="mt-2">
                        <small class="text-muted">Current PDF:</small><br>
                        <a href="/cms-data/magazine/pdfs/<?= $magazine['pdf_path'] ?>" target="_blank" class="btn btn-outline-primary btn-sm">View Current PDF</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <!-- <div class="text-end">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i> Save Magazine
            </button>
        </div> -->

            <div class="text-end">
                <button type="submit" class="btn btn-success">
                    <?php if (empty($mediaId)): ?>
                        <i class="fas fa-paper-plane me-2"></i> Publish Magazine
                    <?php else: ?>
                        <i class="fas fa-paper-plane me-2"></i> Save Magazine
                    <?php endif; ?>
                </button>
            </div>
        </div>
    </form>

    <form method="POST"
        action="<?= $mediaId ? "/dashboards/media/newspaper/update/$mediaId" : "/dashboards/media/newspaper/insert" ?>"
        class="bg-white rounded shadow-sm p-4 mb-4 newspaper-form <?= $newspaperFormClass ?>"
        enctype="multipart/form-data">

        <h4 class="fw-bold mb-4">Newspaper Information</h4>
        <div class="row g-3">

            <!-- Hidden Fields -->
            <input type="hidden" name="publisher_id" value="<?= $userId ?>">
            <input type="hidden" name="public_key" value="<?= $newspaper['public_key'] ?>">

            <!-- Newspaper Title -->
            <div class="col-md-6">
                <label class="form-label">Newspaper Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control" placeholder="e.g. Daily News"
                    value="<?= clean($newspaper['title']) ?>" required>
            </div>

            <!-- Category -->
            <div class="col-md-6">
                <label class="form-label">Category <span class="text-danger">*</span></label>
                <select name="category" class="form-select" required>
                    <option value="">Choose a category</option>
                    <?php
                    $rawCategory = $newspaper['category'] ?? '';
                    foreach ($categories as $cat) {
                        $selected = (strcasecmp(trim($cat), trim($rawCategory)) === 0) ? 'selected' : '';
                        $escapedCat = htmlspecialchars($cat, ENT_QUOTES, 'UTF-8');
                        echo "<option value=\"$escapedCat\" $selected>$escapedCat</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Price -->
            <div class="col-md-6">
                <label class="form-label">Price <span class="text-secondary">(ZAR)</span></label>
                <input type="number" name="price" class="form-control" placeholder="e.g. 15.00"
                    step="0.01" value="<?= clean($newspaper['price']) ?>">
            </div>

            <!-- Publish Date -->
            <div class="col-md-6">
                <label class="form-label">Publication Date <span class="text-danger">*</span></label>
                <input type="date" name="publish_date" class="form-control"
                    value="<?= clean($newspaper['publish_date']) ?>" required>
            </div>

            <!-- Description -->
            <div class="col-12">
                <label class="form-label">Description <small class="text-muted">(Max 600 characters)</small></label>
                <textarea name="description" class="form-control" rows="4" maxlength="600"
                    placeholder="Brief summary of the newspaper..."><?= clean($newspaper['description']) ?></textarea>
            </div>

            <!-- Cover Image -->
            <div class="col-md-6">
                <label class="form-label">Front Page Image</label>
                <input type="file" name="cover" class="form-control">
                <input type="hidden" name="existing_cover" value="<?= $newspaper['cover_image_path'] ?>">
                <?php if (!empty($newspaper['cover_image_path'])): ?>
                    <div class="mt-2">
                        <small class="text-muted">Current Cover:</small><br>
                        <img src="/cms-data/newspaper/covers/<?= $newspaper['cover_image_path'] ?>"
                            class="img-thumbnail" style="max-height: 150px;">
                    </div>
                <?php endif; ?>
            </div>

            <!-- PDF Upload -->
            <div class="col-md-6">
                <label class="form-label">PDF File <span class="text-danger">*</span></label>
                <input type="file" name="pdf" class="form-control" <?= $mediaId ? "" : "required" ?>>
                <input type="hidden" name="existing_pdf" value="<?= $newspaper['pdf_path'] ?>">
                <?php if (!empty($newspaper['pdf_path'])): ?>
                    <div class="mt-2">
                        <small class="text-muted">Current PDF:</small><br>
                        <a href="/cms-data/newspaper/pdfs/<?= $newspaper['pdf_path'] ?>" target="_blank"
                            class="btn btn-outline-primary btn-sm">View Current PDF</a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="d-flex gap-2 mt-4">
                <div class="text-end">
                    <button type="submit" class="btn btn-success">
                        <?php if (empty($mediaId)): ?>
                            <i class="fas fa-paper-plane me-2"></i> Publish Newspaper
                        <?php else: ?>
                            <i class="fas fa-paper-plane me-2"></i> Save Newspaper
                        <?php endif; ?>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const magazineBtn = document.getElementById('magazineBtn');
        const newspaperBtn = document.getElementById('newspaperBtn');
        const magazineForm = document.querySelector('.magazine-form');
        const newspaperForm = document.querySelector('.newspaper-form');

        // Only add event listeners if buttons exist (when no type specified)
        if (magazineBtn && newspaperBtn) {
            // Magazine button click handler
            magazineBtn.addEventListener('click', function() {
                magazineBtn.classList.remove('btn-outline-dark');
                magazineBtn.classList.add('btn-dark');
                newspaperBtn.classList.remove('btn-dark');
                newspaperBtn.classList.add('btn-outline-dark');

                magazineForm.classList.remove('d-none');
                newspaperForm.classList.add('d-none');

                // Update URL parameter
                const url = new URL(window.location);
                url.searchParams.set('type', 'magazine');
                window.history.replaceState(null, null, url);
            });

            // Newspaper button click handler
            newspaperBtn.addEventListener('click', function() {
                newspaperBtn.classList.remove('btn-outline-dark');
                newspaperBtn.classList.add('btn-dark');
                magazineBtn.classList.remove('btn-dark');
                magazineBtn.classList.add('btn-outline-dark');

                newspaperForm.classList.remove('d-none');
                magazineForm.classList.add('d-none');

                // Update URL parameter
                const url = new URL(window.location);
                url.searchParams.set('type', 'newspaper');
                window.history.replaceState(null, null, url);
            });
        }

        // Set initial state based on URL parameter
        const urlParams = new URLSearchParams(window.location.search);
        const mediaType = urlParams.get('type');

        if (mediaType === 'newspaper') {
            if (newspaperBtn && magazineBtn) {
                newspaperBtn.classList.remove('btn-outline-dark');
                newspaperBtn.classList.add('btn-dark');
                magazineBtn.classList.remove('btn-dark');
                magazineBtn.classList.add('btn-outline-dark');
            }

            if (newspaperForm) newspaperForm.classList.remove('d-none');
            if (magazineForm) magazineForm.classList.add('d-none');
        } else {
            if (magazineBtn && newspaperBtn) {
                magazineBtn.classList.remove('btn-outline-dark');
                magazineBtn.classList.add('btn-dark');
                newspaperBtn.classList.remove('btn-dark');
                newspaperBtn.classList.add('btn-outline-dark');
            }

            if (magazineForm) magazineForm.classList.remove('d-none');
            if (newspaperForm) newspaperForm.classList.add('d-none');
        }
    });
</script>