# Technical Requirements

## 1. System Architecture

### 1.1 High-Level Architecture

Eheca ships as a **container-native micro-stack**: one `docker-compose.yml` spins up every service for local development, while each component can be scaled or swapped independently in production.

```mermaid
graph TD
  subgraph Client
    Browser["🌐 Browser (Vue + Alpine, Tailwind CSS)"]
  end

  subgraph Edge
    Nginx["🔒 Nginx (TLS, static assets)"]
  end

  subgraph App
    PHP["⚙️ PHP-FPM 8.4\nEheca Kernel\n(Symfony DI + Events)"]
    Worker["⏱️ CLI Worker\n(Symfony Messenger)"]
  end

  subgraph Data
    Maria[("🗄️ MariaDB 10.6+")]
    Redis[("⚡ Redis 6")\n(cache, session, queues)]
  end

  subgraph Integrations
    Stripe(["💳 Stripe / Braintree"])
    Mail(["📧 SendGrid / SES"])
    Search(["🔍 MeiliSearch (optional)"])
  end

  subgraph CI/CD
    GitHub["GitHub Actions\n(build + test)"] --> Registry(("Docker Registry"))
    Registry --> Prod["Prod Server / K8s"]
  end

  Browser -->|HTTPS| Nginx
  Nginx -->|FastCGI| PHP
  PHP -->|SQL| Maria
  PHP -->|Cache / Session| Redis
  PHP -->|Enqueue Job| Redis
  Redis --> Worker
  Worker -->|SQL| Maria
  PHP --> Stripe
  PHP --> Mail
  PHP --> Search
```

### 1.2 Components

#### Overview

The following table outlines the components of the Eheca system, their respective tech stacks, and responsibilities.

| Tier | Component | Tech Stack | Responsibilities |
|------|-----------|------------|------------------|
| **Frontend** | • **Vue 3** (islands architecture)<br>• **Alpine.js**<br>• **Tailwind CSS**<br>• **Vite** | • Vue 3 Composition API<br>• Server-side rendering<br>• Client-side hydration<br>• Hot Module Replacement | • Render server-side pages with client-side hydration for interactive zones<br>• Handle UI interactions and state management<br>• Provide responsive, accessible components<br>• Optimize asset delivery and performance |
| **Backend** | • **PHP 8.4+**<br>• **Eheca Kernel**<br>• **Symfony Components** | • Dependency Injection<br>• Event Dispatcher<br>• Console Commands<br>• Messenger | • Process business logic and API requests<br>• Manage module lifecycle<br>• Handle background jobs and queues<br>• Enforce security policies |
| **Database** | • **MariaDB 10.6+**<br>• **Doctrine DBAL**<br>• **Migrations** | • ACID Compliance<br>• Read Replicas<br>• Connection Pooling | • Store and retrieve application data<br>• Ensure data consistency and integrity<br>• Support complex queries and transactions |
| **External Services** | • **Stripe/Braintree**<br>• **SendGrid/SES**<br>• **MeiliSearch**<br>• **Prometheus/Grafana**<br>• **Cloudflare** | • REST APIs<br>• Webhooks<br>• SDKs | • Handle payments and subscriptions<br>• Send transactional emails<br>• Provide full-text search<br>• Monitor application metrics<br>• Secure and accelerate content delivery |

## 2. Technology Stack

### 2.1 Frontend

#### Core Technologies
- **Framework**: Vue 3 (Composition API, islands architecture)
- **Lightweight Interactions**: Alpine.js
- **Bundler/Dev Server**: Vite
- **State Management**: Pinia
- **Styling**: Tailwind CSS 3 (JIT)
- **UI Components**: Headless UI
- **Testing**:
  - Unit/Component: Vitest + Vue Testing Library
  - E2E: Playwright

#### Key Features
- **Islands Architecture**: Server-side rendering with client-side hydration of interactive components
- **Developer Experience**:
  - Hot Module Replacement (HMR)
  - TypeScript support
  - Zero-config production builds
- **Performance**:
  - Code splitting
  - Lazy loading of components
  - Optimized production builds

### 2.2 Backend

#### Core Technologies
- **Language**: PHP 8.4+
- **Framework**: Eheca Kernel (built on Symfony components)
- **Database**: MariaDB 10.6+
- **Cache/Queue**: Redis 6+
- **Search**: MeiliSearch (optional)

