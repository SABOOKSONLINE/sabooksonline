<?php
function renderTable(array $headers, array $rows, string $theme = "primary")
{
    if (empty($rows)) {
?>
        <div class="col-9">
            <div class="alert alert-info">No records found.</div>
        </div>
    <?php
        return;
    }

    $rowBg = "bg-{$theme}-subtle";
    $textColor = "text-{$theme}";

    $rowsToShow = array_slice($rows, 0, 5);
    ?>

    <div class="col-12 col-md-8 col-lg-8 col-xl-9 col-xxl-9 mb-3">
        <div class="table-responsive">
            <table class="table table-striped" style="border-spacing: 0 0.75rem;"> <!-- borderless -->
                <thead>
                    <tr>
                        <?php foreach ($headers as $header): ?>
                            <th class="text-capitalize"><?= htmlspecialchars($header) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rowsToShow as $row): ?>
                        <tr>
                            <?php foreach ($row as $cell): ?>
                                <td class="align-middle p-3">
                                    <?php if (is_array($cell) && isset($cell['type'], $cell['value'])): ?>
                                        <?php if ($cell['type'] === 'image'): ?>
                                            <img src="<?= htmlspecialchars($cell['value']) ?>" class="img-thumbnail" style="max-width:80px;">
                                        <?php elseif ($cell['type'] === 'badge'): ?>
                                            <span class="badge <?= htmlspecialchars($cell['class']) ?>"><?= htmlspecialchars($cell['value']) ?></span>
                                        <?php else: ?>
                                            <?= htmlspecialchars($cell['value']) ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?= htmlspecialchars($cell) ?>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php
}
?>