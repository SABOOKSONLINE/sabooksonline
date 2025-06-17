<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$book = $book ?? [];

$bookId = html_entity_decode($book['ID'] ?? '');
$title = html_entity_decode($book['TITLE'] ?? '');
$cover = html_entity_decode($book['COVER'] ?? '');
$category = $book['CATEGORY'] ?? '';
$publisher = ucwords(html_entity_decode($book['PUBLISHER'] ?? ''));
$authors = html_entity_decode($book['AUTHORS'] ?? '');
$description = html_entity_decode($book['DESCRIPTION'] ?? '');
$isbn = html_entity_decode($book['ISBN'] ?? '');
$website = html_entity_decode($book['WEBSITE'] ?? '');
$retailPrice = html_entity_decode($book['RETAILPRICE'] ?? '');
$EbookPrice = html_entity_decode($book['EBOOKPRICE'] ?? '');
$languages = html_entity_decode($book['LANGUAGES'] ?? '');
$status = html_entity_decode($book['STATUS'] ?? 'Draft');
$availability = html_entity_decode($book['STOCK'] ?? 'in stock');
$keywords = html_entity_decode($book['CATEGORY'] ?? '');
$type = html_entity_decode($book['TYPE'] ?? 'book');
$pdf = html_entity_decode($book['PDFURL'] ?? '');

$datePosted = null;
if (!empty($book['DATEPOSTED'])) {
    $formats = ['l jS \o\f F Y', 'Y-m-d', 'd-m-Y', 'm/d/Y'];
    foreach ($formats as $format) {
        $parsedDate = DateTime::createFromFormat($format, $book['DATEPOSTED']);
        if ($parsedDate) {
            $datePosted = $parsedDate->format('Y-m-d');
            break;
        }
    }
}
?>

