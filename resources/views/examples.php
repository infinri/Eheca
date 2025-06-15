<?php $this->layout('layouts/app', ['title' => $title, 'active' => $active]) ?>
<?php $this->start('main') ?>

<section class="max-w-5xl mx-auto px-4 py-12">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Example Tasks</h1>
        <p class="text-xl text-gray-600">A taste of the quick wins I've delivered.</p>
    </div>

    <div class="grid gap-8 md:grid-cols-2">
        <?php foreach ($examples as $ex): ?>
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 flex flex-col">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900 flex-1 mr-2">
                    <?= htmlspecialchars($ex['title']) ?>
                </h2>
                <span class="inline-block bg-blue-600 text-white text-sm px-3 py-1 rounded-full shadow-sm whitespace-nowrap">
                    <?= htmlspecialchars($ex['time']) ?>
                </span>
            </div>
            <div class="p-6 flex-1 flex flex-col">
                <p class="text-gray-700 mb-4 flex-1">
                    <?= htmlspecialchars($ex['description']) ?>
                </p>
                <p class="text-green-700 font-medium mb-4">Result: <?= htmlspecialchars($ex['result']) ?></p>
                <div class="mt-auto">
                    <ul class="flex flex-wrap gap-2 text-sm">
                        <?php foreach ($ex['tech'] as $tech): ?>
                        <li class="bg-gray-200 text-gray-800 px-2 py-1 rounded">
                            <?= htmlspecialchars($tech) ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="mt-12 text-center">
        <a href="/contact" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-md shadow hover:bg-blue-700">Got a challenge? Let's solve it.</a>
    </div>
</section>
<?php $this->stop(); ?>