#### Components
1. **Core Framework**
   - Symfony Routing
   - Dependency Injection
   - Event Dispatcher
   - Console Commands
   - Messenger (async processing)

2. **API Layer**
   - REST (versioned `/api/v1`)
   - Optional GraphQL endpoint
   - OpenAPI documentation
   - Request/Response DTOs with Symfony Serializer

3. **Authentication & Authorization**
   - JWT (Paseto) for API authentication
   - Session-based auth for web
   - Role-Based Access Control (RBAC)
   - OAuth2/OIDC support (via module)

4. **Data Layer**
   - Doctrine DBAL for database abstraction
   - Schema migrations
   - Optional ORM support (Doctrine ORM/Cycle ORM)
   - Read replicas for scaling

5. **Caching & Performance**
   - Redis for:
     - Application cache (PSR-6/PSR-16)
     - Session storage
     - Message queues
   - OPcache for bytecode caching
   - HTTP caching via Nginx/Cloudflare

## 3. API Specifications

### 3.1 REST API

#### Base URL
```
https://api.example.com/v1
```

#### Authentication
- **Type**: Bearer token (JWT)
- **Header**: `Authorization: Bearer <token>`
- **Token Lifetime**: 24 hours (refresh token available)

#### Rate Limiting
- **Anonymous**: 60 requests/minute
- **Authenticated**: 1000 requests/minute
- **Headers**:
  - `X-RateLimit-Limit`: Request limit
  - `X-RateLimit-Remaining`: Remaining requests
  - `X-RateLimit-Reset`: Reset timestamp

### 3.2 Endpoints

#### Users

| Method   | Endpoint          | Description                         | Auth Required      |
|----------|-------------------|-------------------------------------|-------------------|
| `GET`    | `/users`          | List all users                      | Admin only         |
| `POST`   | `/users`          | Register a new user                 | No                |
| `GET`    | `/users/{id}`     | Get user details                    | Self or Admin      |
| `PUT`    | `/users/{id}`     | Update user information             | Self or Admin      |
| `DELETE` | `/users/{id}`     | Delete user account                 | Admin only         |
| `POST`   | `/users/login`    | Authenticate user                   | No                |
| `POST`   | `/users/refresh`  | Refresh authentication token         | Requires valid JWT |

#### Products

| Method   | Endpoint               | Description                         | Auth Required |
|----------|------------------------|-------------------------------------|---------------|
| `GET`    | `/products`            | List all products (paginated)       | No            |
| `GET`    | `/products/search`     | Search products by criteria         | No            |
| `POST`   | `/products`            | Create a new product                | Admin only    |
| `GET`    | `/products/{id}`       | Get product details                 | No            |
| `PUT`    | `/products/{id}`       | Update product information          | Admin only    |
| `DELETE` | `/products/{id}`       | Delete a product                    | Admin only    |
| `GET`    | `/products/categories` | List all product categories         | No            |
| `GET`    | `/products/category/{id}` | List products by category        | No            |

#### Admin User

| Method   | Endpoint                     | Description                                   | Auth Required |
|----------|------------------------------|-----------------------------------------------|---------------|
| `GET`    | `/admin/users`              | List all admin users (paginated)              | Super Admin   |
| `POST`   | `/admin/user`               | Create a new admin user                       | Super Admin   |
| `GET`    | `/admin/user/{id}`          | Get admin user details                       | Self or Admin |
| `PUT`    | `/admin/user/{id}`          | Update admin user information                 | Self or Admin |
| `DELETE` | `/admin/user/{id}`          | Deactivate an admin account                  | Super Admin   |
| `POST`   | `/admin/user/{id}/roles`    | Assign roles to admin user                   | Super Admin   |
| `DELETE` | `/admin/user/{id}/roles`    | Remove roles from admin user                 | Super Admin   |
| `POST`   | `/admin/user/invite`        | Send invitation to new admin                 | Super Admin   |
| `POST`   | `/admin/user/{id}/status`   | Update admin status (active/inactive)        | Super Admin   |

> **Note**: All admin user endpoints are prefixed with `/admin/user` (singular) except for the list endpoint which remains `/admin/users` (plural).

