# FitHub Knowledge Base - Index

> [!IMPORTANT]
> Welcome to the FitHub Gym Management System Knowledge Base! This is your central hub for understanding and working with the codebase.

## 📚 Available Knowledge Items

This knowledge base contains comprehensive documentation for the FitHub Laravel-based gym management SaaS platform:

### 1. [Codebase Knowledge Base](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/CODEBASE_KNOWLEDGE_BASE.md)
**The comprehensive guide to FitHub's architecture and implementation**

- **What it covers**: Complete overview of the project including technology stack, domain models, multi-tenancy architecture, authentication & security, payment integration, business rules, and code conventions
- **Best for**: Understanding the full system, onboarding new developers, architectural decisions
- **Sections**: 10 major sections covering everything from project overview to common development tasks
- **Length**: ~800 lines of detailed documentation

**Key Topics**:
- 28 Eloquent Models and their relationships
- Multi-tenancy pattern using `parent_id` scoping
- Authentication with 2FA and email verification
- Payment gateway integrations (Stripe, PayPal, etc.)
- Helper functions (`parentId()`, `settings()`, `userLoggedHistory()`)
- Testing approaches and patterns

---

### 2. [Quick Reference Guide](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/QUICK_REFERENCE.md)
**Fast lookup for common patterns, commands, and conventions**

- **What it covers**: Quick reference cards for daily development tasks
- **Best for**: Fast lookups during development, command reminders, pattern templates
- **Format**: Tables, code snippets, command lists
- **Length**: ~600 lines of practical references

**Key Topics**:
- Common controller patterns (tenant scoping, file uploads, search/filter)
- Artisan commands (development, code generation, caching)
- Testing commands and approaches
- Database conventions and validation patterns
- Model scopes and relationships
- Environment variables and configuration

---

### 3. [Architecture Diagrams](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/ARCHITECTURE_DIAGRAMS.md)
**Visual representations of system architecture and data flows**

- **What it covers**: Mermaid diagrams showing system design, flows, and interactions
- **Best for**: Visual learners, understanding complex flows, architecture reviews
- **Diagrams**: 15+ comprehensive diagrams
- **Length**: ~700 lines of technical diagrams

**Key Topics**:
- System architecture layers (Frontend, Application, Business, Data)
- Multi-tenancy isolation patterns
- Authentication flows (login, 2FA, email verification)
- Data flow diagrams (member registration, subscription purchase)
- Database schema and entity relationships
- State machines (subscription lifecycle, member status)
- Component interactions and security flows

---

## 🎯 Quick Start Guide

### For New Developers

1. Start with **[Codebase Knowledge Base](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/CODEBASE_KNOWLEDGE_BASE.md)** sections:
   - Project Overview
   - Technology Stack
   - Domain Model & Entities
   - Multi-Tenancy Architecture
   - Code Conventions

2. Review **[Architecture Diagrams](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/ARCHITECTURE_DIAGRAMS.md)**:
   - System Architecture Overview
   - Multi-Tenancy Architecture
   - Database Schema Diagrams

3. Bookmark **[Quick Reference](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/QUICK_REFERENCE.md)** for daily use

### For Understanding Specific Features

| Feature | Documentation |
|---------|--------------|
| Multi-Tenancy | [Knowledge Base § Multi-Tenancy](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/CODEBASE_KNOWLEDGE_BASE.md) + [Diagrams](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/ARCHITECTURE_DIAGRAMS.md) |
| Authentication | [Knowledge Base § Authentication](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/CODEBASE_KNOWLEDGE_BASE.md) + [Auth Flow Diagrams](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/ARCHITECTURE_DIAGRAMS.md) |
| Payments | [Knowledge Base § Payment Integration](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/CODEBASE_KNOWLEDGE_BASE.md) + [Payment Flow Diagrams](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/ARCHITECTURE_DIAGRAMS.md) |
| Database Schema | [Knowledge Base § Domain Model](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/CODEBASE_KNOWLEDGE_BASE.md) + [ER Diagrams](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/ARCHITECTURE_DIAGRAMS.md) |
| Commands & Patterns | [Quick Reference](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/QUICK_REFERENCE.md) |

