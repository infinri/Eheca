<?php $this->layout('layouts/app', ['title' => $title, 'active' => $active]) ?>
<?php $this->start('main') ?>

<section class="max-w-4xl mx-auto px-4 py-12">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">About Me</h1>
        <p class="text-xl text-gray-600">Indie dev. Zero fluff. Results only.</p>
    </div>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-8 md:p-12">
            <div class="md:flex">
                <div class="md:flex-shrink-0 md:mr-8 mb-6 md:mb-0">
                    <div class="h-40 w-40 mx-auto bg-gradient-to-br from-blue-100 to-purple-100 rounded-full flex items-center justify-center text-5xl font-bold text-blue-600">
                        <?= substr('Eheca', 0, 1) ?>
                    </div>
                </div>
                <div class="flex-1">
                    <p class="text-lg text-gray-700 mb-6"><?= htmlspecialchars($bio['intro']) ?></p>
                    <p class="text-gray-600 mb-6"><?= htmlspecialchars($bio['philosophy']) ?></p>
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Core values</h3>
                    <ul class="list-disc list-inside space-y-2">
                        <?php foreach ($bio['qualities'] as $q): ?>
                        <li class="text-gray-700"><?= htmlspecialchars($q) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-12 text-center">
        <a href="/contact" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-md shadow hover:bg-blue-700">Let's work together</a>
    </div>
</section>
<?php $this->stop(); ?>
