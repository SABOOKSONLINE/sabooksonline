<?php
// Updated: Save button always enabled when updating academic books
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../../../../database/connection.php";
require_once __DIR__ . "/../../../../models/AcademicBookModel.php";
require_once __DIR__ . "/../../../../controllers/AcademicBookController.php";

$bookId = $_GET["id"] ?? null;

$academicBookController = new AcademicBookController($conn);
$book = [];

if ($bookId) {
    $book = $academicBookController->getBookById($bookId, $userId) ?? [];
}

// echo "<pre>";
// print_r($book);
// echo "</pre>";

function clean($data): string
{
    if ($data === null) {
        return '';
    }
    $data = (string)$data;
    $data = trim($data);
    return html_entity_decode($data, ENT_QUOTES, 'UTF-8');
}

function safeClean(array $arr, string $key, string $default = ''): string
{
    return isset($arr[$key]) ? clean($arr[$key]) : $default;
}

$publisher_id         = safeClean($book, "publisher_id");
$public_key           = safeClean($book, "public_key");
$title                = safeClean($book, "title");
$author               = safeClean($book, "author");
$editor               = safeClean($book, "editor");
$description          = safeClean($book, "description");
$subject              = safeClean($book, "subject");
$level                = safeClean($book, "level");
$language             = safeClean($book, "language");
$edition              = safeClean($book, "edition");
$pages                = safeClean($book, "pages");
$isbn                 = safeClean($book, "ISBN");
$publish_date         = safeClean($book, "publish_date");
$cover_image_path     = safeClean($book, "cover_image_path");

$pdf_path             = !empty($book["pdf_path"]) ? clean($book["pdf_path"]) : "";
$ebook_price          = !empty($book["ebook_price"]) ? clean($book["ebook_price"]) : "";
$physical_book_price  = !empty($book["physical_book_price"]) ? clean($book["physical_book_price"]) : "";
$link                 = !empty($book["link"]) ? clean($book["link"]) : "";


$subjects = [
    "Home Language",
    "First Additional Language",
    "Mathematics",
    "Life Skills",

    "Natural Sciences",
    "Technology",
    "Social Sciences (History & Geography)",
    "Economic Management Sciences",
    "Creative Arts",
    "Physical Education",

    "Mathematics",
    "Natural Sciences",
    "Technology",
    "Social Sciences",
    "Economic Management Sciences",
    "Creative Arts",
    "Life Orientation",

    "Mathematics",
    "Mathematical Literacy",
    "Physical Sciences",
    "Life Sciences",
    "Geography",
    "History",
    "Accounting",
    "Business Studies",
    "Economics",
    "Computer Applications Technology (CAT)",
    "Information Technology (IT)",
    "Engineering Graphics and Design (EGD)",
    "Tourism",
    "Hospitality Studies",
    "Consumer Studies",
    "Visual Arts",
    "Music",
    "Drama",
    "Life Orientation"
];


$academicLevel = [
    "Foundation Phase (Grade R-3)",
    "Intermediate Phase (Grade 4-6)",
    "Senior Phase (Grade 7-9)",
    "FET Phase (Grade 10-12)",
    "Undergraduate",
    "Postgraduate"
];

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

?>