### For Daily Development

Use **[Quick Reference Guide](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/QUICK_REFERENCE.md)** for:
- Controller patterns and code snippets
- Artisan commands
- Validation rules
- Model scopes and relationships
- Environment variables
- Common errors and solutions

---

## 📊 Project Statistics

| Metric | Count |
|--------|-------|
| **Models** | 28 |
| **Controllers** | 23 |
| **Database Migrations** | 37 |
| **Helper Functions** | 3 |
| **Payment Gateways** | 6 (Stripe, PayPal, Flutterwave, Paystack, Bank Transfer, Cash) |
| **Authentication Methods** | Email/Password + 2FA + Email Verification |
| **User Types** | 5 (Super Admin, Owner, Manager, Trainer, Staff) |
| **PHP Version** | 8.2+ |
| **Laravel Version** | 12.x |

---

## 🗂️ Additional Documentation

### Technical Documentation (`docs/technical/`)
The project also includes detailed technical documentation in the `docs/` directory:

1. **Database Schema & ERD** - `01_Database_Schema_ERD.md`
2. **Controllers & Routes Reference** - `02_Controllers_Routes_Reference.md`
3. **Authentication & Security** - `03_Authentication_Security.md`
4. **Payment Integration Guide** - `04_Payment_Integration_Guide.md`
5. **Helper Functions Reference** - `05_Helper_Functions_Reference.md`
6. **Frontend UI Architecture** - `06_Frontend_UI_Architecture.md`

