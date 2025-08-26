<?php
$subjects = [
    // Foundation Phase
    "Home Language",
    "First Additional Language",
    "Mathematics",
    "Life Skills",

    // Intermediate Phase
    "Natural Sciences",
    "Technology",
    "Social Sciences (History & Geography)",
    "Economic Management Sciences",
    "Creative Arts",
    "Physical Education",

    // Senior Phase
    "Mathematics",
    "Natural Sciences",
    "Technology",
    "Social Sciences",
    "Economic Management Sciences",
    "Creative Arts",
    "Life Orientation",

    // FET Phase (Grade 10-12)
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
    action="/dashboards/academic/book/insert"
    class="bg-white rounded shadow-sm p-4 mb-4 magazine-form <?= $magazineFormClass ?>"
    enctype="multipart/form-data">
    <h4 class="fw-bold mb-4">Basic Academic Book Information</h4>
    <div class="row g-3">

        <input type="text" name="publisher_id" class="form-control" value="<?= $userId ?>" hidden required>
        <input type="text" name="public_key" class="form-control" value="" hidden>

        <!-- Book Title -->
        <div class="col-md-12">
            <label class="form-label">Book Title <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" placeholder="e.g. Mathematics Caps Foundation Phase" value="" required>
        </div>

        <!-- Book Author -->
        <div class="col-md-6">
            <label class="form-label">Book Author <span class="text-danger">*</span></label>
            <input type="text" name="author" class="form-control" placeholder="e.g. Lindiwe Zwane" value="" required>
        </div>

        <!-- Book Editor -->
        <div class="col-md-6">
            <label class="form-label">Book Editor <span class="text-danger">*</span></label>
            <input type="text" name="editor" class="form-control" placeholder="e.g. Lindiwe Zwane" value="" required>
        </div>

        <!-- Book Description -->
        <div class="col-md-12">
            <label class="form-label">Book Description <small class="text-muted">(Max 600 characters)</small></label>
            <textarea name="description" class="form-control" rows="4" maxlength="600" placeholder="Brief summary of the book..."></textarea>
        </div>

        <!-- Book Subject -->
        <div class="col-md-6">
            <label class="form-label">Book Subject <span class="text-danger">*</span></label>
            <select name="category" class="form-select" required>
                <option value="">Choose a Subject</option>
                <?php foreach ($subjects as $sub): ?>
                    <option value="<?= $sub ?>"><?= $sub ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <!-- Book Academic Level -->
        <div class="col-md-6">
            <label class="form-label">Academic Level <span class="text-danger">*</span></label>
            <select name="level" class="form-select" required>
                <option value="">Choose a Level</option>
                <?php foreach ($academicLevel as $level): ?>
                    <option value="<?= $level ?>"><?= $level ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <!-- Book Language -->
        <div class="col-md-6">
            <label class="form-label">Book Language <span class="text-danger">*</span></label>
            <select name="language" class="form-select" required>
                <option value="">Choose a Language</option>
                <?php foreach ($languages as $language): ?>
                    <option value="<?= $language ?>"><?= $language ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <!-- Book ISBN -->
        <div class="col-md-6">
            <label class="form-label">ISBN</label>
            <input type="text" name="ISBN" class="form-control" placeholder="e.g. 12345-69875-36" value="">
        </div>

        <!-- Book Cover file -->
        <div class="col-md-6">
            <label class="form-label">Upload Book Cover <span class="text-danger">*</span></label>
            <input type="file" name="cover" class="form-control">
        </div>

        <!-- Book Publish Date -->
        <div class="col-md-6">
            <label class="form-label">Publish Date <span class="text-danger">*</span></label>
            <input type="date" name="publish_date" class="form-control" value="" required>
        </div>

        <hr class="my-4">

        <h4 class="fw-bold mb-2">Ebook Details</h4>

        <!-- Book PDF file -->
        <div class="col-md-6">
            <label class="form-label">Upload Ebook <small class="text-muted">(PDF)</small></label>
            <input type="file" name="pdf" class="form-control">
        </div>

        <!-- Ebook Price -->
        <div class="col-md-6">
            <label class="form-label">Ebook Price <small class="text-muted">(ZAR)</small></label>
            <input type="number" name="ebook_price" class="form-control" placeholder="e.g. 89" value="">
        </div>

        <hr class="my-4">

        <h4 class="fw-bold mb-2">Physical Copy Details</h4>

        <!-- Physical Copy Link -->
        <div class="col-md-6">
            <label class="form-label">Physical Copy <small class="text-muted">(Link)</small></label>
            <input type="text" name="link" class="form-control" placeholder="e.g. http://www.amazon.co.za/books/purchase/23r342rte12312" value="">
        </div>

        <!-- Physical Copy Price -->
        <div class="col-md-6">
            <label class="form-label">Physical Copy Price <small class="text-muted">(ZAR)</small></label>
            <input type="number" name="physical_book_price" class="form-control" placeholder="e.g. 89" value="">
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        <div class="text-end">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-paper-plane me-2"></i> Publish Book
            </button>
        </div>
    </div>
</form>