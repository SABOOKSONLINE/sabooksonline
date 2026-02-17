<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$book = $book ?? [];

$bookId       = html_entity_decode($book['ID'] ?? '');
$bookPk    = $book['CONTENTID'] ?? '';
$title        = html_entity_decode($book['TITLE'] ?? '');
$cover        = html_entity_decode($book['COVER'] ?? '');
$category     = $book['CATEGORY'] ?? '';
$publisher    = ucwords(html_entity_decode($book['PUBLISHER'] ?? ''));
$authors      = html_entity_decode($book['AUTHORS'] ?? '');
$description  = html_entity_decode($book['DESCRIPTION'] ?? '');
$isbn         = html_entity_decode($book['ISBN'] ?? '');
$website      = html_entity_decode($book['WEBSITE'] ?? '');
$retailPrice  = html_entity_decode($book['RETAILPRICE'] ?? '');
$EbookPrice   = html_entity_decode($book['EBOOKPRICE'] ?? '');
$languages    = html_entity_decode($book['LANGUAGES'] ?? '');
$status       = html_entity_decode($book['STATUS'] ?? 'Draft');
$availability = html_entity_decode($book['STOCK'] ?? 'in stock');
$keywords     = html_entity_decode($book['CATEGORY'] ?? '');
$type         = html_entity_decode($book['TYPE'] ?? 'book');
$pdf          = html_entity_decode($book['PDFURL'] ?? '');

// -------------------- HARDCOPY VARIABLES --------------------
$hcId               = html_entity_decode($book['hc_id'] ?? '');
$hcPrice            = html_entity_decode($book['hc_price'] ?? '');
$hcDiscountPercent  = html_entity_decode($book['hc_discount_percent'] ?? '0');
$hcCountry          = html_entity_decode($book['hc_country'] ?? '');
$hcPages            = html_entity_decode($book['hc_pages'] ?? '');
$hcWeightKg         = html_entity_decode($book['hc_weight_kg'] ?? '');
$hcHeightCm         = html_entity_decode($book['hc_height_cm'] ?? '');
$hcWidthCm          = html_entity_decode($book['hc_width_cm'] ?? '');
$hcReleaseDate      = html_entity_decode($book['hc_release_date'] ?? '');
$hcContributors     = html_entity_decode($book['hc_contributors'] ?? '');
$hcStockCount       = html_entity_decode($book['hc_stock_count'] ?? '');

// -------------------- AUDIOBOOK VARIABLES --------------------
$narrator = htmlspecialchars_decode($book['narrator'] ?? '');
$releaseDate = htmlspecialchars_decode($book['audiobook_release_date'] ?? '');
$audiobookSampleId = htmlspecialchars_decode($book['audiobook_sample_id'] ?? '');
$audiobookSampleUrl = htmlspecialchars_decode($book['sample_url'] ?? '');
$AbookPrice = html_entity_decode($book['ABOOKPRICE'] ?? '');

if (!empty($narrator)) {
    $chapters = $book['chapters'];
}

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

// Check if this is an existing book (has ID)
$isExistingBook = !empty($bookId);
?>

