<?php $this->layout('layouts/app', ['title' => $title, 'active' => $active]) ?>
<?php $this->start('main') ?>

<section id="about" class="about-section">
    <div class="about-header">
        <h1 class="about-heading">About Me</h1>
        <p class="about-subheading">Indie dev. Zero fluff. Results only.</p>
    </div>

    <div class="about-card">
        <div class="about-wrapper">
            <div class="about-flex">
                <div class="about-avatar">
                    <div class="about-avatar-initial">
                        <?= substr('Eheca', 0, 1) ?>
                    </div>
                </div>
                <div class="about-content">
                    <p class="about-paragraph"><?= htmlspecialchars($bio['intro']) ?></p>
                    <p class="about-paragraph"><?= htmlspecialchars($bio['philosophy']) ?></p>
                    <h3 class="about-core-heading">Core values</h3>
                    <ul class="about-core-list">
                        <?php foreach ($bio['qualities'] as $q): ?>
                        <li class="about-core-item"><?= htmlspecialchars($q) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="about-cta-wrapper">
        <a href="/contact" class="btn btn--primary about-cta">Let's work together</a>
    </div>
</section>
<?php $this->stop(); ?>
