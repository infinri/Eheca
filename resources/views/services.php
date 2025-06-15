<?php $this->layout('layouts/app', ['title' => $title, 'active' => $active]) ?>
<?php $this->start('main') ?>

<section id="services" class="services-section">
    <div class="services-header">
        <h1 class="services-heading">Services</h1>
        <p class="services-subheading">Clear, focused expertise—exactly where you need it</p>
    </div>

    <div class="services-grid">
        <?php foreach ($services as $category => $items): ?>
        <div class="service-card">
            <div class="service-card-header">
                <h2 class="service-card-title"><?= htmlspecialchars($category) ?></h2>
            </div>
            <ul class="service-list">
                <?php foreach ($items as $item): ?>
                <li class="service-item"><?= htmlspecialchars($item) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="services-cta-wrapper">
        <a href="/contact" class="btn btn--primary services-cta">Request a Quote</a>
    </div>
</section>
<?php $this->stop(); ?>
