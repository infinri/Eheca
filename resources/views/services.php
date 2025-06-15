<?php $this->layout('layouts/app', ['title' => $title, 'active' => $active]) ?>
<?php $this->start('main') ?>

<section class="max-w-5xl mx-auto px-4 py-12">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Services</h1>
        <p class="text-xl text-gray-600">Clear, focused expertise—exactly where you need it</p>
    </div>

    <div class="grid gap-8 md:grid-cols-2">
        <?php foreach ($services as $category => $items): ?>
        <div class="bg-white rounded-lg shadow-sm border border-gray-100">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center">
                <h2 class="text-xl font-semibold text-gray-900 flex-1"><?= htmlspecialchars($category) ?></h2>
            </div>
            <ul class="px-6 py-4 space-y-3 list-disc list-inside text-gray-700">
                <?php foreach ($items as $item): ?>
                <li><?= htmlspecialchars($item) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="mt-12 text-center">
        <a href="/contact" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-md shadow hover:bg-blue-700">Request a Quote</a>
    </div>
</section>
<?php $this->stop(); ?>
