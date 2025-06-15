<?php $this->layout('layouts/app') ?>

<?php $this->start('main') ?>
<div class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                <?= $this->e($message) ?>
            </h1>
        </div>
    </div>
</div>

<section id="services" class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                What I Can Help With
            </h2>
            <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
                Quick fixes and small tasks that make a big difference
            </p>
        </div>

        <div class="mt-10">
            <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Service 1 -->
                <div class="flex">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Frontend Fixes</h3>
                        <p class="mt-2 text-base text-gray-500">
                            CSS, JavaScript, and responsive design tweaks to make your site look and work great.
                        </p>
                    </div>
                </div>

                <!-- Service 2 -->
                <div class="flex">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Backend Support</h3>
                        <p class="mt-2 text-base text-gray-500">
                            Server-side logic, API integrations, and database optimizations.
                        </p>
                    </div>
                </div>

                <!-- Service 3 -->
                <div class="flex">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Quick Turnaround</h3>
                        <p class="mt-2 text-base text-gray-500">
                            Most tasks completed within 1-3 business days. No endless back-and-forth.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="contact" class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                Get in Touch
            </h2>
            <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                Ready to offload a task? Send me a message and I'll get back to you soon.
            </p>
        </div>

        <div class="mt-10">
            <form id="contact-form" class="max-w-lg mx-auto">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700">How can I help?</label>
                        <textarea id="message" name="message" rows="4" maxlength="1000" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2"></textarea>
                        <p class="text-sm text-gray-500 text-right">Characters: <span id="message-count">0</span></p>
                    </div>
                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Send Message
                        </button>
                    </div>
                </div>
            <div id="form-status" class="hidden mt-4"></div>
            </form>
        </div>
    </div>
</section>

<?php $this->stop(); ?>

