<?php $this->layout('layouts/app') ?>

<?php $this->start('main') ?>

<!-- Hero -->
<section id="hero" aria-labelledby="hero-heading" class="hero-section">
    <div class="hero-wrapper">
        <div class="hero-content">
            <h1 id="hero-heading" class="hero-heading">
                Offload the micro-tasks.<br class="hidden md:inline"> Focus on what really matters.
            </h1>
            <p class="hero-text">Quick, reliable fixes for developers, startups, and growing teams.</p>
        </div>
        <div class="hero-illustration">
            <img src="https://placehold.co/500x350?text=Eheca" alt="Illustration of Eheca sweeping away bugs" class="hero-image">
        </div>
    </div>
</section>

<!-- Services -->
<section id="services" class="services-section">
    <div class="services-wrapper">
        <div class="services-header">
            <h2 id="services-heading" class="services-heading">What I Can Help With</h2>
            <p class="services-subheading">Quick fixes and small tasks that make a big difference</p>
        </div>

        <div class="services-grid">
            <!-- Card -->
            <article class="service-card">
                <div class="service-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                </div>
                <div>
                    <h3 class="service-title">Frontend Fixes</h3>
                    <p class="service-desc">CSS, JavaScript, and responsive tweaks to make your site shine.</p>
                </div>
            </article>

            <!-- Card -->
            <article class="service-card">
                <div class="service-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                </div>
                <div>
                    <h3 class="service-title">Backend Support</h3>
                    <p class="service-desc">API integrations, database tuning, and server-side logic.</p>
                </div>
            </article>

            <!-- Card -->
            <article class="service-card">
                <div class="service-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <h3 class="service-title">Quick Turnaround</h3>
                    <p class="service-desc">Most tasks delivered within 1-3 business days—no endless back-and-forth.</p>
                </div>
            </article>
        </div>
    </div>
</section>

<!-- Why Eheca -->
<section id="why" class="why-section">
    <div class="services-header">
        <h2 id="why-heading" class="why-heading">Why Eheca?</h2>
        <p class="why-subheading">Three reasons developers and founders keep coming back.</p>
        <div class="why-badges">
            <span class="why-badge">Built for devs who don’t have time to babysit bugs.</span>
            <span class="why-badge">Pay only for what you actually need.</span>
            <span class="why-badge">No meetings. No scope creep. Just results.</span>
        </div>
    </div>
    <div class="why-testimonials">
        <figure class="testimonial-card">
            <blockquote class="testimonial-quote">“Eheca fixed our production bug in hours – saved our launch.”</blockquote>
            <figcaption class="testimonial-author">— Alex, Startup CTO</figcaption>
        </figure>
        <figure class="testimonial-card">
            <blockquote class="testimonial-quote">“Fast, clear, and no meetings. Perfect.”</blockquote>
            <figcaption class="testimonial-author">— Maria, Product Manager</figcaption>
        </figure>
    </div>
</section>

<!-- Audience -->
<section id="who" class="audience-section">
    <div class="services-wrapper">
        <div class="services-header">
            <h2 id="who-heading" class="audience-heading">This Is Built for You If…</h2>
        </div>
        <div class="audience-box">
            <ul class="audience-list">
                <li class="audience-item"><span class="text-indigo-600">✔</span> You’re a dev sick of losing time to layout bugs</li>
                <li class="audience-item"><span class="text-indigo-600">✔</span> You run a business but hate tech setup</li>
                <li class="audience-item"><span class="text-indigo-600">✔</span> You just need something done — not discussed to death</li>
            </ul>
            <div class="audience-cta-wrapper">
                <a href="/contact" class="btn btn--primary audience-cta">Offload Your Task Now</a>
            </div>
        </div>
    </div>
</section>

<?php $this->stop(); ?>
