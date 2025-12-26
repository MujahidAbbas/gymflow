# FitHub Architecture Diagrams

> [!NOTE]
> This document contains technical architecture diagrams for understanding the FitHub system design, data flows, and component interactions.

## System Architecture Overview

### High-Level Architecture

```mermaid
graph TB
    subgraph "Frontend Layer"
        UI[Blade Templates]
        JS[Alpine.js]
        CSS[Tailwind CSS]
        Assets[Vite Assets]
    end
    
    subgraph "Application Layer"
        Routes[Routes]
        Middleware[Middleware Stack]
        Controllers[Controllers]
        Requests[Form Requests]
    end
    
    subgraph "Business Logic Layer"
        Services[Services]
        Models[Eloquent Models]
        Helpers[Helper Functions]
    end
    
    subgraph "Data Layer"
        DB[(MySQL Database)]
        Storage[File Storage]
        Cache[Cache]
    end
    
    subgraph "External Services"
        Stripe[Stripe API]
        PayPal[PayPal API]
        Email[Email Service]
    end
    
    UI --> Routes
    JS --> Routes
    Routes --> Middleware
    Middleware --> Controllers
    Controllers --> Requests
    Controllers --> Services
    Controllers --> Models
    Models --> DB
    Models --> Storage
    Helpers --> Models
    Services --> Stripe
    Services --> PayPal
    Controllers --> Email
    
    style UI fill:#e1f5ff
    style DB fill:#ffe1e1
    style Stripe fill:#fff3e1
    style PayPal fill:#fff3e1
```

---

## Multi-Tenancy Architecture

### Tenant Isolation Pattern

```mermaid
graph LR
    subgraph "Tenant A (parent_id=1)"
        Owner1[Owner User ID=1]
        Members1[Members<br/>parent_id=1]
        Classes1[Gym Classes<br/>parent_id=1]
        Settings1[Settings<br/>parent_id=1]
    end
    
    subgraph "Tenant B (parent_id=5)"
        Owner2[Owner User ID=5]
        Members2[Members<br/>parent_id=5]
        Classes2[Gym Classes<br/>parent_id=5]
        Settings2[Settings<br/>parent_id=5]
    end
    
    subgraph "Shared Database"
        UsersTable[(users)]
        MembersTable[(members)]
        ClassesTable[(gym_classes)]
        SettingsTable[(settings)]
    end
    
    Owner1 --> MembersTable
    Owner2 --> MembersTable
    Members1 -.isolated.-> MembersTable
    Members2 -.isolated.-> MembersTable
    
    style Members1 fill:#a8e6cf
    style Members2 fill:#ffd3b6
    style MembersTable fill:#ffe1e1
```

### Parent ID Scoping Flow

```mermaid
sequenceDiagram
    participant User
    participant Controller
    participant Helper
    participant Model
    participant Database
    
    User->>Controller: Request (authenticated)
    Controller->>Helper: parentId()
    Helper->>User: Auth::user()
    
    alt User is Owner/Super Admin
        Helper-->>Controller: user.id
    else User is Sub-user
        Helper-->>Controller: user.parent_id
    end
    
    Controller->>Model: where('parent_id', parentId())
    Model->>Database: SELECT * WHERE parent_id = ?
    Database-->>Model: Tenant-scoped records
    Model-->>Controller: Collection
    Controller-->>User: Response
```

---

## Authentication Flow

### Login with 2FA Flow

```mermaid
stateDiagram-v2
    [*] --> LoginForm
    LoginForm --> ValidateCredentials
    ValidateCredentials --> Invalid: Wrong credentials
    Invalid --> LoginForm
    
    ValidateCredentials --> Check2FA: Valid credentials
    
    Check2FA --> Dashboard: 2FA disabled
    Check2FA --> OTPForm: 2FA enabled
    
    OTPForm --> VerifyOTP
    VerifyOTP --> OTPForm: Invalid OTP
    VerifyOTP --> CheckEmailVerification: Valid OTP
    
    CheckEmailVerification --> Dashboard: Email verified
    CheckEmailVerification --> EmailVerificationNotice: Not verified
    
    Dashboard --> [*]
```

### Authentication Sequence

