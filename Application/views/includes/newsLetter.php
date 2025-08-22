<section class="section nl-section" id="nl-section">
    <div class="container">
        <div class="col-md-6 mb-4 mb-md-0">
            <h1 class="nl-heading">Stay Connected to South African Literature!</h1>
            <p class="nl-subheading">
                Subscribe to the SA Books Online newsletter for the latest books, library news, events,
                and opportunities, all in one place. Join our community today and celebrate the richness
                of South African stories!
            </p>
        </div>

        <div class="col-md-6">
            <!-- Bootstrap Alert Message -->
            <?php if (isset($_SESSION['alert'])): ?>
                <div class="alert alert-<?= $_SESSION['alert']['type']; ?> alert-dismissible fade show mt-3" role="alert">
                    <?= htmlspecialchars($_SESSION['alert']['message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['alert']); ?>
            <?php endif; ?>

            <!-- Newsletter Form -->
            <form action="/newsletter-handler" method="post" class="d-flex">
                <input
                    type="email"
                    id="newsletter-email"
                    name="subscribe"
                    class="form-control form-control-lg me-2 text-secondary nl-input"
                    placeholder="Enter email address"
                    aria-label="Email for newsletter"
                    required>
                <button class="btn btn-white" type="submit">Subscribe</button>
            </form>
            <small class="nl-fine">Weekly newsletter only. No spam, unsubscribe at any time.</small>
        </div>
    </div>
</section>