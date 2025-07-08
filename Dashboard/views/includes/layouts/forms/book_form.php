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

$narrator = htmlspecialchars_decode($book['narrator'] ?? '');
$releaseDate = htmlspecialchars_decode($book['release_date'] ?? '');

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
    <h4 class="fw-bold mb-3">Ebook Upload</h4>
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


    <hr class="my-4">

    <div class="d-flex align-content-center justify-content-between">
        <h4 class="fw-bold mb-3">Audiobook Details</h4>
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

        <?php if (!empty($narrator)): ?>
            <?php if (count($chapters) > 0): ?>
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
                                            href="/dashboards/listings/deleteAudioChapter/<?= $chapter['chapter_id'] ?>">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($narrator): ?>
            <div class="col">
                <span class="btn btn-dark" id="pop_form_btn"><i class="fas fa-plus"></i> Add Chapter</span>
            </div>
        <?php endif; ?>
    </div>


    <div class="mt-4 text-end">
        <button type="submit" class="btn btn-success px-4">
            <?= !empty($bookId) ? 'Update Book' : 'Save Book' ?>
        </button>
    </div>
</form>

<div class="pop_bg" id="audiobook_info">
    <div class="pop_form">
        <form method="POST"
            action="/dashboards/listings/insertAudioChapter"
            enctype="multipart/form-data" id="chapter_form">


            <input type="hidden" name="content_id" value="<?= $contentId ?>">
            <input type="hidden" name="audiobook_id" value="<?= $book['audiobook_id'] ?>">
            <input type="hidden" name="chapter_id" id="chapter_id" value="<?= $chapter['chapter_id'] ?>">

            <div class="card border-0 p-4">
                <span class="close_pop_form">
                    <i class="fas fa-times"></i>
                </span>

                <div class="row g-3">
                    <h4 class="fw-bold mb-3">Audiobook Chapter Information</h4>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Chapter Number <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="chapter_number" id="chapter_number" required>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Chapter Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="chapter_title" id="chapter_title" required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Audio File <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="audio_url" accept=".mp3">
                    </div>

                    <?php if (count($book['chapters'])): ?>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Current Audio:</label> <br>
                            <audio id="audio_url" controls>
                                <source src="" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                            <input type="hidden" name="audio_url" value="<?= $audioUrl ?>">
                        </div>
                    <?php endif; ?>

                    <div class="col-12">
                        <button class="btn btn-success" type="submit" id="chapter_btn">Add Chapter</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

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