```mermaid
sequenceDiagram
    actor User
    participant LoginForm
    participant LoginController
    participant OTPController
    participant EmailVerificationController
    participant Dashboard
    
    User->>LoginForm: Enter credentials
    LoginForm->>LoginController: POST /login
    LoginController->>LoginController: Validate credentials
    
    alt Invalid credentials
        LoginController-->>LoginForm: Error message
    end
    
    LoginController->>LoginController: Auth::login()
    LoginController->>LoginController: userLoggedHistory()
    
    alt 2FA Enabled
        LoginController->>OTPController: Redirect to /2fa
        OTPController->>User: Show OTP form
        User->>OTPController: Enter OTP
        OTPController->>OTPController: Verify OTP
        
        alt Invalid OTP
            OTPController-->>User: Error message
        end
    end
    
    alt Email Not Verified
        LoginController->>EmailVerificationController: Redirect to /email/verify
        EmailVerificationController->>User: Show verification notice
    end
    
    LoginController->>Dashboard: Redirect to /
    Dashboard-->>User: Show dashboard
```

---

## Data Flow Diagrams

### Member Registration Flow

```mermaid
graph TD
    Start([User clicks Create Member]) --> Form[Fill Member Form]
    Form --> Submit[Submit POST /members]
    Submit --> Validate{Form Request<br/>Validation}
    
    Validate -->|Invalid| Form
    Validate -->|Valid| ProcessPhoto{Has Photo?}
    
    ProcessPhoto -->|Yes| UploadPhoto[Store to<br/>storage/members]
    ProcessPhoto -->|No| AddParentId
    UploadPhoto --> AddParentId[Add parent_id]
    
    AddParentId --> CalcExpiry{Has Membership<br/>Plan?}
    CalcExpiry -->|Yes| CalcDate[Calculate<br/>membership_end_date]
    CalcExpiry -->|No| CreateMember
    CalcDate --> CreateMember[Member::create]
    
    CreateMember --> GenerateId[Auto-generate<br/>#MBR-XXXX]
    GenerateId --> SaveDB[(Save to Database)]
    SaveDB --> Success[Redirect with<br/>success message]
    Success --> End([End])
    
    style Start fill:#e1f5ff
    style End fill:#a8e6cf
    style SaveDB fill:#ffe1e1
```

### Subscription Purchase Flow

```mermaid
sequenceDiagram
    actor Member
    participant UI
    participant SubscriptionController
    participant PaymentGateway
    participant Webhook
    participant Database
    
    Member->>UI: Browse plans
    UI->>Member: Display plans
    Member->>UI: Select plan
    UI->>SubscriptionController: GET /checkout/{plan}
    SubscriptionController->>Member: Show checkout form
    
    Member->>SubscriptionController: POST /purchase (gateway choice)
    SubscriptionController->>SubscriptionController: Create subscription record
    Note over SubscriptionController: status = 'pending'
    
    SubscriptionController->>PaymentGateway: Initiate payment
    PaymentGateway->>Member: Redirect to payment page
    Member->>PaymentGateway: Complete payment
    
    alt Payment Success
        PaymentGateway->>SubscriptionController: Redirect to /success
        PaymentGateway->>Webhook: Send webhook (async)
        Webhook->>Database: Update subscription status = 'active'
        Webhook->>Database: Create PaymentTransaction
        SubscriptionController->>Member: Show success page
    else Payment Failed
        PaymentGateway->>SubscriptionController: Redirect to /cancel
        SubscriptionController->>Member: Show error message
    end
```

---

## Database Schema Diagrams

### Core Entity Relationships