<?php
// Display session-based alerts
if (isset($_SESSION['alert_type']) && isset($_SESSION['alert_message'])): ?>
    <div class="alert alert-<?= $_SESSION['alert_type'] ?> alert-dismissible fade show" role="alert">
        <i class="fas fa-<?= $_SESSION['alert_type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
        <strong><?= $_SESSION['alert_type'] === 'success' ? 'Success!' : 'Error!' ?></strong>
        <?= htmlspecialchars($_SESSION['alert_message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php
    // Clear the session variables after displaying
    unset($_SESSION['alert_type']);
    unset($_SESSION['alert_message']);
    ?>
<?php endif; ?>

<form method="POST"
    action="<?= $bookId ? "/dashboards/listings/update/$bookId" : "/dashboards/listings/insert" ?>"
    class="bg-white rounded shadow-sm p-4 mb-4"
    enctype="multipart/form-data"
    id="bookForm">

    <!-- Hidden Fields -->
    <input type="hidden" name="user_id" value="<?= $userKey ?>">
    <input type="hidden" name="book_public_key" value="<?= $bookPk ?>">
    <input type="hidden" name="book_id" value="<?= $bookId ?>">
    <input type="hidden" name="existing_pdf" value="<?= $pdf ?>">
    <input type="hidden" name="existing_cover" value="<?= html_entity_decode($cover) ?>">
    <input type="hidden" name="book_stock" value="<?= $availability ?>">
    <input type="hidden" name="book_type" value="<?= $type ?>">
    <input type="hidden" name="current_tab" id="current_tab" value="book-details">

    <!-- NAV TABS STRUCTURE -->
    <ul class="nav nav-tabs" id="bookTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active"
                id="book-details-tab"
                data-bs-toggle="tab"
                data-bs-target="#book-details"
                type="button"
                role="tab"
                data-tab="book-details">
                Basic Book Information
            </button>
        </li>

        <li class="nav-item" role="presentation">
            <button class="nav-link <?= !$isExistingBook ? 'disabled' : '' ?>"
                id="ebook-details-tab"
                data-bs-toggle="tab"
                data-bs-target="#ebook-details"
                type="button"
                role="tab"
                data-tab="ebook-details"
                <?= !$isExistingBook ? 'disabled' : '' ?>>
                E-Book
                <?php if (!$isExistingBook): ?>
                    <i class="fas fa-lock ms-1"></i>
                <?php endif; ?>
            </button>
        </li>

        <li class="nav-item" role="presentation">
            <button class="nav-link <?= !$isExistingBook ? 'disabled' : '' ?>"
                id="audiobook-details-tab"
                data-bs-toggle="tab"
                data-bs-target="#audiobook-details"
                type="button"
                role="tab"
                data-tab="audiobook-details"
                <?= !$isExistingBook ? 'disabled' : '' ?>>
                Audiobook
                <?php if (!$isExistingBook): ?>
                    <i class="fas fa-lock ms-1"></i>
                <?php endif; ?>
            </button>
        </li>

        <li class="nav-item" role="presentation">
            <button class="nav-link <?= !$isExistingBook ? 'disabled' : '' ?>"
                id="hardcopy-details-tab"
                data-bs-toggle="tab"
                data-bs-target="#hardcopy-details"
                type="button"
                role="tab"
                data-tab="hardcopy-details"
                <?= !$isExistingBook ? 'disabled' : '' ?>>
                Physical Copy
                <?php if (!$isExistingBook): ?>
                    <i class="fas fa-lock ms-1"></i>
                <?php endif; ?>
            </button>
        </li>
    </ul>

    <div class="tab-content mt-3">

        <!-- BASIC BOOK INFORMATION TAB -->
        <div class="tab-pane fade show active"
            id="book-details"
            role="tabpanel">

            <?php if (!$isExistingBook): ?>
                <div class="alert alert-info mb-4">
                    <i class="fas fa-info-circle"></i>
                    <strong>Getting Started:</strong> Please complete the basic book information below and save. Once saved, you can add E-Book, Audiobook, and Physical Copy details.
                </div>
            <?php endif; ?>

            <h4 class="fw-bold mb-3">Basic Book Information</h4>
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
            <h5 class="fw-bold mb-3">Category, Keywords & Cover</h5>
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
                            $selected = ($cat == $category) ? 'selected' : '';
                            echo "<option value=\"$cat\" $selected>" . htmlspecialchars($cat) . "</option>";
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
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="book_status" class="form-select" required>
                        <option value="">Choose status</option>
                        <option value="active" <?= $status == 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= $status !== 'active' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Retail Price <span class="text-danger">(External Physical Copy)</span></label>
                    <input type="number" name="book_price" class="form-control" placeholder="e.g. 199.99" value="<?= $retailPrice ?>">
                </div>
            </div>

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-success px-5">
                    <?= $bookId ? 'Save & Continue' : 'Create Book' ?>
                </button>
            </div>
        </div>

        <!-- E-BOOK DETAILS TAB -->
        <div class="tab-pane fade"
            id="ebook-details"
            role="tabpanel">

            <h4 class="fw-bold my-4">E-Book Details</h4>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">E-Book Price (PDF)</label>
                    <input type="number" step="0.01" name="Ebook_price" class="form-control" placeholder="e.g. 49.99" value="<?= $EbookPrice ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Upload Ebook (PDF)</label>
                    <input type="file" name="book_pdf" class="form-control" accept="application/pdf,.pdf" id="book_pdf">
                    <?php if (!empty($pdf)): ?>
                        <div class="mt-2">
                            <small class="text-muted">Current PDF:</small><br>
                            <a href="/cms-data/book-pdfs/<?= htmlspecialchars($pdf) ?>" target="_blank" class="btn btn-outline-primary btn-sm">View Current PDF</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary px-4 nav-button"
                    data-target-tab="book-details-tab">
                    <i class="fas fa-arrow-left"></i> Back
                </button>
                <div>
                    <button type="submit" class="btn btn-success px-4 me-2">
                        Save & Continue
                    </button>
                    <button type="button" class="btn btn-outline-dark px-4 nav-button"
                        data-target-tab="audiobook-details-tab">
                        Next <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- AUDIOBOOK DETAILS TAB -->
        <div class="tab-pane fade"
            id="audiobook-details"
            role="tabpanel">

            <h4 class="fw-bold my-4">Audiobook Details</h4>

            <div class="d-flex align-content-center justify-content-between mb-3">
                <div></div>
                <?php if ($narrator): ?>
                    <div class="">
                        <span
                            class="btn btn-sm btn-outline-primary" id="adit_ab">
                            Edit Audiobook Details
                            <i class="fas fa-edit"></i>
                        </span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">
                        Narrator
                        <?php if (!empty($narrator)): ?>
                            <small class="text-muted">(Read only)</small>
                        <?php endif; ?>
                    </label>
                    <input type="text" class="form-control bg-light" name="audiobook_narrator"
                        placeholder="e.g. John Smith" value="<?= $narrator ?>" id="audiobook_narrator" readonly>
                </div>

                <div class="col-sm-6">
                    <label class="form-label">
                        Release Date
                        <?php if (!empty($narrator)): ?>
                            <small class="text-muted">(Read only)</small>
                        <?php endif; ?>
                    </label>
                    <input type="date" class="form-control bg-light" name="release_date"
                        value="<?= $releaseDate ?>" readonly id="release_date">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Audio book Price (R)</label>
                    <input type="number" step="0.01" name="Abook_price" class="form-control" placeholder="e.g. 49.99" value="<?= $AbookPrice ?>">
                </div>

                <?php if ($audiobookSampleUrl): ?>
                    <div class="col-12">
                        <label class="fw-semi-bold mb-3 form-label">
                            Audiobook Sample
                        </label>
                        <audio controls class="w-100">
                            <source src="/cms-data/audiobooks/samples/<?= htmlspecialchars($audiobookSampleUrl) ?>" type="audio/mpeg">
                        </audio>
                    </div>
                <?php endif; ?>

                <?php if (!empty($narrator) && count($chapters) > 0): ?>
                    <div class="col-12">
                        <div class="table-responsive mt-4">
                            <h5 class="fw-semi-bold mb-3">Audiobook Chapter List</h5>
                            <table class="table table-hover align-middle">
                                <tbody>
                                    <?php foreach ($chapters as $chapter): ?>
                                        <tr>
                                            <td class="fw-bold"><?= htmlspecialchars($chapter['chapter_number']) ?></td>
                                            <td><?= htmlspecialchars($chapter['chapter_title']) ?></td>
                                            <td>
                                                <?php if (!empty($chapter['audio_url'])): ?>
                                                    <audio controls class="w-100">
                                                        <source src="/cms-data/audiobooks/<?= htmlspecialchars($chapter['audio_url']) ?>" type="audio/mpeg">
                                                    </audio>
                                                <?php else: ?>
                                                    <span class="text-muted">No audio</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <span
                                                    class="btn btn-sm btn-outline-primary edit_chapter"
                                                    data-chapter-id="<?= htmlspecialchars($chapter['chapter_id']) ?>"
                                                    data-chapter-number="<?= htmlspecialchars($chapter['chapter_number']) ?>"
                                                    data-chapter-title="<?= htmlspecialchars($chapter['chapter_title']) ?>"
                                                    data-audio-url="<?= htmlspecialchars($chapter['audio_url']) ?>">
                                                    <i class="fas fa-edit"></i>
                                                </span>
                                                <a class="btn btn-sm btn-outline-danger edit_chapter"
                                                    href="/dashboards/listings/deleteAudioChapter/<?= $chapter['chapter_id'] ?>?content_id=<?= $contentId ?>">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($narrator): ?>
                    <div class="col-12">
                        <div class="d-flex gap-2">
                            <div class="">
                                <span class="btn btn-dark" id="sample_pop_btn">
                                    <?php if (!$audiobookSampleUrl):  ?>
                                        <i class="fas fa-plus"></i> Add Sample
                                    <?php else: ?>
                                        <i class="fas fa-pen"></i> Edit Sample
                                    <?php endif; ?>
                                </span>
                            </div>

                            <div class="">
                                <span class="btn btn-dark" id="pop_form_btn"><i class="fas fa-plus"></i> Add Chapter</span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mt-4 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary px-4 nav-button"
                    data-target-tab="ebook-details-tab">
                    <i class="fas fa-arrow-left"></i> Back
                </button>
                <div>
                    <button type="submit" class="btn btn-success px-4 me-2">
                        Save & Continue
                    </button>
                    <button type="button" class="btn btn-outline-dark px-4 nav-button"
                        data-target-tab="hardcopy-details-tab">
                        Next <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- HARDCOPY DETAILS TAB -->
        <div class="tab-pane fade"
            id="hardcopy-details"
            role="tabpanel">

            <h4 class="fw-bold my-4">Physical Copy Details</h4>

            <?php
            if (
                !empty($book['ID']) &&
                isset($_SESSION['ADMIN_EMAIL']) &&
                in_array($_SESSION['ADMIN_EMAIL'], $publisherEmails)
            ):
            ?>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Hardcopy Price (R)
                            <span class="text-danger small">*</span>
                        </label>
                        <input type="number" step="0.01" name="hc_price" class="form-control" placeholder="e.g. 199.99" value="<?= $hcPrice ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Discount Percent (%) </label>
                        <input type="number" name="hc_discount_percent" class="form-control" placeholder="e.g. 10" min="0" max="100" value="<?= $hcDiscountPercent ?>">
                        <i class="text-muted small">Note: percentage will be deducted from the price.</i>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Contributors</label>
                        <input type="text" name="hc_contributors" class="form-control" value="<?= $hcContributors ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Country
                            <span class="text-danger small">*</span>
                        </label>
                        <input type="text" name="hc_country" class="form-control" value="<?= $hcCountry ?>">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Number of Pages
                            <span class="text-danger small">*</span>
                        </label>
                        <input type="number" name="hc_pages" class="form-control" value="<?= $hcPages ?>">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Weight (kg)
                            <span class="text-danger small">*</span>
                        </label>
                        <input type="number" step="0.01" name="hc_weight_kg" class="form-control" value="<?= $hcWeightKg ?>">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Height (cm)
                            <span class="text-danger small">*</span>
                        </label>
                        <input type="number" step="0.01" name="hc_height_cm" class="form-control" value="<?= $hcHeightCm ?>">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Width (cm)
                            <span class="text-danger small">*</span>
                        </label>
                        <input type="number" step="0.01" name="hc_width_cm" class="form-control" value="<?= $hcWidthCm ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Release Date
                            <span class="text-danger small">*</span>
                        </label>
                        <input type="date" name="hc_release_date" class="form-control" value="<?= $hcReleaseDate ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Stock Count
                            <span class="text-danger small">*</span>
                        </label>
                        <input type="number" name="hc_stock_count" class="form-control" value="<?= $hcStockCount ?>">
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Physical copy details are only available for authorized users.
                </div>
            <?php endif; ?>

            <div class="mt-4 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary px-4 nav-button"
                    data-target-tab="audiobook-details-tab">
                    <i class="fas fa-arrow-left"></i> Back
                </button>
                <button type="submit" class="btn btn-success px-5">
                    <?= $bookId ? 'Save Changes' : 'Add Book' ?>
                </button>
            </div>
        </div>

    </div>
</form>

<!-- AUDIOBOOK DETAILS POPUP -->
<div class="ab_pop_bg">
    <div class="row">
        <div class="ab_pop_form p-4 bg-white col-lg-6">
            <form method="POST"
                action="<?= $narrator ? "/dashboards/listings/updateAudio/$bookId" : "/dashboards/listings/insertAudio/" ?>"
                enctype="multipart/form-data" id="ab_form">

                <span class="close_ab_pop_form">
                    <i class="fas fa-times"></i>
                </span>

                <input type="text" class="form-control" name="content_id" id="content_id" value="<?= $contentId ?>" hidden>
                <input type="text" class="form-control" name="book_id" id="book_id" value="<?= $bookId ?>" hidden>

                <h4 class="fw-bold mb-3">Audiobook Information</h4>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label">Narrator <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="audiobook_narrator" value="<?= $narrator ?>" required>
                    </div>

                    <div class="col-sm-6">
                        <label class="form-label">Release Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="release_date" value="<?= $releaseDate ?>" required>
                    </div>

                    <div class="d-flex align-content-center justify-content-between">
                        <div class="col-6">
                            <button class="btn btn-success" type="submit" id="ab_btn">
                                <?php if ($narrator): ?>
                                    Update Details
                                <?php else: ?>
                                    Save Details
                                <?php endif; ?>
                            </button>
                        </div>
                        <div>
                            <?php if ($narrator): ?>
                                <a href="/dashboards/listings/deleteAudio/<?= $bookId ?>?content_id=<?= $contentId ?>" class="btn btn-outline-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this audiobook?');">
                                    Delete
                                    <i class="fas fa-trash"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- AUDIOBOOK SAMPLE POPUP -->
<?php if ($narrator): ?>
    <div class="sample_pop_bg">
        <div class="row">
            <div class="sample_pop_form p-4 pb-2 bg-white col-lg-6">
                <form method="POST"
                    action="<?= $audiobookSampleId ? "/dashboards/listings/updateSampleAudio/$audiobookSampleId" : "/dashboards/listings/insertSampleAudio" ?>"
                    enctype="multipart/form-data">

                    <span class="close_sample_pop_form">
                        <i class="fas fa-times"></i>
                    </span>

                    <input type="text" class="form-control" name="content_id" id="content_id" value="<?= $contentId ?>" hidden>
                    <input type="hidden" class="form-control" name="book_id" id="book_id" value="<?= $bookId ?>">

                    <h4 class="fw-bold mb-3">Audiobook Sample Information</h4>

                    <div class="row mb-3">
                        <div class="col-12 mb-3">
                            <label class="form-label fw-semibold">Sample Audio File <small class="text-muted">(< 5MB)</small><span class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="sample_file" accept=".mp3" required>
                        </div>

                        <?php if ($audiobookSampleId): ?>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Current Sample Audio:</label> <br>
                                <audio id="audio_url" controls>
                                    <source src="/cms-data/audiobooks/samples/<?= htmlspecialchars($audiobookSampleUrl) ?>" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                                <input type="hidden" name="audio_url" value="<?= $audioUrl ?>">
                            </div>
                        <?php endif; ?>

                        <div class="d-flex align-content-center justify-content-between mt-3">
                            <div class="col-6">
                                <button type="submit" class="btn btn-success">
                                    <?= $audiobookSampleId ? "Update Sample" : "Upload Sample" ?>
                                </button>
                            </div>

                            <div>
                                <?php if ($audiobookSampleId): ?>
                                    <a href="/dashboards/listings/deleteSampleAudio/<?= $bookId ?>?content_id=<?= $contentId ?>" class="btn btn-outline-danger"
                                        onclick="return confirm('Are you sure you want to delete this Audiobook Sample?');">
                                        Delete
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<style>
    .nav-tabs .nav-link {
        color: #000;
    }

    .nav-tabs .nav-link.active {
        color: #fff;
        background-color: #000;
        border-color: #000;
    }

    .nav-tabs .nav-link.disabled {
        color: #6c757d;
        background-color: #e9ecef;
        border-color: #dee2e6;
        cursor: not-allowed;
        opacity: 0.65;
    }

    .nav-tabs .nav-link.disabled:hover {
        color: #6c757d;
        background-color: #e9ecef;
    }
</style>

<script>
    // ============================================================================
    // IMPROVED TAB STATE MANAGEMENT
    // ============================================================================
    document.addEventListener('DOMContentLoaded', function() {
        const currentTabInput = document.getElementById('current_tab');
        const bookForm = document.getElementById('bookForm');

        // Function to update the current tab hidden field
        function updateCurrentTab(tabElement) {
            if (tabElement && currentTabInput) {
                const tabName = tabElement.getAttribute('data-tab');
                if (tabName) {
                    currentTabInput.value = tabName;
                    console.log('✓ Current tab updated to:', tabName);
                }
            }
        }

        // ========================================================================
        // LISTEN TO TAB CLICKS - Update hidden field immediately when tab changes
        // ========================================================================
        const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
        tabButtons.forEach(button => {
            // Method 1: Click event
            button.addEventListener('click', function(e) {
                updateCurrentTab(this);
            });

            // Method 2: Bootstrap's shown.bs.tab event (fires after tab is shown)
            button.addEventListener('shown.bs.tab', function(e) {
                updateCurrentTab(this);
            });
        });

        // ========================================================================
        // NAVIGATION BUTTONS - Handle Back/Next buttons
        // ========================================================================
        const navButtons = document.querySelectorAll('.nav-button');
        navButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const targetTabId = this.getAttribute('data-target-tab');
                const targetTab = document.getElementById(targetTabId);

                if (targetTab && !targetTab.classList.contains('disabled')) {
                    // Use Bootstrap Tab API to switch tabs
                    const tab = new bootstrap.Tab(targetTab);
                    tab.show();
                    updateCurrentTab(targetTab);
                }
            });
        });

        // ========================================================================
        // SET INITIAL TAB on page load
        // ========================================================================
        const activeTab = document.querySelector('.nav-link.active');
        if (activeTab) {
            updateCurrentTab(activeTab);
        }

        // ========================================================================
        // DOUBLE-CHECK before form submission
        // ========================================================================
        if (bookForm) {
            bookForm.addEventListener('submit', function(e) {
                const currentActive = document.querySelector('.nav-link.active');
                if (currentActive) {
                    updateCurrentTab(currentActive);
                }
                console.log('📝 Form submitting with tab:', currentTabInput.value);
            });
        }

        // ========================================================================
        // RESTORE TAB from URL parameter
        // ========================================================================
        const urlParams = new URLSearchParams(window.location.search);
        const activeTabParam = urlParams.get('tab');

        if (activeTabParam) {
            console.log('🔄 Restoring tab from URL:', activeTabParam);
            const tabButton = document.querySelector(`[data-tab="${activeTabParam}"]`);
            if (tabButton && !tabButton.classList.contains('disabled')) {
                // Use Bootstrap's Tab API to properly switch tabs
                const tab = new bootstrap.Tab(tabButton);
                tab.show();
                updateCurrentTab(tabButton);
            }
        }

        // ========================================================================
        // AUTO-DISMISS success alerts after 5 seconds
        // ========================================================================
        const successAlerts = document.querySelectorAll('.alert-success');
        successAlerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = bootstrap.Alert.getInstance(alert);
                if (bsAlert) {
                    bsAlert.close();
                }
            }, 5000);
        });
    });

    // ============================================================================
    // PDF VALIDATION
    // ============================================================================
    document.addEventListener('DOMContentLoaded', function() {
        const pdfInput = document.getElementById('book_pdf');
        if (pdfInput) {
            pdfInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file && file.type !== 'application/pdf') {
                    alert('Only PDF files are allowed!');
                    this.value = '';
                }
            });
        }
    });
</script>