<?php $this->layout('layouts/default', ['title' => $title, 'active' => $active]) ?>
<?php $this->start('main') ?>

<section class="max-w-4xl mx-auto px-4 py-12">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">FAQ &amp; How I Work</h1>
        <p class="text-xl text-gray-600">Answers to common questions about my process and what to expect</p>
    </div>

    <div class="space-y-6 max-w-3xl mx-auto" x-data="{}">
        <?php foreach ($faqs as $idx => $faq): ?>
        <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100" x-data="{ open: <?= $idx === 0 ? 'true' : 'false' ?> }">
            <button class="w-full px-6 py-4 flex justify-between items-center text-left focus:outline-none" @click="open = !open" :aria-expanded="open">
                <h3 class="text-lg font-medium text-gray-900"><?= htmlspecialchars($faq['question']) ?></h3>
                <svg class="h-5 w-5 text-gray-500 transform transition-transform duration-200" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.06l3.71-3.83a.75.75 0 111.08 1.04l-4.25 4.39a.75.75 0 01-1.08 0L5.25 8.27a.75.75 0 01-.02-1.06z" clip-rule="evenodd" />
                </svg>
            </button>
            <div x-show="open" x-collapse class="px-6 pb-4 text-gray-600">
                <?= nl2br(htmlspecialchars($faq['answer'])) ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="mt-12 bg-blue-50 rounded-lg p-6 text-center">
        <h3 class="text-lg font-medium text-gray-900 mb-2">Still have questions?</h3>
        <p class="text-gray-600 mb-4">I'm here to help! Get in touch and I'll get back to you ASAP.</p>
        <a href="/contact" class="inline-flex items-center px-6 py-3 text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Contact Me</a>
    </div>
</section>
<?php $this->stop(); ?>