<form method="POST"
    action="<?= $public_key ? "/dashboards/academic/book/update/$bookId" : "/dashboards/academic/book/insert" ?>"
    class="bg-white rounded shadow-sm p-4 mb-4"
    enctype="multipart/form-data">
    <h4 class="fw-bold mb-4">Basic Academic Book Information</h4>
    <div class="row g-3">

        <input type="text" name="publisher_id" class="form-control" value="<?= $userId ?>" hidden>
        <input type="text" name="public_key" class="form-control" value="<?= $public_key ?>" hidden>

        <!-- Book Title -->
        <div class="col-md-12">
            <label class="form-label">Book Title <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" placeholder="e.g. Mathematics Caps Foundation Phase" value="<?= $title ?>" required>
        </div>

        <!-- Book Author -->
        <div class="col-md-6">
            <label class="form-label">Book Author <span class="text-danger">*</span></label>
            <input type="text" name="author" class="form-control" placeholder="e.g. Lindiwe Zwane" value="<?= $author ?>" required>
        </div>

        <!-- Book Editor -->
        <div class="col-md-6">
            <label class="form-label">Book Editor <span class="text-danger">*</span></label>
            <input type="text" name="editor" class="form-control" placeholder="e.g. Lindiwe Zwane" value="<?= $editor ?>" required>
        </div>

        <!-- Book Description -->
        <div class="col-md-12">
            <label class="form-label">Book Description <small class="text-muted">(Max 600 characters)</small></label>
            <textarea name="description" class="form-control" rows="4" maxlength="600" placeholder="Brief summary of the book..." required><?= $description ?></textarea>
        </div>

        <!-- Book Subject -->
        <div class="col-md-6">
            <label class="form-label">Book Subject <span class="text-danger">*</span></label>
            <select name="subject" class="form-select" required>
                <option value="">Choose a Subject</option>
                <?php foreach ($subjects as $sub): ?>
                    <?php if ($sub === $subject): ?>
                        <option value="<?= $sub ?>" selected><?= $sub ?></option>
                    <?php else: ?>
                        <option value="<?= $sub ?>"><?= $sub ?></option>
                    <?php endif ?>
                <?php endforeach ?>
            </select>
        </div>

        <!-- Book Academic Level -->
        <div class="col-md-6">
            <label class="form-label">Academic Level <span class="text-danger">*</span></label>
            <select name="level" class="form-select" required>
                <option value="">Choose a Level</option>
                <?php foreach ($academicLevel as $lvl): ?>
                    <option value="<?= clean($lvl) ?>" <?= ($lvl === $level) ? 'selected' : '' ?>>
                        <?= clean($lvl) ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <!-- Book Language -->
        <div class="col-md-6">
            <label class="form-label">Book Language <span class="text-danger">*</span></label>
            <select name="language" class="form-select" required>
                <option value="">Choose a Language</option>
                <?php foreach ($languages as $lang): ?>
                    <option value="<?= clean($lang) ?>" <?= ($lang === $language) ? 'selected' : '' ?>>
                        <?= clean($lang) ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>


        <!-- Book Edition -->
        <div class="col-md-6">
            <label class="form-label">Book Edition</label>
            <input type="text" name="edition" class="form-control" placeholder="e.g. 1st Edition" value="<?= $edition ?>">
        </div>

        <!-- Book Pages -->
        <div class="col-md-6">
            <label class="form-label">Book Pages</label>
            <input type="number" name="pages" class="form-control" placeholder="e.g. 450" value="<?= $pages ?>">
        </div>

        <!-- Book ISBN -->
        <div class="col-md-6">
            <label class="form-label">ISBN</label>
            <input type="text" name="isbn" class="form-control" placeholder="e.g. 12345-69875-3" value="<?= $isbn ?>" required>
        </div>

        <!-- Book Cover file -->
        <div class="col-md-6">
            <label class="form-label">Upload Book Cover <span class="text-danger">*</span></label>
            <input type="file" name="cover" class="form-control" <?php echo empty($book) ? 'required' : ''; ?>>
            <input type="text" name="existing_cover" class="form-control" value="<?= $cover_image_path ?>" hidden>
            <?php if (!empty($cover_image_path)): ?>
                <div class="mt-2">
                    <small class="text-muted">Current Cover:</small><br>
                    <img src="/cms-data/academic/covers/<?= $cover_image_path ?>" class="img-thumbnail" style="max-height: 150px;">
                </div>
            <?php endif; ?>
        </div>

        <!-- Book Publish Date -->
        <div class="col-md-6">
            <label class="form-label">Publish Date <span class="text-danger">*</span></label>
            <input type="date" name="publish_date" class="form-control" value="<?= $publish_date ?>">
        </div>

        <hr class="my-4">

        <h4 class="fw-bold mb-2">Ebook Details</h4>

        <!-- Book PDF file -->
        <div class="col-md-6">
            <label class="form-label">Upload Ebook <small class="text-muted">(PDF)</small></label>
            <input type="file" name="pdf" class="form-control" accept="application/pdf">
            <input type="text" name="existing_pdf" id="existing_pdf" class="form-control" value="<?= $pdf_path ?>" hidden>
            <?php if (!empty($pdf_path)): ?>
                <div class="mt-2">
                    <small class="text-muted">Current PDF:</small><br>
                    <a href="/cms-data/academic/pdfs/<?= $pdf_path ?>" target="_blank"
                        class="btn btn-outline-primary btn-sm">View Current PDF</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Ebook Price -->
        <div class="col-md-6">
            <label class="form-label">Ebook Price <small class="text-muted">(ZAR)</small></label>
            <input type="number" name="ebook_price" class="form-control" id="ebook_price" placeholder="e.g. 89" value="<?= $ebook_price ?>">
        </div>

        <hr class="my-4">

        <h4 class="fw-bold mb-2">Physical Copy Details</h4>

        <!-- Physical Copy Link -->
        <div class="col-md-6">
            <label class="form-label">Physical Copy <small class="text-muted">(Link)</small></label>
            <input type="text" name="link" class="form-control" placeholder="e.g. http://www.amazon.co.za/books/purchase/23r342rte12312" value="<?= $link ?>">
        </div>

        <!-- Physical Copy Price -->
        <div class="col-md-6">
            <label class="form-label">Physical Copy Price <small class="text-muted">(ZAR)</small></label>
            <input type="number" name="physical_book_price" class="form-control" id="physical_book_price" placeholder="e.g. 89" value="<?= $physical_book_price ?>">
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        <div class="text-end">
            <button type="submit" class="btn btn-success" id="publish_button">
                <?php if ($public_key): ?>
                    <i class="fas fa-bookmark"></i> Save Book
                <?php else: ?>
                    <i class="fas fa-paper-plane me-2"></i> Publish Book
                <?php endif ?>
            </button>
        </div>
    </div>