```mermaid
erDiagram
    USERS ||--o{ MEMBERS : "parent_id"
    USERS ||--o{ TRAINERS : "parent_id"
    USERS ||--o{ SETTINGS : "parent_id"
    
    MEMBERS ||--|| MEMBERSHIP_PLANS : "subscribes to"
    MEMBERS ||--o{ ATTENDANCES : "has"
    MEMBERS ||--o{ SUBSCRIPTIONS : "has"
    MEMBERS ||--o{ INVOICES : "receives"
    MEMBERS ||--o{ HEALTH : "tracks"
    MEMBERS ||--o{ WORKOUTS : "performs"
    MEMBERS ||--o{ LOCKER_ASSIGNMENTS : "assigned"
    MEMBERS }o--|| USERS : "user_id"
    
    SUBSCRIPTIONS ||--|| SUBSCRIPTION_PLANS : "based on"
    SUBSCRIPTIONS ||--o{ PAYMENT_TRANSACTIONS : "has"
    
    GYM_CLASSES ||--|| CATEGORIES : "categorized by"
    GYM_CLASSES ||--o{ CLASS_SCHEDULES : "has"
    GYM_CLASSES ||--o{ CLASS_ASSIGNS : "assigned to"
    
    TRAINERS ||--o{ CLASS_ASSIGNS : "teaches"
    
    WORKOUTS ||--o{ WORKOUT_ACTIVITIES : "contains"
    
    INVOICES ||--o{ INVOICE_ITEMS : "contains"
    INVOICES ||--o{ INVOICE_PAYMENTS : "has"
    
    LOCKERS ||--o{ LOCKER_ASSIGNMENTS : "assigned as"
    
    USERS {
        bigint id PK
        bigint parent_id FK
        string type
        string email
        string password
        string twofa_secret
        timestamp email_verified_at
    }
    
    MEMBERS {
        bigint id PK
        bigint parent_id FK
        bigint user_id FK
        string member_id UK
        string name
        string email
        date membership_start_date
        date membership_end_date
        enum status
    }
    
    SUBSCRIPTIONS {
        bigint id PK
        bigint member_id FK
        bigint subscription_plan_id FK
        date start_date
        date end_date
        enum status
        boolean auto_renew
        string gateway_subscription_id
    }
```

### Multi-Tenancy Schema

```mermaid
graph TB
    subgraph "All Tables with Multi-Tenancy"
        Members[members<br/>├ parent_id]
        Trainers[trainers<br/>├ parent_id]
        Classes[gym_classes<br/>├ parent_id]
        Categories[categories<br/>├ parent_id]
        Schedules[class_schedules<br/>├ parent_id]
        Attendances[attendances<br/>├ parent_id]
        Workouts[workouts<br/>├ parent_id]
        Health[healths<br/>├ parent_id]
        Invoices[invoices<br/>├ parent_id]
        Expenses[expenses<br/>├ parent_id]
        Lockers[lockers<br/>├ parent_id]
        Events[events<br/>├ parent_id]
        Notices[notice_boards<br/>├ parent_id]
        Settings[settings<br/>├ parent_id]
        Plans[membership_plans<br/>├ parent_id]
    end
    
    UsersTable[(users table)]
    
    UsersTable -->|parent_id FK| Members
    UsersTable -->|parent_id FK| Trainers
    UsersTable -->|parent_id FK| Classes
    UsersTable -->|parent_id FK| Categories
    UsersTable -->|parent_id FK| Schedules
    UsersTable -->|parent_id FK| Attendances
    UsersTable -->|parent_id FK| Workouts
    UsersTable -->|parent_id FK| Health
    UsersTable -->|parent_id FK| Invoices
    UsersTable -->|parent_id FK| Expenses
    UsersTable -->|parent_id FK| Lockers
    UsersTable -->|parent_id FK| Events
    UsersTable -->|parent_id FK| Notices
    UsersTable -->|parent_id FK| Settings
    UsersTable -->|parent_id FK| Plans
    
    style UsersTable fill:#ffe1e1
```

---

## State Machine Diagrams

### Subscription Lifecycle

```mermaid
stateDiagram-v2
    [*] --> Pending: Purchase initiated
    
    Pending --> Active: Payment confirmed
    Pending --> Failed: Payment failed
    
    Active --> Expired: End date passed
    Active --> Cancelled: User cancels
    Active --> Active: Auto-renewal
    
    Trial --> Active: Trial ends & payment
    Trial --> Expired: Trial ends & no payment
    
    Expired --> Active: Renewal payment
    Cancelled --> [*]
    Failed --> [*]
    Expired --> [*]
    
    note right of Active
        auto_renew = true
        Renews automatically
    end note
    
    note right of Trial
        trial_end_date set
        Free period
    end note
```

### Member Status Transitions

