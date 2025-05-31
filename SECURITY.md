# Security Policy

## Supported Versions

| Version | Supported          |
| ------- | ------------------ |
| 1.x.x   | :white_check_mark: |
| < 1.0.0 | :x:                |

## Reporting a Vulnerability

We take all security vulnerabilities seriously. Thank you for improving the security of Eheca. We appreciate your efforts and responsible disclosure and will make every effort to acknowledge your contributions.

### How to Report a Security Vulnerability

**Please do not report security vulnerabilities through public GitHub issues.**

Instead, please report them by emailing our security team at [security@eheca.dev](mailto:security@eheca.dev). You should receive a response within 48 hours. If for some reason you do not, please follow up via email to ensure we received your original message.

Please include the following details in your report:
- Type of issue (e.g., XSS, SQL injection, CSRF, etc.)
- Full path of the source file(s) related to the issue
- The location of the affected source code (tag/branch/commit or direct URL)
- Any special configuration required to reproduce the issue
- Step-by-step instructions to reproduce the issue
- Proof-of-concept or exploit code (if possible)
- Impact of the issue, including how an attacker might exploit the issue

### Our Security Process

1. Your report will be acknowledged within 48 hours, and you'll receive a more detailed response to your email within 72 hours indicating the next steps in handling your report.
2. After the initial reply to your report, the security team will keep you informed of the progress toward a fix and full announcement, and may ask for additional information or guidance.
3. When the issue is resolved, we will release a security advisory and credit you for the discovery (unless you prefer to remain anonymous).

### Vulnerability Management

- **Critical/High severity issues**: We aim to provide a fix or workaround within 7 days of confirmation.
- **Medium severity issues**: We aim to address these in the next regular release.
- **Low severity issues**: These will be evaluated and fixed based on impact and available resources.

### Security Updates

Security updates will be released as patch versions (e.g., 1.0.1, 1.0.2) for the latest minor version and as minor versions (e.g., 1.1.0, 1.2.0) for previous minor versions if the issue affects them.

### Security Advisories

Security advisories will be published in the following locations:
- GitHub Security Advisories
- Project Changelog
- Project Documentation

### Responsible Disclosure

We follow responsible disclosure guidelines:
- We will respond to your report within 48 hours with our evaluation of the vulnerability and expected resolution time.
- We will handle your report with strict confidentiality, and not pass on your personal details to third parties without your permission.
- We will keep you informed about the progress of the vulnerability analysis and the planned release date of the fix.
- We will credit you for your discovery, unless you prefer to stay anonymous.
- We will not take legal action against you if you have made a good faith effort to avoid privacy violations, destruction of data, and interruption or degradation of our service during your research.

### Secure Development Practices

Our development process includes the following security measures:
- Regular dependency updates
- Automated security scanning in CI/CD
- Code reviews with security in mind
- Security testing as part of our development workflow
- Regular security audits

### Security Hardening

We implement the following security measures:
- Input validation and output encoding
- CSRF protection
- XSS protection headers
- Secure session handling
- Secure password hashing
- Rate limiting
- Security headers (CSP, HSTS, etc.)

### Third-Party Dependencies

We regularly update our dependencies to include security patches. You can check our dependency status in:
- `composer.lock` for PHP dependencies
- `package-lock.json` for Node.js dependencies
- `Dockerfile` for system dependencies

### Security Contact

For any security-related questions or concerns, please contact [security@eheca.dev](mailto:security@eheca.dev).

---

*Last updated: 2025-05-30*
