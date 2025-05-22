<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$service = $service ?? [];

$serviceName = htmlspecialchars($service['SERVICE'] ?? '');
// must remove temp userid (testing)
$userId = htmlspecialchars($service['USERID'] ?? '64c971169092344964c971b98');
$status = htmlspecialchars($service['STATUS'] ?? 'draft');
$created = htmlspecialchars($service['CREATED'] ?? '');
$minimum = htmlspecialchars($service['MINIMUM'] ?? '');
$maximum = htmlspecialchars($service['MAXIMUM'] ?? '');

$action = null;
$serviceId = $service["ID"] ?? '';

if ($service) {
    $action = "update";
} else {
    $action = "insert";
}
?>

<form method="post" action="/handlers/service_handler.php?action=<?= $action ?>&id=<?= $serviceId ?>" class="bg-white rounded mb-4 overflow-hidden position-relative">
    <div class="card border-0 shadow-sm p-4 mb-3">
        <div class="row">
            <div class="border-bottom pb-3 mb-4">
                <h5 class="fw-bold">Service Information</h5>
            </div>

            <input type="text" name="user_id" value="<?= $userId ?>" hidden>
            <input type="text" name="service_created" value="<?= $created ?>" hidden>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Type Of Service*</label>
                    <select class="form-select" name="service" required>
                        <option value="cover_designer">Cover Designer</option>
                        <option value="speaker">Speaker</option>
                        <option value="reviewer">Reviewer</option>
                        <option value="writter">Writter</option>
                        <option value="illustrator">Illustrator</option>
                        <option value="distributer">Distributer</option>
                        <option value="printer">Printer</option>
                        <option value="proof_reader">Proof Reader</option>
                        <option value="editor">Editor</option>
                    </select>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Status*</label>
                    <select class="form-select" name="service_status" required>
                        <option value="Active" <?= $status === 'Active' ? 'selected' : '' ?>>Active</option>
                        <option value="Inactive" <?= $status === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Minimum Amount</label>
                    <input type="text" class="form-control" name="minimum_amount" value="<?= $minimum ?>">
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Maximum Amount</label>
                    <input type="text" class="form-control" name="maximum_amount" value="<?= $maximum ?>">
                </div>
            </div>

            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-primary ms-2" name="save_and_activate">Save & Publish</button>
            </div>
        </div>
    </div>
</form>