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

function clean($data): string
{
    if ($data === null) {
        return '';
    }
    $data = (string)$data;
    $data = trim($data);
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}


function getDefaultMagazine(): array
{
    return [
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
}

$magazine = getDefaultMagazine();

if ($type === 'magazine' && $mediaId) {
    $fetchedMagazine = $mediaController->getMagazineById($mediaId, $userId);

    if ($fetchedMagazine) {
        $magazine = array_merge($magazine, $fetchedMagazine);
    }

    // echo "<pre>";
    // print_r($magazine);
    // echo "</pre>";
}

?>

<?php if (!$type): ?>
    <div class="d-flex justify-content-center mb-4">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-dark" id="magazineBtn">
                <i class="fas fa-book me-2"></i> Magazine
            </button>
            <button type="button" class="btn btn-outline-dark" id="newspaperBtn">
                <i class="fas fa-newspaper me-2"></i> Newspaper
            </button>
        </div>
    </div>
<?php endif; ?>

<div id="formsContainer">
    <form method="POST" action="<?= $mediaId ? "/dashboards/media/magazine/update/$mediaId" : "/dashboards/media/magazine/insert" ?>" class="bg-white rounded shadow-sm p-4 mb-4 magazine-form" enctype="multipart/form-data">
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
                <label class="form-label">Category</label>
                <select name="category" class="form-select">
                    <option value="">Choose a category</option>
                    <?php
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

                    $selectedCategory = clean($magazine['category'] ?? '');

                    foreach ($categories as $cat) {
                        $selected = ($cat === $selectedCategory) ? 'selected' : '';
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
        action=""
        class="bg-white rounded shadow-sm p-4 mb-4 newspaper-form d-none"
        enctype="multipart/form-data">

        <h4 class="fw-bold mb-4">Newspaper Upload Information</h4>
        <div class="row g-3">

            <!-- Newspaper Basic Info -->
            <div class="col-md-6">
                <label class="form-label">Newspaper Title <span class="text-danger">*</span></label>
                <input type="text" name="newspaper_title" class="form-control" placeholder="e.g. Daily News" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Publisher <span class="text-danger">*</span></label>
                <input type="text" name="newspaper_publisher" class="form-control" placeholder="e.g. National Press Ltd." required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Language <span class="text-danger">*</span></label>
                <select class="form-select" name="newspaper_language" required>
                    <option value="">Choose language</option>
                    <?php
                    $languagesList = [
                        "Afrikaans",
                        "English",
                        "IsiNdebele",
                        "IsiXhosa",
                        "IsiZulu",
                        "Sesotho",
                        "Sesotho sa Leboa",
                        "Setswana",
                        "SiSwati",
                        "Tshivenda",
                        "Xitsonga"
                    ];
                    foreach ($languagesList as $lang) {
                        echo "<option value=\"$lang\">$lang</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Edition <span class="text-danger">*</span></label>
                <input type="text" name="newspaper_edition" class="form-control" placeholder="e.g. Morning Edition, Evening Edition" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Publication Date <span class="text-danger">*</span></label>
                <input type="date" name="newspaper_date" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Price <span class="text-danger">*</span></label>
                <input type="number" name="newspaper_price" class="form-control" placeholder="e.g. 5.00" step="0.01" required>
            </div>

            <!-- File Uploads -->
            <div class="col-md-6">
                <label class="form-label">Front Page Image</label>
                <input type="file" name="newspaper_cover_image" class="form-control" accept="image/*">
            </div>

            <div class="col-md-6">
                <label class="form-label">Full Newspaper File (PDF)</label>
                <input type="file" name="newspaper_file" class="form-control" accept=".pdf">
            </div>

            <!-- Description -->
            <div class="col-12">
                <label class="form-label">Description <small class="text-muted">(Max 600 characters)</small></label>
                <textarea name="newspaper_description" class="form-control" rows="4" maxlength="600" placeholder="Brief summary of the newspaper..."></textarea>
            </div>

            <div class="d-flex gap-2 mt-4">
                <!-- <div class="text-end">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i> Save Magazine
            </button>
        </div> -->

                <div class="text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane me-2"></i> Publish Newspapers
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

        // Magazine button click handler
        magazineBtn.addEventListener('click', function() {
            magazineBtn.classList.remove('btn-outline-dark');
            magazineBtn.classList.add('btn-dark', 'active');
            newspaperBtn.classList.remove('btn-dark', 'active');
            newspaperBtn.classList.add('btn-outline-dark');

            magazineForm.classList.remove('d-none');
            newspaperForm.classList.add('d-none');
        });

        // Newspaper button click handler
        newspaperBtn.addEventListener('click', function() {
            newspaperBtn.classList.remove('btn-outline-dark');
            newspaperBtn.classList.add('btn-dark', 'active');
            magazineBtn.classList.remove('btn-dark', 'active');
            magazineBtn.classList.add('btn-outline-dark');

            newspaperForm.classList.remove('d-none');
            magazineForm.classList.add('d-none');
        });
    });
</script>