</form>

<script>
    const ebookPrice = document.getElementById("ebook_price");
    const physicalPrice = document.getElementById("physical_book_price");
    const publishBookButton = document.getElementById("publish_button");
    // const existingCover = document.getElementById("existing_cover");
    // const existingPdf = document.getElementById("existing_pdf");

    const form = document.querySelector("form");
    const fields = form.querySelectorAll("input[required], textarea[required], select[required]");

    const disableSubmitButton = () => {
        publishBookButton.classList.remove("btn-success");
        publishBookButton.classList.add("btn-secondary");
        publishBookButton.disabled = true;
    }

    const enableSubmitButton = () => {
        publishBookButton.classList.remove("btn-secondary");
        publishBookButton.classList.add("btn-success");
        publishBookButton.disabled = false;
    }

    // Check if this is an update (public_key exists)
    const isUpdate = <?php echo $public_key ? 'true' : 'false'; ?>;

    const validateForm = () => {
        // If updating, always enable the button (can save without PDF)
        if (isUpdate) {
            enableSubmitButton();
            return;
        }

        // For new books, validate required fields and price
        let allRequiredFilled = true;
        fields.forEach(field => {
            if (!field.value.trim()) allRequiredFilled = false;
        });

        const priceFilled = ebookPrice.value.trim() || physicalPrice.value.trim();

        if (allRequiredFilled && priceFilled) {
            enableSubmitButton();
        } else {
            disableSubmitButton();
        }
    };

    // Initial validation
    validateForm();

    fields.forEach(field => field.addEventListener("input", validateForm));
    ebookPrice.addEventListener("input", validateForm);
    physicalPrice.addEventListener("input", validateForm);

    validateForm();
</script>