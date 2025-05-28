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
$languages = html_entity_decode($book['LANGUAGES'] ?? '');
$status = html_entity_decode($book['STATUS'] ?? 'Draft');
$availability = html_entity_decode($book['STOCK'] ?? '');
$keywords = html_entity_decode($book['CATEGORY'] ?? '');
$type = html_entity_decode($book['TYPE'] ?? '');
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
    class="bg-white rounded mb-4 overflow-hidden position-relative"
    enctype="multipart/form-data">
    <input type="hidden" name="user_id" value="<?= $userKey ?>">
    <input type="hidden" name="book_id" value="<?= $bookId ?>">
    <input type="hidden" name="existing_cover" value="<?= html_entity_decode($cover) ?>">

    <div class="card border-0 shadow-sm p-4 mb-3">
        <h5 class="fw-bold mb-3">Book Information</h5>
        <div class="row">
            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Book Title*</label>
                    <input type="text" class="form-control" name="book_title" value="<?= $title ?>" required>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Authors*</label>
                    <input type="text" class="form-control" name="book_authors" value="<?= $authors ?>" required>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Publisher*</label>
                    <input type="text" class="form-control bg-white text-muted opacity-75" name="book_publisher" value="<?= $publisher ?>" required>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Languages*</label>
                    <select class="form-select" name="book_languages" required>
                        <option value="">Select Language</option>
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
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">ISBN Number*</label>
                    <input type="text" class="form-control" name="book_isbn" value="<?= $isbn ?>" required>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Publish Date*</label>
                    <input type="date" class="form-control" name="book_date_published" value="<?= $datePosted ?>" required>
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Book Description <small>(Maximum of 600 characters)</small></label>
                    <textarea class="form-control" rows="6" maxlength="600" name="book_desc" required><?= $description ?></textarea>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label for="bookCategory" class="form-label fw-semibold">Book Categories*</label>
                    <select id="bookCategory" class="form-select" name="book_category[]" required>
                        <option value="">Select Category</option>
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
                            echo "<option value=\"$cat\">$cat</option>\n";
                        }
                        if ($category) {
                            echo "<option value=\"$category\" selected>$category</option>\n";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Keywords</label>
                    <input type="text" class="form-control" name="book_keywords" value="<?= $keywords ?? $category ?>">
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Type*</label>
                    <select class="form-select" name="book_type" required>
                        <option value="">Select Type</option>
                        <option value="publisher" <?= $type == 'publisher' ? 'selected' : '' ?>>Publisher</option>
                        <option value="author" <?= $type == 'author' ? 'selected' : '' ?>>Author</option>
                    </select>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Website</label>
                    <input type="url" class="form-control" name="book_website" value="<?= $website ?>">
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Retail Price</label>
                    <input type="number" class="form-control" name="book_price" value="<?= $retailPrice ?>">
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Status*</label>
                    <select class="form-select" name="book_status" required>
                        <option value="">Select Status</option>
                        <option value="active" <?= $status == 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= $status !== 'active' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Availability*</label>
                    <select class="form-select" name="book_stock" required>
                        <option value="">Select Availability</option>
                        <option value="In-Stock" <?= $availability == 'In-Stock' ? 'selected' : '' ?>>In-Stock</option>
                        <option value="Out of Stock" <?= $availability == 'Out of Stock' ? 'selected' : '' ?>>Out of Stock</option>
                    </select>
                </div>
            </div>

            <hr class="my-4">
            <h5 class="fw-bold mb-3">Book Cover & Ebook Files</h5>
            <div class="col-sm-12">
                <div class="mb-3">
                    <label for="bookCover" class="form-label fw-semibold">Upload Book Cover*</label>
                    <input type="file" class="form-control" id="bookCover" name="book_cover" accept="image/*" <?= empty($cover) ? 'required' : '' ?>>
                    <?php if (!empty($cover)): ?>
                        <div class="mt-2">
                            <label class="form-label fw-semibold">Current Cover:</label> <br>
                            <img src="/cms-data/book-covers/<?= $cover ?>" alt="Book Cover" class="img-fluid rounded" style="max-height: 150px;">
                            <input type="hidden" name="existing_cover" value="<?= $cover ?>">
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="mb-3">
                    <label for="bookPdf" class="form-label fw-semibold">Upload Book PDF</label>
                    <input type="file" class="form-control" id="bookPdf" name="book_pdf" accept=".pdf">

                    <?php if (!empty($pdf)): ?>
                        <div class="mt-2">
                            <label class="form-label fw-semibold">Current PDF:</label><br>
                            <a href="/cms-data/book-pdfs/<?= htmlspecialchars($pdf) ?>" class="btn btn-primary btn-sm" target="_blank">View PDF</a>
                            <input type="hidden" name="existing_pdf" value="<?= $pdf ?>">
                        </div>
                    <?php endif; ?>
                </div>
            </div>


            <div class="mt-3">
                <?php if (!empty($bookId)): ?>
                    <button class="btn btn-success" type="submit">Update Book</button>
                <?php else: ?>
                    <button class="btn btn-success" type="submit">Save Book</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</form>