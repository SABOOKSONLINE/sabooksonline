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

<div id="formsContainer">
    <form method="POST" action="" class="bg-white rounded shadow-sm p-4 mb-4 magazine-form" enctype="multipart/form-data">
        <h4 class="fw-bold mb-4">Basic Magazine Information</h4>
        <div class="row g-3">

            <!-- Magazine Title -->
            <div class="col-md-6">
                <label class="form-label">Magazine Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control" placeholder="e.g. Tech Today" required>
            </div>

            <!-- Publisher -->
            <div class="col-md-6">
                <label class="form-label">Publisher <span class="text-danger">*</span></label>
                <input type="text" name="publisher_name" class="form-control" placeholder="e.g. Global Magazines Ltd." required>
            </div>

            <!-- Category -->
            <div class="col-md-6">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select">
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
                    foreach ($categories as $cat) {
                        echo "<option value='" . htmlspecialchars($cat) . "'>" . htmlspecialchars($cat) . "</option>";
                    }
                    ?>
                </select>
            </div>


            <!-- ISSN -->
            <div class="col-md-6">
                <label class="form-label">ISSN Number</label>
                <input type="text" name="issn" class="form-control" placeholder="e.g. 1234-5678">
            </div>

            <!-- Frequency -->
            <div class="col-md-6">
                <label class="form-label">Frequency <span class="text-danger">*</span></label>
                <select name="frequency" class="form-select" required>
                    <option value="">Choose frequency</option>
                    <option value="weekly">Weekly</option>
                    <option value="biweekly">Biweekly</option>
                    <option value="monthly">Monthly</option>
                    <option value="bimonthly">Bimonthly</option>
                    <option value="quarterly">Quarterly</option>
                    <option value="annual">Annual</option>
                </select>
            </div>

            <!-- Language -->
            <div class="col-md-6">
                <label class="form-label">Language</label>
                <input type="text" name="language" class="form-control" placeholder="e.g. English">
            </div>

            <!-- Country -->
            <div class="col-md-6">
                <label class="form-label">Country</label>
                <input type="text" name="country" class="form-control" placeholder="e.g. South Africa">
            </div>

            <!-- Publish Date -->
            <div class="col-md-6">
                <label class="form-label">Publish Date</label>
                <input type="date" name="publish_date" class="form-control">
            </div>

            <!-- Cover Image -->
            <div class="col-md-6">
                <label class="form-label">Cover Image</label>
                <input type="file" name="cover_image_url" class="form-control">
            </div>

            <!-- PDF Upload -->
            <div class="col-md-6">
                <label class="form-label">PDF Upload</label>
                <input type="file" name="mag_pdf_url" class="form-control">
            </div>

            <!-- Active Status -->
            <div class="col-md-6">
                <label class="form-label">Active</label>
                <select name="is_active" class="form-select">
                    <option value="1" selected>Yes</option>
                    <option value="0">No</option>
                </select>
            </div>

            <!-- Description -->
            <div class="col-12">
                <label class="form-label">Description <small class="text-muted">(Max 600 characters)</small></label>
                <textarea name="description" class="form-control" rows="4" maxlength="600" placeholder="Brief summary of the magazine..."></textarea>
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
                    <i class="fas fa-paper-plane me-2"></i> Publish Magazine
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