### 3.3 Data Models

#### User
```typescript
interface User {
  id: string;                // UUID v4
  email: string;             // User's email (unique)
  firstName: string;         // User's first name
  lastName: string;          // User's last name
  isActive: boolean;        // Account status
  createdAt: string;         // ISO 8601 timestamp
  updatedAt: string;         // ISO 8601 timestamp
}
```

#### Admin User

```typescript
interface AdminUser {
  id: string;                // UUID v4
  email: string;             // Admin's email (must be unique)
  firstName: string;         // Admin's first name
  lastName: string;          // Admin's last name
  avatar?: string;           // Optional profile picture URL
  phoneNumber?: string;      // Contact number with country code
  timezone: string;          // User's timezone (e.g., 'America/New_York')
  locale: string;           // Preferred language/locale (e.g., 'en-US')
  
  // Authentication
  emailVerified: boolean;    // Whether email has been verified
  lastLoginAt?: string;      // ISO 8601 timestamp of last login
  lastLoginIp?: string;      // IP address of last login
  failedLoginAttempts: number; // Count of failed login attempts
  
  // Role-based access
  roles: string[];           // Array of role IDs or keys
  permissions: string[];     // Flattened list of all permissions from roles
  isSuperAdmin: boolean;     // Whether user has full system access
  
  // Status
  status: 'active' | 'inactive' | 'suspended' | 'invited';
  statusReason?: string;     // Reason for status change (e.g., 'suspended: too many failed attempts')
  
  // Metadata
  metadata?: Record<string, any>; // Additional custom fields
  
  // Timestamps
  createdAt: string;         // ISO 8601 timestamp
  updatedAt: string;         // ISO 8601 timestamp
  lastPasswordChangedAt?: string; // ISO 8601 timestamp
  
  // Audit fields
  createdBy?: string;        // ID of admin who created this account
  updatedBy?: string;        // ID of admin who last updated this account
}

// Example of admin creation payload
interface CreateAdminUserDto {
  email: string;
  firstName: string;
  lastName: string;
  phoneNumber?: string;
  roles?: string[];         // Array of role IDs
  sendInvite?: boolean;     // Whether to send invitation email
  status?: 'active' | 'invited'; // Default status for new admins
}

// Example of admin update payload
interface UpdateAdminUserDto {
  firstName?: string;
  lastName?: string;
  phoneNumber?: string | null;
  timezone?: string;
  locale?: string;
  status?: 'active' | 'inactive' | 'suspended';
  statusReason?: string | null;
  metadata?: Record<string, any>;
}
```

#### Product
```typescript
interface Product {
  id: string;                // UUID v4
  sku: string;               // Unique product identifier
  name: string;              // Product name
  description: string;       // Product description (markdown supported)
  price: number;            // Price in smallest currency unit (e.g., cents)
  currency: string;         // ISO 4217 currency code (e.g., "USD")
  stock: number;            // Available quantity
  isActive: boolean;         // Product visibility
  categories: string[];      // Category IDs
  images: string[];          // Array of image URLs
  metadata: Record<string, any>; // Additional product attributes
  createdAt: string;         // ISO 8601 timestamp
  updatedAt: string;         // ISO 8601 timestamp
}
```

## 4. Performance Requirements

### 4.1 Response Times
- **API Response Time**:
  - P95: < 200ms
  - P99: < 500ms
  - Max: < 2s
- **Page Load Time**:
  - First Contentful Paint (FCP): < 1s
  - Largest Contentful Paint (LCP): < 2s
  - Time to Interactive (TTI): < 3s

### 4.2 Availability & Reliability
- **Uptime**: 99.9% ("three nines")
- **Error Rate**: < 0.1% of requests
- **Recovery Time Objective (RTO)**: < 15 minutes
- **Recovery Point Objective (RPO)**: < 5 minutes

### 4.3 Scalability
- **Concurrent Users**:
  - Expected: 10,000 concurrent users
  - Peak: 50,000 concurrent users
- **Throughput**:
  - API: 1,000 requests/second
  - Database: 5,000 queries/second
- **Data Volume**:
  - Expected: 1M records
  - Maximum: 100M records

### 4.4 Caching Strategy
- **Browser**:
  - Static assets: 1 year (with cache busting)
  - API responses: 5 minutes
