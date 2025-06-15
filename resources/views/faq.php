<?php $this->layout('layouts/default', ['title' => $title, 'active' => $active]) ?>
<?php $this->start('main') ?>

<section id="faq" class="faq-section">
    <div class="faq-header">
        <h1 class="faq-heading">FAQ &amp; How I Work</h1>
        <p class="faq-subheading">Answers to common questions about my process and what to expect</p>
    </div>

    <div class="faq-accordion" x-data="{}">
        <?php foreach ($faqs as $idx => $faq): ?>
        <div class="faq-item" x-data="{ open: <?= $idx === 0 ? 'true' : 'false' ?> }">
            <button class="faq-question" @click="open = !open" :aria-expanded="open">
                <h3 class="faq-question-text"><?= htmlspecialchars($faq['question']) ?></h3>
                <svg class="faq-icon" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.06l3.71-3.83a.75.75 0 111.08 1.04l-4.25 4.39a.75.75 0 01-1.08 0L5.25 8.27a.75.75 0 01-.02-1.06z" clip-rule="evenodd" />
                </svg>
            </button>
            <div x-show="open" x-collapse class="faq-answer">
                <?= nl2br(htmlspecialchars($faq['answer'])) ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="faq-cta-box">
        <h3 class="faq-cta-heading">Still have questions?</h3>
        <p class="faq-cta-text">I'm here to help! Get in touch and I'll get back to you ASAP.</p>
        <a href="/contact" class="btn btn--primary faq-cta">Contact Me</a>
    </div>
</section>
<?php $this->stop(); ?>
