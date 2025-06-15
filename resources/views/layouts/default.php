<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $this->e($title ?? 'Eheca') ?></title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap" rel="stylesheet">
        <link href="/css/app.css" rel="stylesheet">
        <script src="/js/app.js" defer></script>
    </head>
    <body class="site-body">
        <nav class="navbar" x-data="{ open: false }">
            <div class="navbar-container">
                <div class="navbar-inner">
                    <button class="menu-toggle" @click="open = !open" aria-label="Toggle menu">
                        <svg class="icon-menu" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div class="logo-wrapper">
                        <span class="logo">Eheca</span>
                    </div>
                    <div :class="{'navbar-links': true, 'navbar-links--open': open}" class="navbar-links">
                        <a href="/" class="nav-link <?= ($active ?? '') === 'home' ? 'nav-link--active' : '' ?>">
                            Home
                        </a>
                        <a href="/about" class="nav-link <?= ($active ?? '') === 'about' ? 'nav-link--active' : '' ?>">
                            About
                        </a>
                        <a href="/services" class="nav-link <?= ($active ?? '') === 'services' ? 'nav-link--active' : '' ?>">
                            Services
                        </a>
                        <a href="/examples" class="nav-link <?= ($active ?? '') === 'examples' ? 'nav-link--active' : '' ?>">
                            Examples
                        </a>
                        <a href="/faq" class="nav-link <?= ($active ?? '') === 'faq' ? 'nav-link--active' : '' ?>">
                            FAQ
                        </a>
                        <a href="/contact" class="nav-link <?= ($active ?? '') === 'contact' ? 'nav-link--active' : '' ?>">
                            Contact
                        </a>
                    </div>
                </div>
            </div>
        </nav>
        <div class="site-wrapper">
            <main class="site-main">
                <?= $this->section('main') ?: '' ?>
            </main>
        </div>
        <footer class="site-footer">
            <div class="footer-container">
                <p>&copy; <?= date('Y') ?> Eheca. All rights reserved.</p>
                <nav class="footer-links">
                    <a href="/privacy" class="footer-link">Privacy</a>
                    <a href="/terms" class="footer-link">Terms</a>
                    <a href="/contact" class="footer-link">Contact</a>
                </nav>
            </div>
        </footer>
    </body>
</html>