- **CDN**:
  - Static content: 1 hour
  - Dynamic content: 1 minute
- **Application**:
  - Redis cache with 1-hour TTL
  - Cache invalidation on write operations

## 5. Security Requirements

### 5.1 Authentication & Authorization

| Requirement                     | Implementation Details                                                                 |
|---------------------------------|---------------------------------------------------------------------------------------|
| **Authentication**             | JWT (Paseto) with 24-hour expiration and refresh tokens                               |
| **Password Security**          | Argon2id with minimum 12 characters, enforcing complexity requirements               |
| **Multi-Factor Auth**          | TOTP (Time-based One-Time Password) support for sensitive operations                  |
| **OAuth2 / OIDC**              | Support for Google, GitHub, and other OAuth2 providers                                |
| **Role-Based Access Control**  | Fine-grained permissions with role inheritance and resource-based access control     |
| **Session Management**         | Secure, HTTP-only cookies with SameSite=Strict and Secure flags                      |
| **Rate Limiting**              | Tiered rate limiting based on endpoint sensitivity and user role                      |

### 5.2 Data Protection

| Category              | Implementation Details                                                                 |
|-----------------------|---------------------------------------------------------------------------------------|
| **Encryption**        | • AES-256 for data at rest<br>• TLS 1.3 for data in transit<br>• Encrypted backups |
| **Database Security** | • Row-level security<br>• Field-level encryption for PII<br>• Automatic data masking in logs |
| **API Security**      | • Strict CORS policies<br>• Request validation<br>• Input sanitization |
| **Secrets Management**| • Environment variables for dev<br>• HashiCorp Vault for production secrets |

### 5.3 Security Headers

| Header                         | Value                                                                 |
|--------------------------------|-----------------------------------------------------------------------|
| `Content-Security-Policy`     | Default-src 'self'; script-src 'self' 'unsafe-inline' cdn.example.com |
| `X-Content-Type-Options`      | nosniff                                                              |
| `X-Frame-Options`             | DENY                                                                 |
| `X-XSS-Protection`            | 1; mode=block                                                       |
| `Strict-Transport-Security`   | max-age=31536000; includeSubDomains; preload                         |
| `Referrer-Policy`             | strict-origin-when-cross-origin                                      |
| `Permissions-Policy`          | camera=(), microphone=(), geolocation=()                             |

## 6. Deployment

### 6.1 Environments

| Environment | Purpose                         | Access                    | Data                                                                 |
|-------------|---------------------------------|---------------------------|---------------------------------------------------------------------|
| **Local**   | Development & testing          | Developers only           | Synthetic/test data                                                 |
| **Dev**     | Feature development & testing  | Internal team             | Anonymized production data                                          |
| **Staging** | Pre-production testing         | Internal & stakeholders   | Production-like data                                                |
| **Prod**    | Live environment               | Public                    | Real customer data                                                  |

### 6.2 CI/CD Pipeline

| Stage               | Tools & Processes                                                                 |
|---------------------|----------------------------------------------------------------------------------|
| **Source Control**  | GitHub with protected branches, required reviews, and status checks              |
| **Build**          | Docker image builds with multi-stage builds for optimized production images      |
| **Test**           | Unit, integration, and E2E tests running in isolated containers                  |
| **Security Scan**  | Dependency scanning (Dependabot), container scanning, and SAST/DAST tools       |
| **Deploy**         | Blue/green deployments with zero-downtime, automated rollback on failure        |
| **Post-Deploy**    | Smoke tests, performance regression checks, and monitoring verification         |

## 7. Monitoring & Observability

### 7.1 Logging Strategy

| Log Level | Storage | Retention | Purpose                                                                 |
|-----------|---------|-----------|-------------------------------------------------------------------------|
| **DEBUG** | Central | 7 days    | Detailed debugging information, verbose application state               |
| **INFO**  | Central | 30 days   | Normal operational messages, significant application events            |
| **WARN**  | Central | 90 days   | Non-critical issues that don't affect functionality                    |
| **ERROR** | Central | 1 year    | Error conditions that might still allow the application to continue    |
| **FATAL** | Central | 5 years   | Severe errors causing application failure                              |

### 7.2 Monitoring Stack

