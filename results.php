<?php require_once '../views/layouts/header.php'; ?>

<div class="container section-padding">
    <h2 class="section-title">Search Results for "
        <?= htmlspecialchars($query) ?>"
    </h2>

    <?php if (empty($results)): ?>
        <p>No results found.</p>
    <?php else: ?>
        <div class="grid gap-4">
            <?php foreach ($results as $res): ?>
                <div class="card">
                    <h4>
                        <?= $res['title'] ?> <span class="badge"
                            style="background: #E5E7EB; font-size: 0.8rem; vertical-align: middle;">
                            <?= $res['type'] ?>
                        </span>
                    </h4>
                    <p class="text-muted">
                        <?= $res['description'] ?>
                    </p>
                    <a href="<?= $res['link'] ?>" class="btn btn-primary btn-sm mt-2">View</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../views/layouts/footer.php'; ?>