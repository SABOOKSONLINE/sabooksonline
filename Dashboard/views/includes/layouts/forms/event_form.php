<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$event = $event ?? [];

$userId = htmlspecialchars($event['USERID'] ?? '');
$title = htmlspecialchars($event['TITLE'] ?? '');
$email = htmlspecialchars($event['EMAIL'] ?? '');
$phone = htmlspecialchars($event['NUMBER'] ?? '');
$venue = htmlspecialchars($event['VENUE'] ?? '');
$eventStartDate = htmlspecialchars($event['EVENTDATE'] ?? '');
$eventTime = htmlspecialchars($event['EVENTTIME'] ?? '');
$eventEndDate = htmlspecialchars($event['EVENTEND'] ?? '');
$endTime = htmlspecialchars($event['TIMEEND'] ?? '');
$description = htmlspecialchars($event['DESCRIPTION'] ?? '');
$eventType = htmlspecialchars($event['EVENTTYPE'] ?? 'Author Q&A');
$attendanceMode = htmlspecialchars($event['ATTENDANCE'] ?? 'In-Person');
$duration = htmlspecialchars($event['DURATION'] ?? '');
$link = htmlspecialchars($event['LINK'] ?? '');
$cover = htmlspecialchars($event['COVER'] ?? '');

$action = $event ? "update" : "insert";
$eventId = $event["ID"] ?? '';
?>

<form method="post" action="/handlers/event_handler.php?action=<?= $action ?>&id=<?= $eventId ?>" class="bg-white rounded mb-4 overflow-hidden position-relative" enctype="multipart/form-data">
    <div class="card border-0 shadow-sm p-4 mb-3">
        <div class="row">
            <div class="border-bottom pb-3 mb-4">
                <h5 class="fw-bold">Event Information</h5>
            </div>

            <input type="text" name="user_id" value="<?= $userId ?>" hidden>

            <div class="col-sm-12">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Upload Event Cover*</label>
                    <input type="file" class="form-control" name="cover" <?= $action === 'insert' ? 'required' : '' ?>>
                    <?php if (!empty($cover)): ?>
                        <div class="mt-2">
                            <label class="form-label fw-semibold">Current Cover:</label>
                            <img src="<?= $cover ?>" alt="Event Cover" class="img-fluid rounded" style="max-height: 150px;">
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Event Title*</label>
                    <input type="text" class="form-control" name="title" value="<?= $title ?>" required>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Contact Email*</label>
                    <input type="email" class="form-control" name="email" value="<?= $email ?>" required>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Contact Phone*</label>
                    <input type="tel" class="form-control" name="number" value="<?= $phone ?>" required>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Venue*</label>
                    <input type="text" class="form-control" name="venue" value="<?= $venue ?>" required>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Event Start Date*</label>
                    <input type="date" class="form-control" name="event_start_date" value="<?= $eventStartDate ?>" required>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Start Time</label>
                    <input type="time" class="form-control" name="event_time" value="<?= $eventTime ?>">
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Event End Date*</label>
                    <input type="date" class="form-control" name="event_end_date" value="<?= $eventEndDate ?>" required>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">End Time</label>
                    <input type="time" class="form-control" name="end_time" value="<?= $endTime ?>">
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Description*</label>
                    <textarea class="form-control" rows="6" name="description" required><?= $description ?></textarea>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Event Type*</label>
                    <select class="form-select" name="eventType" required>
                        <option value="Author Q&A" <?= $eventType === 'Author Q&A' ? 'selected' : '' ?>>Author Q&A</option>
                        <option value="Book Launch" <?= $eventType === 'Book Launch' ? 'selected' : '' ?>>Book Launch</option>
                        <option value="Reading Challenge" <?= $eventType === 'Reading Challenge' ? 'selected' : '' ?>>Reading Challenge</option>
                        <option value="Book Club Meeting" <?= $eventType === 'Book Club Meeting' ? 'selected' : '' ?>>Book Club Meeting</option>
                        <option value="Writing Workshop" <?= $eventType === 'Writing Workshop' ? 'selected' : '' ?>>Writing Workshop</option>
                        <option value="Virtual Book Reading" <?= $eventType === 'Virtual Book Reading' ? 'selected' : '' ?>>Virtual Book Reading</option>
                        <option value="Book Signing" <?= $eventType === 'Book Signing' ? 'selected' : '' ?>>Book Signing</option>
                        <option value="Panel Discussion" <?= $eventType === 'Panel Discussion' ? 'selected' : '' ?>>Panel Discussion</option>
                        <option value="Cover Reveal" <?= $eventType === 'Cover Reveal' ? 'selected' : '' ?>>Cover Reveal</option>
                        <option value="Fan Contest" <?= $eventType === 'Fan Contest' ? 'selected' : '' ?>>Fan Contest</option>
                    </select>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Attendance Mode*</label>
                    <select class="form-select" name="attendence" required>
                        <option value="In-Person" <?= $attendanceMode === 'In-Person' ? 'selected' : '' ?>>In-Person</option>
                        <option value="Virtual" <?= $attendanceMode === 'Virtual' ? 'selected' : '' ?>>Virtual</option>
                        <option value="Hybrid" <?= $attendanceMode === 'Hybrid' ? 'selected' : '' ?>>Hybrid</option>
                    </select>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Duration* (hours)</label>
                    <input type="number" class="form-control" name="duration" min="1" value="<?= $duration ?>" required>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Event Link (if virtual)</label>
                    <input type="url" class="form-control" name="link" value="<?= $link ?>">
                </div>
            </div>

            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-primary ms-2" name="save_and_publish">Save & Publish</button>
            </div>
        </div>
    </div>
</form>