| Component         | Tool                | Purpose                                                                 |
|-------------------|---------------------|-------------------------------------------------------------------------|
| **Metrics**       | Prometheus          | Time-series metrics collection and alerting                            |
| **Visualization** | Grafana             | Dashboards for system and application metrics                         |
| **Tracing**       | Jaeger             | Distributed tracing for request flows across services                  |
| **APM**          | New Relic          | Application performance monitoring and error tracking                  |
| **Synthetics**    | Checkly            | Automated browser and API monitoring from multiple locations          |

## 8. Compliance & Governance

### 8.1 Regulatory Compliance

| Standard      | Scope                                                                 | Implementation Status |
|---------------|----------------------------------------------------------------------|----------------------|
| **GDPR**      | Data protection and privacy for EU citizens                           | Fully Compliant      |
| **CCPA**      | California Consumer Privacy Act requirements                         | Fully Compliant      |
| **PCI DSS**   | Payment Card Industry Data Security Standard (for payment processing) | Level 1 Compliant    |
| **WCAG 2.1**  | Web Content Accessibility Guidelines                                 | AA Conformance       |
| **SOC 2**     | Security and privacy controls                                        | In Progress          |

### 8.2 Security Practices

| Practice                    | Implementation Details                                                                 |
|-----------------------------|---------------------------------------------------------------------------------------|
| **Vulnerability Scanning**  | Weekly automated scans using OWASP ZAP and Trivy                                      |
| **Penetration Testing**     | Quarterly third-party security assessments and continuous automated testing           |
| **Secret Scanning**        | Pre-commit hooks and CI checks for exposed credentials                               |
| **Dependency Updates**     | Automated dependency updates with Dependabot, with security patch auto-merging        |
| **Security Training**      | Annual security awareness training for all team members                              |
| **Incident Response**      | Documented procedures for security incidents with regular tabletop exercises         |

## 9. Compliance
### 9.1 Regulatory Compliance

| Regulation / Standard | Applies When | Notes / Mitigation Strategy |
|-----------------------|-------------|-----------------------------|
| **GDPR (EU)** | Any site that collects personal data from EU/EEA residents. | - Cookie‐consent banner & granular opt-in.<br>- "Right to be forgotten" & data-export endpoints.<br>- DPA in place with sub-processors (Stripe, SendGrid, etc.). |
| **CCPA / CPRA (California)** | Servicing California consumers *and* >$25 M revenue OR >100 k users. | - "Do not sell/share my info" link.<br>- Transparent privacy policy updates.<br>- Self-service data-request portal. |
| **PCI DSS v4.0** | Processing or storing card payments. | - Stripe/Braintree redirect or hosted fields keeps Eheca *out of scope*.<br>- Quarterly composer-audit + dependency scanning. |
| **WCAG 2.1 AA / ADA** | Public-facing U.S. sites. | - Tailwind utility presets enforce color-contrast.<br>- Automated Axe & manual screen-reader checks in CI. |
| **COPPA** | Sites aimed at children < 13 yrs. | - Not enabled by default; modules must disable tracking & require parental consent. |
| **ePrivacy / Cookie Directive** | EU/UK users + cookies beyond "strictly necessary." | - Borlabs-style banner with category management.<br>- Consent stored in Redis; passed server-side to avoid accidental tracking. |
| **CAN-SPAM / CASL** | Marketing emails to U.S./Canadian recipients. | - Double opt-in, 1-click unsubscribe, physical mailing address in footer. |
| **HIPAA** (optional) | Only if hosting PHI for healthcare clients. | - Recommend dedicated HIPAA-ready hosting & database encryption at rest; otherwise avoid storing PHI. |
| **OWASP Top 10** (best-practice) | All deployments. | - Static analysis + dependency audit in GitHub Actions; rate-limiting & CSP headers baked in. |

> **Baseline:** Eheca core ships GDPR-ready, PCI-out-of-scope (via payment redirects), WCAG-conformant templates, and a configurable consent banner. Additional modules may extend compliance (e.g., HIPAA add-on) as project requirements demand.

### 9.2 Policies
- **Data Privacy Policy**: Outlines data collection, processing, and retention practices
- **Cookie Policy**: Details cookie usage and user consent management
- **Security Policy**: Defines security practices and incident response procedures