```mermaid
stateDiagram-v2
    [*] --> Active: Registration
    
    Active --> Inactive: Manual deactivation
    Active --> Expired: membership_end_date passed
    
    Inactive --> Active: Reactivation
    Inactive --> Expired: Time passes
    
    Expired --> Active: Renewal
    Expired --> [*]: Deletion
    
    note right of Active
        status = 'active'
        membership_end_date > now
        or membership_end_date IS NULL
    end note
    
    note right of Expired
        status = 'expired'
        membership_end_date < now
    end note
```

---

## Component Interaction Diagrams

### Request/Response Cycle

```mermaid
graph LR
    Request[HTTP Request] --> Router[Router<br/>web.php]
    Router --> AuthMiddleware{auth<br/>middleware}
    
    AuthMiddleware -->|Unauthenticated| Login[Redirect to Login]
    AuthMiddleware -->|Authenticated| TwoFAMiddleware{verify2fa<br/>middleware}
    
    TwoFAMiddleware -->|2FA Required| OTPPage[Redirect to 2FA]
    TwoFAMiddleware -->|Verified| Controller[Controller Method]
    
    Controller --> FormRequest{Form Request<br/>Validation}
    FormRequest -->|Invalid| ErrorResponse[422 Response]
    FormRequest -->|Valid| BusinessLogic[Business Logic]
    
    BusinessLogic --> Model[Eloquent Model]
    Model --> Database[(Database)]
    
    Database --> Model
    Model --> Controller
    Controller --> View[Blade Template]
    View --> Response[HTTP Response]
    
    style Request fill:#e1f5ff
    style Response fill:#a8e6cf
    style Database fill:#ffe1e1
```

### Payment Gateway Integration

```mermaid
graph TB
    User[User Action] --> Controller[SubscriptionController]
    
    Controller --> CreateSub[Create Subscription<br/>status='pending']
    CreateSub --> Gateway{Payment<br/>Gateway?}
    
    Gateway -->|Stripe| StripeService[Stripe Service]
    Gateway -->|PayPal| PayPalService[PayPal Service]
    Gateway -->|Bank| BankService[Bank Transfer<br/>Manual]
    
    StripeService --> StripeAPI[Stripe API]
    PayPalService --> PayPalAPI[PayPal API]
    
    StripeAPI --> StripeRedirect[Redirect to Stripe]
    PayPalAPI --> PayPalRedirect[Redirect to PayPal]
    BankService --> BankInstructions[Show Bank Details]
    
    StripeRedirect --> Payment[User Completes Payment]
    PayPalRedirect --> Payment
    
    Payment --> StripeWebhook[Stripe Webhook]
    Payment --> PayPalWebhook[PayPal IPN]
    
    StripeWebhook --> VerifySignature{Verify<br/>Signature}
    PayPalWebhook --> VerifySignature
    
    VerifySignature -->|Invalid| Reject[Reject Request]
    VerifySignature -->|Valid| UpdateDB[Update Subscription<br/>Create Transaction]
    
    UpdateDB --> Database[(Database)]
    Database --> EmailNotification[Send Email<br/>Notification]
    
    style User fill:#e1f5ff
    style Database fill:#ffe1e1
    style EmailNotification fill:#a8e6cf
```

---

## Module Dependencies

### Service Layer Architecture

```mermaid
graph TD
    subgraph "Controllers"
        MemberCtrl[MemberController]
        SubCtrl[SubscriptionController]
        InvoiceCtrl[InvoiceController]
    end
    
    subgraph "Services"
        PaymentSvc[PaymentService]
        EmailSvc[EmailService]
        NotificationSvc[NotificationService]
    end
    
    subgraph "Models"
        Member[Member Model]
        Subscription[Subscription Model]
        PaymentTxn[PaymentTransaction Model]
        Invoice[Invoice Model]
    end
    
    subgraph "External APIs"
        StripeAPI[Stripe API]
        PayPalAPI[PayPal API]
        SMTP[SMTP Server]
    end
    
    MemberCtrl --> Member
    SubCtrl --> Subscription
    SubCtrl --> PaymentSvc
    InvoiceCtrl --> Invoice
    
    PaymentSvc --> StripeAPI
    PaymentSvc --> PayPalAPI
    PaymentSvc --> PaymentTxn
    
    EmailSvc --> SMTP
    NotificationSvc --> EmailSvc
    
    SubCtrl --> NotificationSvc
    InvoiceCtrl --> NotificationSvc
    
    style MemberCtrl fill:#e1f5ff
    style PaymentSvc fill:#fff3e1
    style StripeAPI fill:#ffe1e1
```

