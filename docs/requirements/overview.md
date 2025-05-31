# Project Requirements

## 1. Project Overview

| Category | High-Level Requirement | Why It Matters |
|----------|------------------------|----------------|
| **Architecture** | • Modular, Composer-managed packages under `modules/`<br>• Symfony 6 with PHP 8.x<br>• Core_* and Feature_* module structure<br>• Service-oriented DI & event system | Enables maintainable, scalable architecture with clear separation of concerns and easy feature toggling. |
| **Core Features** | • Authentication & User Management (Core_Auth)<br>• Admin Dashboard (Core_AdminPanel)<br>• Dynamic Routing (Core_Routing)<br>• Module Loading (Core_ModuleLoader)<br>• Theming (Core_LayoutEngine) | Provides essential functionality while maintaining flexibility for future expansion. |
| **Frontend** | • React 18+ with TypeScript<br>• Vite for build tooling<br>• TailwindCSS for styling<br>• Responsive design by default | Modern, performant frontend with excellent developer experience. |
| **Performance** | • ≤ 200 ms TTFB on a $5 VPS<br>• ≥ 95 Lighthouse score (mobile)<br>• Redis caching layer | Ensures fast, responsive user experience even on budget hosting. |
| **Scalability** | • Stateless app containers (Docker)<br>• MySQL/MariaDB with read-replica support<br>• Redis for cache/session/queues | Horizontal scaling capabilities for growing applications. |
| **Developer Experience** | • `make init` for local setup<br>• Vite hot-reload for assets<br>• Pest PHP & Jest testing<br>• Code quality tools (PHPStan, ESLint) | Reduces onboarding time and maintains code quality. |
| **Security** | • OWASP Top 10 protection<br>• CSRF & CSP headers<br>• Rate limiting<br>• Authentication flows (JWT/OAuth2) | Enterprise-grade security out of the box. |
| **Documentation** | • Obsidian knowledge base<br>• API documentation<br>• Module development guides | Comprehensive resources for developers and maintainers. |

**Definition of Done (v1.0)**: All features implemented, tested, documented, and passing CI/CD pipelines.

## 2. Functional Requirements

### 2.1 Core Modules

#### Authentication & User Management
- [ ] User registration and authentication
- [ ] Role-based access control
- [ ] Password reset flow
- [ ] Email verification
- [ ] Social login integration

#### Admin Dashboard
- [ ] User management
- [ ] System configuration
- [ ] Module management
- [ ] Audit logs

#### Dynamic Routing 
- [ ] Route definition and resolution
- [ ] Route parameters
- [ ] Route authentication
- [ ] Route caching

### 2.2 User Stories

#### End Users
- [ ] As a user, I want to register an account so I can access protected features
- [ ] As a user, I want to log in to my account to manage my profile
- [ ] As a user, I want to reset my password if I forget it

#### Administrators
- [ ] As an admin, I want to manage user accounts
- [ ] As an admin, I want to configure system settings
- [ ] As an admin, I want to view system logs

## 3. Non-Functional Requirements

### 3.1 Performance
- [ ] Page load time < 2s (90th percentile)
- [ ] API response time < 500ms (95th percentile)
- [ ] Support for 1000+ concurrent users
- [ ] Database query optimization

### 3.2 Security
- [ ] OWASP Top 10 compliance
- [ ] Regular security audits
- [ ] Automated dependency updates
- [ ] Rate limiting and DDoS protection
- [ ] Data encryption at rest and in transit

### 3.3 Usability
- [ ] WCAG 2.1 AA compliance
- [ ] Mobile-responsive design
- [ ] Browser support: Latest Chrome, Firefox, Safari, Edge
- [ ] Progressive enhancement where possible

## 4. Technical Requirements

### 4.1 Backend
- PHP 8.2+
- Symfony 6.3+
- MySQL 8.0+ / MariaDB 10.6+
- Redis 6.0+
- Composer 2.0+
- Xdebug for development

### 4.2 Frontend
- React 18+
- TypeScript 5.0+
- Vite 4.0+
- TailwindCSS 3.0+
- ESLint + Prettier

### 4.3 Development Environment
- Docker + Docker Compose
- Makefile for common tasks
- Local SSL support
- MailHog for email testing

## 5. Constraints
- Must support PHP 8.2+
- Must support MySQL 8.0+ / MariaDB 10.6+
- Must be deployable on standard LAMP/LEMP stacks
- Must include comprehensive test coverage

## 6. Assumptions
- Developers are familiar with modern PHP and JavaScript
- Deployment will be on Linux servers
- Third-party services (email, storage) will be used where appropriate
- Backward compatibility will be maintained within major versions

## 7. Open Questions
- [ ] Which payment processors to support initially?
- [ ] What are the specific GDPR requirements for target markets?
- [ ] What level of browser compatibility is required?

## 8. Documentation
- [ ] Module development guide
- [ ] API documentation
- [ ] Deployment guide
- [ ] Contribution guidelinesj
- [ ] Security best practices

## 2. Functional Requirements  

### 2.1 Core Features (MVP scope)  

