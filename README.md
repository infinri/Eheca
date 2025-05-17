# Eheca

A modular, production-ready platform scaffold built with **Symfony 6 (PHP 8.x)** for the backend and **React + TailwindCSS + Vite** for the frontend. Designed for clarity, extensibility, and rapid development.

---

## Features
- **Symfony 6 Backend**: PSR-12 compliant, MySQL via Doctrine ORM, modular service architecture
- **React Frontend**: Vite build, TailwindCSS, SEO and i18n ready
- **Authentication**: JWT-based authentication with password reset functionality
- **Security**: CSRF protection, password hashing, and secure session management
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
   - Configure your mailer settings in `.env` for password reset functionality
   - Generate JWT keys:
     ```sh
     mkdir -p config/jwt
     openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
     openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
     ```

5. **Run the app:**
   - Backend: `php bin/console server:run` (or your preferred server)
   - Frontend: `npm run dev` (from frontend directory)

## Authentication

### User Registration
- Endpoint: `POST /api/auth/register`
- Required fields: `email`, `password`, `first_name`, `last_name`
- Success: Returns JWT token

### User Login
- Endpoint: `POST /api/auth/login`
- Required fields: `email`, `password`
- Success: Returns JWT token

### Password Reset
1. Request password reset email: `POST /api/auth/reset-password/request`
2. Check email for reset link
3. Submit new password: `POST /api/auth/reset-password/reset`

### Protected Routes
- Add `Authorization: Bearer YOUR_JWT_TOKEN` header to access protected routes
- Example:
  ```http
  GET /api/protected-route
  Authorization: Bearer YOUR_JWT_TOKEN
  ```

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

### API Documentation
API documentation is available at `/api/docs` when running the application in development mode.

### Authentication Flow
1. User registers or logs in to receive a JWT token
2. Token is stored in the frontend (localStorage or httpOnly cookie)
3. Token is sent with each request in the Authorization header
4. Expired tokens trigger automatic logout

### Security Notes
- Passwords are hashed using Argon2i
- JWT tokens have a limited lifetime
- Password reset tokens expire after 1 hour
- Rate limiting is in place for authentication endpoints

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