---

## Deployment Architecture

### Production Environment

```mermaid
graph TB
    subgraph "Load Balancer"
        LB[Nginx / Load Balancer]
    end
    
    subgraph "Application Servers"
        App1[Laravel App 1]
        App2[Laravel App 2]
        App3[Laravel App 3]
    end
    
    subgraph "Database Cluster"
        DBPrimary[(Primary DB)]
        DBReplica1[(Replica 1)]
        DBReplica2[(Replica 2)]
    end
    
    subgraph "Caching Layer"
        Redis[Redis Cache]
    end
    
    subgraph "Storage"
        S3[S3 / Cloud Storage]
    end
    
    subgraph "Queue Workers"
        Queue1[Queue Worker 1]
        Queue2[Queue Worker 2]
    end
    
    Internet[Internet] --> LB
    LB --> App1
    LB --> App2
    LB --> App3
    
    App1 --> DBPrimary
    App2 --> DBPrimary
    App3 --> DBPrimary
    
    DBPrimary --> DBReplica1
    DBPrimary --> DBReplica2
    
    App1 --> Redis
    App2 --> Redis
    App3 --> Redis
    
    App1 --> S3
    App2 --> S3
    App3 --> S3
    
    Queue1 --> DBPrimary
    Queue2 --> DBPrimary
    
    style Internet fill:#e1f5ff
    style DBPrimary fill:#ffe1e1
    style Redis fill:#fff3e1
    style S3 fill:#a8e6cf
```

---

## Security Flow

### Authorization Check Flow

```mermaid
sequenceDiagram
    participant User
    participant Middleware
    participant Controller
    participant Model
    participant Database
    
    User->>Middleware: HTTP Request
    Middleware->>Middleware: Check Authentication
    
    alt Not Authenticated
        Middleware-->>User: Redirect to Login
    end
    
    Middleware->>Middleware: Check 2FA
    
    alt 2FA Required
        Middleware-->>User: Redirect to /2fa
    end
    
    Middleware->>Controller: Forward Request
    Controller->>Model: Fetch Record
    Model->>Database: Query
    Database-->>Model: Record Data
    Model-->>Controller: Record
    
    Controller->>Controller: Verify parent_id == parentId()
    
    alt Unauthorized
        Controller-->>User: 403 Forbidden
    end
    
    Controller->>Controller: Process Request
    Controller-->>User: Response
```

---

## Performance Optimization

### Query Optimization Pattern

```mermaid
graph LR
    Request[Request] --> Controller
    
    Controller --> QueryBuilder{Query Type}
    
    QueryBuilder -->|Single Record| FindQuery[Model::find]
    QueryBuilder -->|List with Relations| EagerLoad[Model::with RelationsArray]
    QueryBuilder -->|Paginated| Paginate[Model::paginate]
    
    FindQuery --> Cache{Cacheable?}
    EagerLoad --> Cache
    Paginate --> NoCacheNeeded[Direct Query]
    
    Cache -->|Yes| CheckCache{In Cache?}
    Cache -->|No| Database
    
    CheckCache -->|Hit| CacheResponse[Return Cached]
    CheckCache -->|Miss| Database[(Database)]
    
    Database --> StoreCache[Store in Cache]
    StoreCache --> Response
    CacheResponse --> Response[Response]
    NoCacheNeeded --> Response
    
    style Request fill:#e1f5ff
    style Response fill:#a8e6cf
    style Database fill:#ffe1e1
    style Cache fill:#fff3e1
```

---

These diagrams provide visual representations of the FitHub system architecture. Use them to understand data flows, component interactions, and system design patterns. For code-level details, refer to the [CODEBASE_KNOWLEDGE_BASE.md](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/CODEBASE_KNOWLEDGE_BASE.md).
