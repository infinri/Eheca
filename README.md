# Eheca

A modular, production-ready platform scaffold built with **Symfony 6 (PHP 8.x)** for the backend and **React + TailwindCSS + Vite** for the frontend. Designed for clarity, extensibility, and rapid development.

---

## Features
- **Symfony 6 Backend**: PSR-12 compliant, MySQL via Doctrine ORM, modular service architecture
- **React Frontend**: Vite build, TailwindCSS, SEO and i18n ready
- **Core Modules**: Auth, Routing, Module Loader, Layout Engine, Admin Panel
- **Feature Modules**: Services, Contact, GMB (Google My Business)
- **Environment Management**: .env files (never committed), .env.example for onboarding
- **CI/CD Ready**: GitLab CI pipeline included
- **Obsidian Documentation**: All code and config changes are reflected in Obsidian vault for traceability and onboarding

---

## Getting Started
1. **Clone the repository:**
   ```sh
   git clone https://github.com/infinri/Eheca.git
   cd Eheca
   ```
2. **Install backend dependencies:**
   ```sh
   composer install
   ```
3. **Install frontend dependencies:**
   ```sh
   cd frontend
   npm install
   # or yarn install
   ```
4. **Set up environment variables:**
   - Copy `.env.example` to `.env` and set your values.

5. **Run the app:**
   - Backend: `php bin/console server:run` (or your preferred server)
   - Frontend: `npm run dev` (from frontend directory)

---

## Directory Structure
```
Eheca/
├── modules/
│   ├── Core_Auth/
│   ├── Core_Routing/
│   ├── Core_ModuleLoader/
│   ├── Core_LayoutEngine/
│   ├── Core_AdminPanel/
│   ├── Feature_Services/
│   ├── Feature_Contact/
│   └── Feature_GMB/
├── frontend/
│   ├── src/
│   ├── vite.config.js
│   └── tailwind.config.js
├── .env.example
├── .gitignore
├── .gitlab-ci.yml
└── README.md
```

---

## Documentation
- Project and module-level documentation is maintained in an Obsidian vault (see `Eheca Project/` in your documentation system).
- All changes to code and configuration are reflected in the documentation.

---

## Contributing
- Follow PSR-12 for PHP and standard best practices for React/JS.
- Do not commit `.env` or sensitive files.
- Keep documentation up-to-date with all changes.

---

## License
[MIT](LICENSE) (or specify your preferred license)

---

## Credits
- [Symfony](https://symfony.com/)
- [React](https://react.dev/)
- [TailwindCSS](https://tailwindcss.com/)
- [Vite](https://vitejs.dev/)
