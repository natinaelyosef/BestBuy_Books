BestBuy Books - Project Directory Structure
==========================================

This document describes the directory structure of the BestBuy Books project.

.
├── app
│   ├── Http
│   │   ├── Controllers
│   │   │   ├── Auth
│   │   │   ├── AdminChatController.php
│   │   │   ├── AdminDashboardController.php
│   │   │   ├── AdminIssueReportController.php
│   │   │   ├── AdminUserController.php
│   │   │   ├── AdminUserManagementController.php
│   │   │   ├── BookController.php
│   │   │   ├── Controller.php
│   │   │   ├── CustomerBookController.php
│   │   │   ├── CustomerCartController.php
│   │   │   ├── CustomerChatController.php
│   │   │   ├── CustomerDashboardController.php
│   │   │   ├── CustomerIssueReportController.php
│   │   │   ├── CustomerOrderController.php
│   │   │   ├── CustomerWishlistController.php
│   │   │   ├── ProfileController.php
│   │   │   ├── StoreChatController.php
│   │   │   ├── StoreController.php
│   │   │   ├── StoreIssueReportController.php
│   │   │   └── StoreRegistrationController.php
│   │   ├── Middleware
│   │   │   ├── CheckUserActive.php
│   │   │   ├── EnsureAccountType.php
│   │   │   └── HandleInertiaRequests.php
│   │   └── Requests
│   │       ├── Auth
│   │       └── ProfileUpdateRequest.php
│   ├── Models
│   │   ├── Book.php
│   │   ├── ChatConversation.php
│   │   ├── ChatMessage.php
│   │   ├── IssueReport.php
│   │   ├── StoreRegistration.php
│   │   └── User.php
│   └── Providers
│       └── AppServiceProvider.php
├── bootstrap
│   ├── cache
│   │   ├── packages.php
│   │   └── services.php
│   ├── app.php
│   └── providers.php
├── config
│   ├── app.php
│   ├── auth.php
│   ├── cache.php
│   ├── database.php
│   ├── filesystems.php
│   ├── logging.php
│   ├── mail.php
│   ├── queue.php
│   ├── services.php
│   └── session.php
├── database
│   ├── factories
│   │   └── UserFactory.php
│   ├── migrations
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   ├── 2026_03_10_000000_add_account_type_to_users_table.php
│   │   ├── 2026_03_10_010000_create_store_registrations_table.php
│   │   ├── 2026_03_11_000001_create_books_table.php
│   │   ├── 2026_03_11_130100_create_issue_reports_table.php
│   │   ├── 2026_03_11_130200_create_chat_conversations_table.php
│   │   ├── 2026_03_11_130300_create_chat_messages_table.php
│   │   ├── 2026_03_13_055558_add_store_id_to_chat_conversations.php
│   │   ├── 2026_03_13_055559_add_is_read_to_chat_messages.php
│   │   └── 2026_03_14_000001_enhance_issue_reports_and_users.php
│   └── seeders
│       ├── AdminUserSeeder.php
│       └── DatabaseSeeder.php
├── public
│   └── index.php
├── resources
│   ├── css
│   │   └── app.css
│   ├── js
│   │   ├── Components
│   │   │   ├── ApplicationLogo.jsx
│   │   │   ├── Checkbox.jsx
│   │   │   ├── DangerButton.jsx
│   │   │   ├── Dropdown.jsx
│   │   │   ├── InputError.jsx
│   │   │   ├── InputLabel.jsx
│   │   │   ├── Modal.jsx
│   │   │   ├── NavLink.jsx
│   │   │   ├── PrimaryButton.jsx
│   │   │   ├── ResponsiveNavLink.jsx
│   │   │   ├── SecondaryButton.jsx
│   │   │   └── TextInput.jsx
│   │   ├── Layouts
│   │   │   ├── AuthenticatedLayout.jsx
│   │   │   └── GuestLayout.jsx
│   │   ├── Pages
│   │   │   ├── Auth
│   │   │   ├── Customer
│   │   │   ├── Profile
│   │   │   ├── store
│   │   │   └── Welcome.jsx
│   │   ├── app.jsx
│   │   └── bootstrap.js
│   └── views
│       ├── admin
│       │   ├── admins
│       │   ├── chats
│       │   ├── issue_reports
│       │   ├── users
│       │   ├── base.html
│       │   ├── base_sub_admin.html
│       │   ├── dashboard.blade.php
│       │   └── layout.blade.php
│       ├── chat
│       │   └── chat_room.html
│       ├── customer
│       │   ├── issue_reports
│       │   ├── base.blade.php
│       │   ├── book_detail.blade copy.php
│       │   ├── book_detail.blade.php
│       │   ├── cart.blade.php
│       │   ├── chat_index.blade.php
│       │   ├── chat_list.blade.php
│       │   ├── chat_room.blade.php
│       │   ├── chat_show.blade.php
│       │   ├── checkout.blade.php
│       │   ├── dashboard.blade.php
│       │   ├── featured_books.blade.php
│       │   ├── order_detail.blade.php
│       │   ├── order_history.blade.php
│       │   ├── support_chat_detail.blade.php
│       │   ├── support_chat_list.blade.php
│       │   └── wishlist.blade.php
│       ├── store
│       │   ├── books
│       │   ├── issue_reports
│       │   ├── chat_list.blade.php
│       │   ├── chat_room.blade.php
│       │   ├── dashboard.blade.php
│       │   ├── orders.blade.php
│       │   ├── registration-layout.blade.php
│       │   ├── registration-update.blade.php
│       │   ├── registration-view.blade.php
│       │   ├── registration.blade.php
│       │   └── wishlist.blade.php
│       └── app.blade.php
├── routes
│   ├── auth.php
│   ├── console.php
│   └── web.php
├── storage
│   └── framework
│       └── views
│           └── ... 69 files, 0 dirs not shown
├── tests
│   ├── Feature
│   │   ├── Auth
│   │   │   └── ... 6 files, 0 dirs not shown
│   │   ├── ExampleTest.php
│   │   └── ProfileTest.php
│   ├── Unit
│   │   └── ExampleTest.php
│   └── TestCase.php
├── CHANGELOG.md
├── README.md
├── composer.json
├── generate_compact_pdf.py
├── generate_pdf.py
├── generate_pdf_optimized.py
├── generate_project_pdf.py
├── jsconfig.json
├── package-lock.json
├── package.json
├── phpunit.xml
├── postcss.config.js
├── reset_superadmin_password.php
├── tailwind.config.js
└── vite.config.js


Project Overview
================
BestBuy Books is an e-commerce platform built with Laravel PHP framework. It includes features for customers, administrators, and store managers, with functionalities such as book browsing, shopping carts, order management, chat systems, and issue reporting.

Key Features:
- Multi-role authentication (Admin, Store, Customer)
- Book catalog management
- Shopping cart and order processing
- Real-time chat functionality
- Issue reporting system
- User profile management