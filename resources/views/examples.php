<?php $this->layout('layouts/app', ['title' => $title, 'active' => $active]) ?>
<?php $this->start('main') ?>

<section id="examples" class="examples-section">
    <div class="examples-header">
        <h1 class="examples-heading">Example Tasks</h1>
        <p class="examples-subheading">A taste of the quick wins I've delivered.</p>
    </div>

    <div class="examples-grid">
        <?php foreach ($examples as $ex): ?>
        <div class="example-card">
            <div class="example-card-header">
                <h2 class="example-card-title">
                    <?= htmlspecialchars($ex['title']) ?>
                </h2>
                <span class="example-duration">
                    <?= htmlspecialchars($ex['time']) ?>
                </span>
            </div>
            <div class="example-card-body">
                <p class="example-description">
                    <?= htmlspecialchars($ex['description']) ?>
                </p>
                <p class="example-result">Result: <?= htmlspecialchars($ex['result']) ?></p>
                <div class="example-tech-wrapper">
                    <ul class="example-tech-list">
                        <?php foreach ($ex['tech'] as $tech): ?>
                        <li class="example-tech-item">
                            <?= htmlspecialchars($tech) ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="examples-cta-wrapper">
        <a href="/contact" class="btn btn--primary examples-cta">Got a challenge? Let's solve it.</a>
    </div>
</section>
<?php $this->stop(); ?>