| # | Feature | Description | Key Sub-Features |
|---|---------|-------------|------------------|
| **F-01** | **Authentication & Account** | Secure customer registration / login / forgotten-password flow. | Email verification · Optional Google/GitHub OAuth · 2-FA (TOTP) |
| **F-02** | **Customer Profile** | Self-service area for personal data and addresses. | Edit details · Manage addresses · Upload avatar |
| **F-03** | **Catalog** | Browsable product catalog with variant support. | Category tree · Filters · Product details page |
| **F-04** | **Search** | Keyword search with ranking & filters. | Auto-complete (stretch) |
| **F-05** | **Cart & Wishlist** | Persistent shopping cart and optional wishlist. | Qty update · Remove · Save for later |
| **F-06** | **Checkout & Payment** | Guest or account checkout with Stripe/Braintree. | Shipping + billing capture · Coupons |
| **F-07** | **Order History** | Customers track and reorder purchases. | PDF invoice · Re-order |
| **F-08** | **Notifications** | Transactional email pipeline. | Order confirmation · Status updates |
| **F-09** | **Compliance & Consent** | GDPR/CCPA cookie banner and data-export. | Cookie preferences · Data download |
| **F-10** | **Contact & Support** | Public contact form with autoresponder. | Spam captcha · Auto-ack email |

> **Out of MVP (but designed for):** Loyalty points, returns portal, gift cards, product reviews, multi-language currency switching.  

### 2.2 User Stories  

Customer stories are tracked in **`Customer-Facing User Stories.md`** (latest count: 18 epics, 60 stories).  
Each feature above maps 1-to-n stories with **Must / Should / Could** priority tags.  

## 3. Non-Functional Requirements  

### 3.1 Performance  
| Metric | Target |
|--------|--------|
| Time-to-First-Byte (TTFB) | ≤ 200 ms on $5 VPS (1 vCPU, 1 GB RAM) |
| P95 API response | ≤ 300 ms under 1 000 concurrent users |
| Front-end Lighthouse (mobile) | ≥ 95 performance score |

### 3.2 Security  
- **Authentication:** JWT (Paseto) bearer tokens + optional 2-FA (TOTP).  
- **Data-in-transit:** TLS 1.3 only.  
- **Data-at-rest:** Full-disk encryption on DB volume; salted bcrypt passwords.  
- **App Hardening:** CSP, HSTS, X-Frame-Options, rate-limit (100/min/IP).  
- **Compliance:** GDPR, CCPA, PCI DSS out-of-scope via Stripe/Braintree redirect.  

### 3.3 Usability  
- **Accessibility:** WCAG 2.1 AA minimum.  
- **Browser Support:** Evergreen (Chrome, Edge, Safari, Firefox) + iOS/Android WebView (≥ 2 yrs old).  
- **Mobile-First:** Responsive layout tested down to 360 px width.  

## 4. Technical Requirements  

### 4.1 Backend  
| Item | Spec |
|------|------|
| Language | PHP 8.3+ (strict types) |
| Framework | Symfony components (Routing, DI, EventDispatcher, Messenger, Console) in **Eheca Kernel** |
| API | REST `/api/v1/*` (JSON) + optional GraphQL; documented via OpenAPI 3. |
| Database | MariaDB 10.6 (InnoDB) with Doctrine DBAL + Migrations; read-replica ready. |
| Caching / Queues | Redis 6 (sessions, PSR-6 cache, Messenger transport). |
| Tests | PestPHP for units; Playwright for e2e. |

### 4.2 Frontend  
| Item | Spec |
|------|------|
| Framework | Vue 3 (Composition API) + Alpine.js islands |
| Styling | Tailwind CSS v3 (JIT) + Headless UI |
| Build Tool | Vite (HMR in dev, hashed assets for prod) |
| Responsive | CSS clamp()/fluid scales; prefers-color-scheme dark ready |
| Browser Compatibility | Last 2 major versions of modern browsers; graceful degradation to ES2017. |

## 5. Constraints  
- **Technical:** Must run on a single-node Docker Compose stack for local dev; prod may be multi-node but container images must stay < 200 MB.  
- **Timeline:** MVP feature-complete in **12 weeks** from kickoff; beta client live by **week 16**.  
- **Budget:** Capex ≤ $1 500 for initial infra + tooling; Opex ≤ $50/mo for staging/prod during beta.  
- **Resources:** 1 full-time developer (Manuel), 1 part-time designer, AI assistant (Alexander).  

## 6. Assumptions  
1. Target clients are SMBs with ≤ 10 k SKUs and ≤ 50 k monthly visitors.  
2. Stripe/Braintree will remain the sole payment gateway for MVP (no native PayPal).  
3. All email sending will route through SendGrid free-tier during beta.  
4. No third-party CMS (e.g., WordPress) will be integrated—Eheca CMS module covers all content pages.  

## 7. Open Questions  
1. **Search Engine Choice:** MeiliSearch vs. native SQL LIKE – which do we bundle by default?  
2. **Loyalty Program Scope:** Points accrual and redemption rules—include in v1 or deliver as premium module?  
3. **Return Merchandise Authorization (RMA):** Build front-end customer flow now or defer to Admin sprint?  
4. **Hosting Target:** Stick with Docker Compose on a single VPS for prod, or plan early for Kubernetes/ECS?  
5. **Legal Review:** Do we need external counsel to review cookie-consent, privacy policy templates?  

### ✅ End of Requirements v0.9  
*(Update sections 5–7 once open questions are resolved and constraints are signed off by stakeholders.)*