### Project Files
- [README.md](file:///Users/mujahid/Herd/gym/README.md) - Installation and setup
- [composer.json](file:///Users/mujahid/Herd/gym/composer.json) - PHP dependencies
- [package.json](file:///Users/mujahid/Herd/gym/package.json) - JavaScript dependencies

---

## 🔍 Knowledge Base Features

### Comprehensive Coverage

These knowledge items cover:

✅ **Architecture & Design Patterns**
- Multi-tenancy with parent_id scoping
- Service layer architecture
- Repository pattern considerations
- MVC structure

✅ **Domain Knowledge**
- 28 core entities and their relationships
- Business rules (member IDs, expiry calculation, renewals)
- Status transitions and state machines

✅ **Security & Authentication**
- 2FA implementation with Google2FA
- Email verification flows
- Login history tracking
- CSRF, XSS protection

✅ **Payment Processing**
- Multiple gateway integrations
- Webhook handling
- Transaction management
- Subscription lifecycle

✅ **Development Workflow**
- Code conventions and naming
- Testing strategies
- Common tasks and troubleshooting
- Performance optimization

✅ **Visual Guides**
- Architecture diagrams
- Data flow diagrams
- State machines
- Entity relationship diagrams

---

## 🚀 Common Development Workflows

### Adding a New Feature

1. Review **[Domain Model](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/CODEBASE_KNOWLEDGE_BASE.md)** to understand existing entities
2. Check **[Architecture Diagrams](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/ARCHITECTURE_DIAGRAMS.md)** for data flow patterns
3. Use **[Quick Reference](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/QUICK_REFERENCE.md)** for Artisan commands to generate files
4. Follow existing patterns in **[Code Conventions](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/CODEBASE_KNOWLEDGE_BASE.md)**
5. Write tests following **[Testing Approach](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/CODEBASE_KNOWLEDGE_BASE.md)**

### Debugging Issues

1. Check **[Common Errors](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/QUICK_REFERENCE.md)** in Quick Reference
2. Review **[Security Flow](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/ARCHITECTURE_DIAGRAMS.md)** diagrams for auth issues
3. Use **[Debugging Tips](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/QUICK_REFERENCE.md)** from Quick Reference
4. Check **[Multi-Tenancy](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/CODEBASE_KNOWLEDGE_BASE.md)** section for isolation issues

### Understanding Data Flow

1. Start with **[Data Flow Diagrams](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/ARCHITECTURE_DIAGRAMS.md)**
2. Review **[Component Interaction](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/ARCHITECTURE_DIAGRAMS.md)** diagrams
3. Check **[Module Structure](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/CODEBASE_KNOWLEDGE_BASE.md)** for controller patterns
4. Reference **[Entity Relationships](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/ARCHITECTURE_DIAGRAMS.md)** for data connections

---

## 💡 Best Practices Reminders

### Always
- ✅ Scope queries by `parentId()` for multi-tenancy
- ✅ Verify `parent_id` ownership on mutations
- ✅ Use Form Requests for validation
- ✅ Use eager loading to prevent N+1 queries
- ✅ Write tests for new features
- ✅ Follow existing naming conventions

### Never
- ❌ Use `env()` outside config files
- ❌ Skip `parent_id` verification on sensitive operations
- ❌ Return all records without pagination
- ❌ Commit sensitive data to git
- ❌ Mix tenant data across `parent_id` boundaries

---

## 🔗 Quick Links

| Resource | Link |
|----------|------|
| **Main Knowledge Base** | [CODEBASE_KNOWLEDGE_BASE.md](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/CODEBASE_KNOWLEDGE_BASE.md) |
| **Quick Reference** | [QUICK_REFERENCE.md](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/QUICK_REFERENCE.md) |
| **Architecture Diagrams** | [ARCHITECTURE_DIAGRAMS.md](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/ARCHITECTURE_DIAGRAMS.md) |
| **Project README** | [README.md](file:///Users/mujahid/Herd/gym/README.md) |
| **Routes** | [routes/web.php](file:///Users/mujahid/Herd/gym/routes/web.php) |
| **Models** | [app/Models/](file:///Users/mujahid/Herd/gym/app/Models) |
| **Controllers** | [app/Http/Controllers/](file:///Users/mujahid/Herd/gym/app/Http/Controllers) |
| **Helpers** | [app/Helper/helper.php](file:///Users/mujahid/Herd/gym/app/Helper/helper.php) |
| **Migrations** | [database/migrations/](file:///Users/mujahid/Herd/gym/database/migrations) |

---

## 📝 Document Versions

| Document | Created | Purpose |
|----------|---------|---------|
| CODEBASE_KNOWLEDGE_BASE.md | 2025-11-30 | Comprehensive system documentation |
| QUICK_REFERENCE.md | 2025-11-30 | Developer quick reference guide |
| ARCHITECTURE_DIAGRAMS.md | 2025-11-30 | Visual architecture documentation |
| INDEX.md (this file) | 2025-11-30 | Navigation hub for all knowledge items |

---

## 🎓 Learning Path

### Beginner (Day 1-3)
1. Read **[Project Overview](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/CODEBASE_KNOWLEDGE_BASE.md)**
2. Review **[System Architecture](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/ARCHITECTURE_DIAGRAMS.md)** diagrams
3. Setup local environment using [README.md](file:///Users/mujahid/Herd/gym/README.md)
4. Explore models and controllers

### Intermediate (Week 1-2)
1. Deep dive into **[Multi-Tenancy Architecture](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/CODEBASE_KNOWLEDGE_BASE.md)**
2. Understand **[Authentication Flow](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/ARCHITECTURE_DIAGRAMS.md)**
3. Study **[Payment Integration](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/CODEBASE_KNOWLEDGE_BASE.md)**
4. Write your first feature with tests

### Advanced (Month 1+)
1. Master **[Business Rules](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/CODEBASE_KNOWLEDGE_BASE.md)**
2. Study **[Performance Optimization](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/ARCHITECTURE_DIAGRAMS.md)**
3. Contribute to architecture decisions
4. Mentor new team members

---

## 📮 Feedback & Updates

This knowledge base is a living document. As the FitHub codebase evolves, these documents should be updated to reflect:

- New features and modules
- Architecture changes
- Convention updates
- New patterns and best practices
- Lessons learned

---

**Happy Coding! 🚀**

For any questions about the FitHub codebase, start here and navigate to the appropriate knowledge item.