<form method="POST"
    action="<?= $bookId ? "/dashboards/listings/update/$bookId" : "/dashboards/listings/insert" ?>"
    class="bg-white rounded shadow-sm p-4 mb-4"
    enctype="multipart/form-data">

    <!-- Hidden Fields -->
    <input type="hidden" name="user_id" value="<?= $userKey ?>">
    <input type="hidden" name="book_id" value="<?= $bookId ?>">
    <input type="hidden" name="existing_pdf" value="<?= $pdf ?>">
    <input type="hidden" name="existing_cover" value="<?= html_entity_decode($cover) ?>">
    <input type="hidden" name="book_stock" value="<?= $availability ?>">
    <input type="hidden" name="book_type" value="<?= $type ?>">



    <!-- Section: Basic Book Info -->
    <h4 class="fw-bold mb-4">Basic Book Information</h4>
    <div class="row g-3">

        <div class="col-md-6">
            <label class="form-label">Title <span class="text-danger">*</span></label>
            <input type="text" name="book_title" class="form-control" placeholder="e.g. The Hidden Truth" value="<?= $title ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Author(s) <span class="text-danger">*</span></label>
            <input type="text" name="book_authors" class="form-control" placeholder="e.g. John Doe, Jane Smith" value="<?= $authors ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Publisher <span class="text-danger">*</span></label>
            <input type="text" name="book_publisher" class="form-control" placeholder="e.g. Penguin Books" value="<?= $publisher ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Language <span class="text-danger">*</span></label>
            <select class="form-select" name="book_languages" required>
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
                    $selected = ($lang == $languages) ? 'selected' : '';
                    echo "<option value=\"$lang\" $selected>$lang</option>";
                }
                ?>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">ISBN Number <span class="text-danger">*</span></label>
            <input type="text" name="book_isbn" class="form-control" placeholder="e.g. 978-3-16-148410-0" value="<?= $isbn ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Publish Date <span class="text-danger">*</span></label>
            <input type="date" name="book_date_published" class="form-control" value="<?= $datePosted ?>" required>
        </div>

        <div class="col-12">
            <label class="form-label">Description <small class="text-muted">(Max 600 characters)</small></label>
            <textarea name="book_desc" class="form-control" rows="4" maxlength="600" required placeholder="Brief summary of the book..."><?= $description ?></textarea>
        </div>

    </div>

    <hr class="my-4">

    <!-- Section: Category & Type -->
    <h4 class="fw-bold mb-3">Category & Format</h4>
    <div class="row g-3">

        <div class="col-md-6">
            <label class="form-label">Main Category <span class="text-danger">*</span></label>
            <select name="book_category[]" class="form-select" required>
                <option value="">Choose a category</option>
                <?php
                $categories = [
                            "Fiction",
                            "Poetry",
                            "Adult Fiction",
                            "Arts & Photography",
                            "Biographies & Memoirs",
                            "Business & Money",
                            "Children",
                            "Comics & Graphic Novels",
                            "Computers & Technology",
                            "Cooking, Food & Wines",
                            "Crafts, Hobbies & Home",
                            "Education & Training",
                            "Engineering & Transportation",
                            "Health, Fitness & Dieting",
                            "History",
                            "Humour & Entertainment",
                            "Law",
                            "LGBTQIA+",
                            "Medical",
                            "Mystery, Thriller & Suspense",
                            "Mythology",
                            "Nonfiction",
                            "Parenting & Relationships",
                            "Politics & Social Sciences",
                            "Religion & Spirituality",
                            "Romance",
                            "Science & Math",
                            "Science Fiction & Fantasy",
                            "Self-Help",
                            "Sports & Outdoors",
                            "Teen & Young Adult",
                            "Travel",
                            "Magazines & Newspapers",
                            "Psychology",
                            "Reference",
                            "Environment"
                        ];

                foreach ($categories as $cat) {
                    echo "<option value=\"$cat\">" . htmlspecialchars($cat) . "</option>";
                }
                if ($category) {
                    echo "<option value=\"$category\" selected>$category</option>";
                }
                ?>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Keywords (Optional)</label>
            <input type="text" name="book_keywords" class="form-control" placeholder="e.g. thriller, South Africa" value="<?= $keywords ?? $category ?>">
        </div>

        <div class="col-md-6">
            <label class="form-label">Purchase Link (Hard Copy)</label>
            <input type="url" name="book_website" class="form-control" placeholder="https://..." value="<?= $website ?>">
        </div>
    </div>

    <hr class="my-4">

    <!-- Section: Pricing -->
    <h4 class="fw-bold mb-3">Availability & Pricing</h4>
    <div class="row g-3">

        <div class="col-md-6">
            <label class="form-label">Retail Price (Hard Copy)</label>
            <input type="number" name="book_price" class="form-control" placeholder="e.g. 199.99" value="<?= $retailPrice ?>">
        </div>

        <div class="col-md-6">
            <label class="form-label">Ebook Price (PDF)</label>
            <input type="number" name="Ebook_price" class="form-control" placeholder="e.g. 49.99" value="<?= $EbookPrice ?>">
        </div>

         <div class="col-md-6">
            <label class="form-label">Audio book Price</label>
            <input type="number" name="Abook_price" class="form-control" placeholder="e.g. 49.99" value="<?= $AbookPrice ?>">
        </div>

        <div class="col-md-6">
            <label class="form-label">Status <span class="text-danger">*</span></label>
            <select name="book_status" class="form-select" required>
                <option value="">Choose status</option>
                <option value="active" <?= $status == 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= $status !== 'active' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>


    </div>

    <hr class="my-4">

    <!-- Section: Uploads -->
    <h4 class="fw-bold mb-3">Uploads</h4>
    <div class="row g-3">

        <div class="col-md-6">
            <label class="form-label">Upload Book Cover Image <span class="text-danger">*</span></label>
            <input type="file" name="book_cover" class="form-control" accept="image/*" <?= empty($cover) ? 'required' : '' ?>>
            <?php if (!empty($cover)): ?>
                <div class="mt-2">
                    <small class="text-muted">Current Cover:</small><br>
                    <img src="/cms-data/book-covers/<?= $cover ?>" class="img-thumbnail" style="max-height: 150px;">
                </div>
            <?php endif; ?>
        </div>

        <div class="col-md-6">
            <label class="form-label">Upload Ebook (PDF)</label>
            <input type="file" name="book_pdf" class="form-control" accept=".pdf">
            <?php if (!empty($pdf)): ?>
                <div class="mt-2">
                    <small class="text-muted">Current PDF:</small><br>
                    <a href="/cms-data/book-pdfs/<?= htmlspecialchars($pdf) ?>" target="_blank" class="btn btn-outline-primary btn-sm">View Current PDF</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-4 text-end">
        <button type="submit" class="btn btn-success px-4">
            <?= !empty($bookId) ? 'Update Book' : 'Save Book' ?>
        </button>
    </div>
</form>
