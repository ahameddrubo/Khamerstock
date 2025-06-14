<?php
// passwords.php ফাইলটি অন্তর্ভুক্ত করুন
$anonymous_passwords = include 'passwords.php';
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-en="Khamer Store Management App" data-ar="تطبيق خمر لإدارة المخزون">Khamer Store Management App</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-auth-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-firestore-compat.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <style>
        /* General Styles - Zoho Inspired */
        :root {
            --primary-color: #0d6efd; /* Professional Blue */
            --secondary-color: #6c757d; /* Medium Grey */
            --light-grey: #f8f9fa; /* Light Grey for backgrounds */
            --border-color: #dee2e6; /* Light border color */
            --text-color: #212529; /* Dark text for readability */
            --text-muted: #6c757d; /* Muted text */
            --success-color: #198754; /* Green for success */
            --danger-color: #dc3545; /* Red for danger/errors */
            --info-color: #0dcaf0;   /* Teal for info */
            --warning-color: #ffc107; /* Yellow for warning */
            --white-color: #ffffff;
            --font-family-sans-serif: 'Roboto', sans-serif;
            --border-radius: 0.375rem; /* Consistent border radius */
            --box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); /* Subtle shadow */
            --box-shadow-lg: 0 0.5rem 1rem rgba(0, 0, 0, 0.15); /* Larger shadow for modals/cards */
        }

        body {
            font-family: var(--font-family-sans-serif);
            margin: 0;
            padding: 0;
            background-color: #eef2f6; /* Zoho-like light grey background */
            color: var(--text-color);
            line-height: 1.6;
            direction: rtl;
            text-align: right;
        }
        body.en {
            direction: ltr;
            text-align: left;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            background-color: var(--white-color);
            padding: 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow-lg);
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Header Styles */
        header {
            background-color: var(--white-color); /* Clean white header */
            color: var(--primary-color);
            padding: 15px 20px; /* Reduced padding for a slimmer header */
            text-align: center;
            border-bottom: 1px solid var(--border-color); /* Subtle border */
            margin-bottom: 25px;
        }

        header h1 {
            margin: 0;
            font-size: 1.75em; /* Slightly smaller, more refined */
            font-weight: 600;
        }
        .app-credit {
            font-size: 0.8em;
            margin-top: 5px;
            color: var(--text-muted);
        }

        /* Language Toggle */
        #language-toggle {
            position: absolute;
            top: 15px;
            right: 20px; /* Adjusted for new header */
            background-color: transparent;
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            padding: 6px 10px;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-size: 0.85em;
            z-index: 1000;
            transition: background-color 0.2s ease, color 0.2s ease;
        }
        #language-toggle:hover {
            background-color: var(--primary-color);
            color: var(--white-color);
        }
        body.en #language-toggle {
            left: 20px;
            right: auto;
        }

        /* Section Styles */
        .content-section {
            padding: 20px;
            background-color: var(--white-color); /* Sections on main background might not need this if container is white */
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            /* box-shadow: var(--box-shadow); */ /* Optional: shadow on individual sections */
        }

        .content-section h2 {
            font-size: 1.5em; /* More refined section titles */
            margin-bottom: 20px;
            color: var(--primary-color);
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 10px;
            text-align: center;
            font-weight: 500;
        }
        .content-section h3 {
            font-size: 1.25em;
            margin-top: 25px;
            margin-bottom: 15px;
            color: var(--text-color);
            font-weight: 500;
        }


        /* Form Styles */
        form {
            background-color: transparent; /* Forms blend with section background */
            padding: 0; /* Remove extra padding if section has it */
            border-radius: var(--border-radius);
            /* box-shadow: none; */
        }

        .input-field, select {
            width: 100%; /* Full width by default */
            padding: 10px 12px;
            margin-bottom: 15px;
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            box-sizing: border-box;
            font-size: 0.95em;
            background-color: var(--white-color);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .input-field:focus, select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25); /* Focus ring */
            outline: none;
        }
        body.ar .input-field, body.ar select {
            text-align: right;
            direction: rtl;
        }
        body.en .input-field, body.en select {
            text-align: left;
            direction: ltr;
        }

        /* Button Styles - Zoho Inspired */
        .button-group {
            display: flex;
            justify-content: flex-start; /* Align buttons to the start */
            flex-wrap: wrap;
            margin-top: 20px;
            gap: 10px; /* Space between buttons */
        }
         .button-group button { /* General style for buttons in a group */
            flex: 0 1 auto; /* Don't grow, shrink if needed, base on content */
         }


        .button-primary, .button-success, .button-danger, .button-info, .button-secondary, .button-warning {
            border: none;
            padding: 10px 20px;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-weight: 500;
            font-size: 0.95em;
            transition: background-color 0.2s ease, transform 0.1s ease, box-shadow 0.2s ease;
            text-align: center;
        }
        .button-primary {
            background-color: var(--primary-color);
            color: var(--white-color);
        }
        .button-primary:hover {
            background-color: #0b5ed7; /* Darker shade of primary */
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .button-success {
            background-color: var(--success-color);
            color: var(--white-color);
        }
        .button-success:hover {
            background-color: #157347; /* Darker shade of success */
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .button-danger {
            background-color: var(--danger-color);
            color: var(--white-color);
        }
        .button-danger:hover {
            background-color: #bb2d3b; /* Darker shade of danger */
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .button-info {
            background-color: var(--info-color);
            color: var(--white-color);
        }
        .button-info:hover {
            background-color: #0aa3c2; /* Darker shade of info */
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .button-secondary {
            background-color: var(--secondary-color);
            color: var(--white-color);
        }
        .button-secondary:hover {
            background-color: #5c636a; /* Darker shade of secondary */
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .button-warning {
            background-color: var(--warning-color);
            color: var(--text-color); /* Dark text on yellow for readability */
        }
        .button-warning:hover {
            background-color: #ffba00; /* Darker yellow */
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .button-outline-primary {
            background-color: transparent;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }
        .button-outline-primary:hover {
            background-color: var(--primary-color);
            color: var(--white-color);
        }


        /* Order Item & Card Styles */
        .order-item, .user-order-item, .stock-item, .alert-item, .product-item, .return-notification-item {
            border: 1px solid var(--border-color);
            padding: 15px;
            margin-bottom: 15px;
            border-radius: var(--border-radius);
            background-color: var(--white-color);
            box-shadow: var(--box-shadow);
            transition: box-shadow 0.2s ease;
        }
        .order-item:hover, .user-order-item:hover, .stock-item:hover, .alert-item:hover, .product-item:hover, .return-notification-item:hover {
            box-shadow: var(--box-shadow-lg);
        }

        .order-item h4, .user-order-item h4, .product-item h4, .return-notification-item h4 {
            color: var(--primary-color);
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 1.15em;
            font-weight: 600;
        }
         .return-notification-item p {
            margin: 5px 0;
            font-size: 0.95em;
        }


        .order-item ul, .user-order-item ul, .editable-order-item ul {
            list-style-type: none; /* Cleaner list */
            padding:0;
            margin-top: 10px;
        }
        .order-item li, .user-order-item li, .editable-order-item li {
            margin-bottom: 8px;
            font-size: 0.9em;
            padding: 5px 0;
            border-bottom: 1px dashed #eee;
        }
        .order-item li:last-child, .user-order-item li:last-child, .editable-order-item li:last-child {
            border-bottom: none;
        }


        /* Product Item Styles for User Panel (Card-like) */
        #product-list { /* Grid for product items */
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); /* Responsive grid */
            gap: 20px;
        }
        .product-item {
            display: flex;
            flex-direction: column;
            align-items: stretch; /* Items stretch to fill height */
            text-align: center;
        }
        .product-item p {
            margin: 8px 0;
            font-size: 1em;
            color: var(--text-muted);
            flex-grow: 1; /* Allows text to take available space */
        }
        .product-item .quantity-control {
            display: flex;
            align-items: center;
            justify-content: center; /* Center quantity controls */
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid var(--border-color);
        }
        .product-item .quantity-control button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            width: 36px; /* Fixed size */
            height: 36px;
            border-radius: 50%;
            font-size: 1.3em;
            line-height: 1; /* Center icon/text */
            cursor: pointer;
            transition: background-color 0.2s ease;
            margin: 0 10px;
        }
        .product-item .quantity-control button:hover {
            background-color: #0b5ed7;
        }
        .product-item .quantity-control span {
            font-size: 1.2em;
            font-weight: bold;
            color: var(--text-color);
            min-width: 30px; /* Ensure space for number */
            text-align: center;
        }

        /* Low Stock Alert Styles */
        .alert-item {
            border-left: 5px solid var(--danger-color); /* Accent border */
            background-color: #fdf3f3; /* Very light red */
        }
        .alert-item p {
            font-weight: 500;
            color: var(--danger-color);
        }
        .alert-item button { /* Adapt existing button style */
             background-color: var(--info-color); color: white;
             padding: 8px 15px; font-size: 0.9em; margin-top:10px;
        }

        /* Stock and Restock Styles */
        .stock-item p {
            font-size: 1em;
            margin-bottom: 10px;
        }
        .stock-item input.restock-quantity { /* Specific class for restock inputs */
            margin-bottom: 10px;
            width: calc(100% - 120px); /* Adjust width if button is inline */
            display: inline-block; /* For inline alignment */
            margin-right: 10px; /* RTL: margin-left */
        }
        body.en .stock-item input.restock-quantity {
            margin-left: 10px;
            margin-right: 0;
        }
        .stock-item .restock-btn { /* Adapt existing button style */
             background-color: var(--info-color); color: white;
             padding: 10px 15px;
        }


        /* Login Form Specific - Centered and Clean */
        .login-form {
            max-width: 450px; /* Slightly wider */
            margin: 60px auto; /* More vertical margin */
            padding: 30px 35px; /* More padding */
            background-color: var(--white-color);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow-lg);
            text-align: center;
        }
        .login-form h2 {
            color: var(--primary-color);
            margin-bottom: 25px;
            font-size: 1.75em; /* Adjusted */
            border-bottom: none;
            padding-bottom: 0;
        }
        .login-form .input-field {
            margin-bottom: 20px; /* More space between fields */
        }
        .login-form .button-success { /* Main login button */
            width: 100%;
            padding: 12px;
            font-size: 1em;
            margin-top: 10px; /* Space above button */
        }
         .login-form .button-group { /* For alternative logins */
            margin-top: 25px;
            display: flex;
            flex-direction: column; /* Stack buttons */
            gap:12px;
        }
        .login-form .button-group button {
            width: 100%; /* Full width for stacked buttons */
            background-color: var(--secondary-color); /* Consistent alt login style */
        }
        .login-form .button-group button:hover {
            background-color: #555c63;
        }


        /* Message Area for UI Feedback */
        .message-area {
            margin-top: 15px;
            padding: 12px 15px; /* Increased padding */
            border-radius: var(--border-radius);
            font-weight: 500; /* Bolder for emphasis */
            display: none;
            text-align: center; /* Center align message text */
            border: 1px solid transparent;
        }
        .message-area.error {
            background-color: #f8d7da; /* Lighter red */
            color: #721c24; /* Darker red text */
            border-color: #f5c6cb;
        }
        .message-area.info {
            background-color: #cff4fc; /* Lighter teal */
            color: #055160; /* Darker teal text */
            border-color: #b6effb;
        }
        .message-area.success {
            background-color: #d1e7dd; /* Lighter green */
            color: #0f5132; /* Darker green text */
            border-color: #badbcc;
        }

        /* Utility classes for display control */
        .login-form, .admin-dashboard, .user-panel, .product-add-form, .low-stock-alert-section, .total-order-report, .stock-restock-section, .order-return-section {
            display: none;
        }
        .active-section {
            display: block;
        }

        /* Global Navigation (Top Bar style) */
        .global-nav {
            background-color: var(--primary-color); /* Primary color for top nav bar */
            padding: 10px 20px;
            display: flex;
            justify-content: flex-end; /* Align logout to the right */
            align-items: center;
            border-radius: 0; /* Full width bar */
            margin-bottom: 25px;
            box-shadow: var(--box-shadow);
        }
        body.en .global-nav {
            justify-content: flex-end;
        }
        body.ar .global-nav {
            justify-content: flex-start; /* Align logout to the left for RTL */
        }
        .global-nav button#nav-logout-btn { /* Style the global logout button */
            background-color: var(--danger-color);
            color: var(--white-color);
            padding: 8px 15px;
            font-size: 0.9em;
        }
        .global-nav button#nav-logout-btn:hover {
            background-color: #bb2d3b;
        }


        /* Admin Sub-navigation Styling (Zoho-like tabs or prominent buttons) */
        .admin-sub-nav {
            display: flex; /* Horizontal layout */
            flex-wrap: wrap; /* Allow wrapping on smaller screens */
            gap: 10px;
            padding: 15px;
            background-color: var(--light-grey); /* Light background for the nav area */
            border-radius: var(--border-radius);
            margin-bottom: 25px;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
        }

        .admin-sub-nav button {
            flex-grow: 1; /* Allow buttons to grow and fill space */
            padding: 12px 15px;
            font-size: 0.95em;
            font-weight: 500;
            color: var(--primary-color);
            background-color: var(--white-color);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: background-color 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
            text-align: center;
        }
        .admin-sub-nav button:hover {
            background-color: var(--primary-color);
            color: var(--white-color);
            border-color: var(--primary-color);
            box-shadow: 0 2px 5px rgba(13, 110, 253, 0.2);
        }
        .admin-sub-nav button.logout-btn {
            background-color: var(--danger-color);
            color: var(--white-color);
            border-color: var(--danger-color);
        }
        .admin-sub-nav button.logout-btn:hover {
            background-color: #bb2d3b;
            box-shadow: 0 2px 5px rgba(220, 53, 69, 0.2);
        }


        /* Order Edit Specific Styles */
        .editable-order-item li {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .editable-order-item .edit-quantity-control {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .editable-order-item .edit-quantity-control input {
            width: 50px;
            text-align: center;
            padding: 6px;
            font-size: 0.9em;
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
        }
        .editable-order-item .edit-quantity-control button,
        .editable-order-item .delete-item-btn,
        .editable-order-item .save-item-edit-btn {
            padding: 6px 10px;
            font-size: 0.85em;
        }
         .editable-order-item .delete-item-btn {
            margin-left: 5px; /* RTL: margin-right */
        }
        body.ar .editable-order-item .delete-item-btn {
             margin-right: 5px;
             margin-left: 0;
        }
        .editable-order-item .save-item-edit-btn {
            margin-left: 5px; /* RTL: margin-right */
            background-color: var(--success-color); /* Use success for save */
        }
        body.ar .editable-order-item .save-item-edit-btn {
             margin-right: 5px;
             margin-left: 0;
        }


        /* Back to Admin Dashboard Button */
        .back-to-admin-btn {
            background-color: var(--secondary-color);
            color: var(--white-color);
            border: none;
            padding: 10px 20px;
            margin-bottom: 20px;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: background-color 0.2s ease;
            display: inline-block; /* Not full width unless specified */
            font-size: 0.9em;
        }
        .back-to-admin-btn:hover {
            background-color: #5a6268;
        }

        /* Report Filters */
        #report-filters {
            display: flex;
            gap: 15px;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            background-color: var(--light-grey);
            border-radius: var(--border-radius);
        }
        #report-filters label {
            font-weight: 500;
        }
        #report-filters select {
            width: auto; /* Fit content */
            min-width: 150px;
            margin-bottom: 0; /* Remove bottom margin for inline */
        }
        #report-filters #generate-report-btn { /* Specific styling if needed */
            background-color: var(--primary-color);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                margin: 10px;
                padding: 15px;
            }
            header h1 {
                font-size: 1.5em;
            }
            .admin-sub-nav {
                flex-direction: column; /* Stack admin nav buttons */
            }
            .admin-sub-nav button {
                width: 100%;
            }
            .content-section h2 {
                font-size: 1.3em;
            }
            .button-group {
                flex-direction: column;
            }
            .button-group button {
                width: 100%;
                margin-bottom: 10px;
            }
            #product-list {
                 grid-template-columns: 1fr; /* Single column for products on small screens */
            }
            .stock-item input.restock-quantity {
                width: calc(100% - 100px); /* Adjust for smaller screens */
            }
            #report-filters {
                flex-direction: column;
                align-items: stretch;
            }
            #report-filters select, #report-filters button {
                width: 100%;
            }
        }
         @media (max-width: 480px) {
            header h1 {
                font-size: 1.3em;
            }
            .content-section h2 {
                font-size: 1.15em;
            }
             .input-field, select, .button-primary, .button-success, .button-danger, .button-info, .button-secondary, .button-warning  {
                font-size: 0.9em; /* Slightly smaller fonts for small devices */
                padding: 8px 10px;
            }
             .product-item .quantity-control button {
                width: 32px;
                height: 32px;
                font-size: 1.1em;
             }
             .product-item .quantity-control span {
                font-size: 1em;
             }
        }
    </style>
</head>
<body class="ar">
    <div id="language-toggle" data-en="اللغة العربية" data-ar="English">English</div>
    <div class="container">
        <header>
            <h1 data-en="Khamer Store Management App" data-ar="تطبيق خمر لإدارة المخزون">Khamer Store Management App</h1>
            <p class="app-credit" data-en="This system Created by Amdadul" data-ar="تم إنشاء هذا النظام بواسطة امدادول">This system Created by Amdadul</p>
        </header>

        <main>
            <nav id="global-navigation" class="global-nav" style="display: none;">
                <button id="nav-logout-btn" class="button-danger" data-en="Logout" data-ar="تسجيل الخروج">Logout</button>
            </nav>


            <section id="login-section" class="login-form active-section">
                <h2 data-en="Login" data-ar="تسجيل الدخول">تسجيل الدخول</h2>
                <form id="login-form">
                    <input type="email" id="login-email" data-en-placeholder="Email (Admin)" data-ar-placeholder="البريد الإلكتروني (المسؤول)" placeholder="البريد الإلكتروني (المسؤول)" class="input-field">
                    <input type="password" id="login-password" data-en-placeholder="Password (Admin)" data-ar-placeholder="كلمة المرور (المسؤول)" placeholder="كلمة المرور (المسؤول)" class="input-field">
                    <button type="submit" class="button-success" data-en="Admin Login" data-ar="تسجيل دخول المسؤول">تسجيل دخول المسؤول</button>
                </form>
                <div id="login-message-area" class="message-area"></div>

                <div class="button-group" style="margin-top: 30px;">
                    <button type="button" id="kitchen-login-btn" class="button-info" data-en="Kitchen Login" data-ar="تسجيل دخول المطبخ">تسجيل دخول المطبخ</button>
                    <button type="button" id="men-login-btn" class="button-info" data-en="Men's Section Login" data-ar="تسجيل دخول قسم الرجال">تسجيل دخول قسم الرجال</button>
                    <button type="button" id="women-login-btn" class="button-info" data-en="Women's Section Login" data-ar="تسجيل دخول قسم النساء">تسجيل دخول قسم النساء</button>
                </div>
            </section>

            <section id="admin-dashboard-section" class="admin-dashboard content-section">
                <h2 data-en="Admin Control Panel" data-ar="لوحة تحكم المسؤول">لوحة تحكم المسؤول</h2>
                <nav class="admin-sub-nav">
                    <button id="show-pending-orders-btn" data-en="Pending Orders Management" data-ar="إدارة الطلبات المعلقة">إدارة الطلبات المعلقة</button>
                    <button id="add-product-btn" data-en="Add New Product" data-ar="إضافة منتج جديد">إضافة منتج جديد</button>
                    <button id="low-stock-btn" data-en="Inventory Alerts" data-ar="تنبيهات المخزون">تنبيهات المخزون</button>
                    <button id="total-order-report-btn" data-en="Comprehensive Sales Report" data-ar="تقرير المبيعات الشامل">تقرير المبيعات الشامل</button>
                    <button id="stock-restock-btn" data-en="Manage Stock & Replenish" data-ar="إدارة وتجديد المخزون">إدارة وتجديد المخزون</button>
                    <button id="order-return-btn" data-en="Process Returns to Supplier" data-ar="معالجة الإرجاع للمورد">معالجة الإرجاع للمورد</button>
                    <button id="admin-logout-btn" class="logout-btn" data-en="Logout" data-ar="تسجيل الخروج">تسجيل الخروج</button>
                </nav>
                <div id="admin-dashboard-content" style="margin-top: 20px;">
                    <h3 data-en="Pending Orders" data-ar="الطلبات المعلقة">الطلبات المعلقة</h3>
                    <div id="pending-orders-list">
                        </div>
                </div>
                <div id="admin-message-area" class="message-area"></div>
            </section>

            <section id="add-product-section" class="product-add-form content-section">
                <button class="back-to-admin-btn button-secondary" data-en="Back to Admin Dashboard" data-ar="العودة للوحة تحكم المسؤول">العودة للوحة تحكم المسؤول</button>
                <h2 data-en="Add New Product" data-ar="إضافة منتج جديد">إضافة منتج جديد</h2>
                <form id="add-product-form">
                    <input type="text" id="product-name-en" data-en-placeholder="Product Name (English)" data-ar-placeholder="اسم المنتج (الإنجليزية)" placeholder="اسم المنتج (الإنجليزية)" class="input-field" required>
                    <input type="text" id="product-name-ar" data-en-placeholder="Product Name (Arabic)" data-ar-placeholder="اسم المنتج (العربية)" placeholder="اسم المنتج (العربية)" class="input-field" required>
                    <input type="number" id="product-quantity" data-en-placeholder="Quantity (Current Stock)" data-ar-placeholder="الكمية (المخزون الحالي)" placeholder="الكمية (المخزون الحالي)" class="input-field" required min="0">
                    <input type="number" id="low-stock-alert-quantity" data-en-placeholder="Low Stock Alert Quantity" data-ar-placeholder="كمية تنبيه المخزون المنخفض" placeholder="كمية تنبيه المخزون المنخفض" class="input-field" required min="0">
                    <select id="product-category" class="input-field" required>
                        <option value="" data-en="Select Category" data-ar="اختر الفئة">اختر الفئة</option>
                        <option value="kitchen" data-en="Kitchen" data-ar="المطبخ">المطبخ</option>
                        <option value="men" data-en="Men's Section" data-ar="قسم الرجال">قسم الرجال</option>
                        <option value="women" data-en="Women's Section" data-ar="قسم النساء">قسم النساء</option>
                    </select>
                    <select id="quantity-type" class="input-field" required>
                        <option value="" data-en="Select Quantity Type" data-ar="اختر نوع الكمية">اختر نوع الكمية</option>
                        <option value="piece" data-en="Piece" data-ar="قطعة">قطعة</option>
                        <option value="kg" data-en="KG" data-ar="كيلوغرام">كيلوغرام</option>
                        <option value="liter" data-en="Liter" data-ar="لتر">لتر</option>
                        <option value="carton" data-en="Carton" data-ar="كرتون">كرتون</option>
                    </select>
                    <button type="submit" class="button-success" style="width: 100%;" data-en="Add Product" data-ar="إضافة منتج">إضافة منتج</button>
                </form>
                <div id="add-product-message-area" class="message-area"></div>
            </section>

            <section id="low-stock-alert-section" class="low-stock-alert-section content-section">
                <button class="back-to-admin-btn button-secondary" data-en="Back to Admin Dashboard" data-ar="العودة للوحة تحكم المسؤول">العودة للوحة تحكم المسؤول</button>
                <h2 data-en="Inventory Alerts" data-ar="تنبيهات المخزون">تنبيهات المخزون</h2>
                <div id="low-stock-products-list">
                    </div>
                <div id="low-stock-message-area" class="message-area"></div>
            </section>

            <section id="total-order-report-section" class="total-order-report content-section">
                <button class="back-to-admin-btn button-secondary" data-en="Back to Admin Dashboard" data-ar="العودة للوحة تحكم المسؤول">العودة للوحة تحكم المسؤول</button>
                <h2 data-en="Comprehensive Sales Report (Last 3 Months)" data-ar="تقرير المبيعات الشامل (آخر 3 أشهر)">تقرير المبيعات الشامل (آخر 3 أشهر)</h2>
                <div id="report-filters">
                    <label for="report-category" data-en="Category:" data-ar="الفئة:">الفئة:</label>
                    <select id="report-category" class="input-field">
                        <option value="all" data-en="All" data-ar="الكل">الكل</option>
                        <option value="kitchen" data-en="Kitchen" data-ar="المطبخ">المطبخ</option>
                        <option value="men" data-en="Men's Section" data-ar="قسم الرجال">قسم الرجال</option>
                        <option value="women" data-en="Women's Section" data-ar="قسم النساء">قسم النساء</option>
                    </select>
                    <button id="generate-report-btn" class="button-primary" data-en="Show Report" data-ar="عرض التقرير">عرض التقرير</button>
                </div>
                <div id="total-order-report-content">
                    </div>
                <div id="report-message-area" class="message-area"></div>
            </section>

            <section id="stock-restock-section" class="stock-restock-section content-section">
                <button class="back-to-admin-btn button-secondary" data-en="Back to Admin Dashboard" data-ar="العودة للوحة تحكم المسؤول">العودة للوحة تحكم المسؤول</button>
                <h2 data-en="Manage Stock & Replenish" data-ar="إدارة وتجديد المخزون">إدارة وتجديد المخزون</h2>
                <div id="current-stock-list">
                    </div>
                <div id="stock-message-area" class="message-area"></div>
            </section>

            <section id="order-return-section" class="order-return-section content-section">
                <button class="back-to-admin-btn button-secondary" data-en="Back to Admin Dashboard" data-ar="العودة للوحة تحكم المسؤول">العودة للوحة تحكم المسؤول</button>
                <h2 data-en="Process Returns to Supplier" data-ar="معالجة الإرجاع للمورد">معالجة الإرجاع للمورد</h2>
                <form id="return-product-to-supplier-form">
                    <select id="return-to-supplier-product-select" class="input-field" required>
                        <option value="" data-en="Select Product" data-ar="اختر المنتج">اختر المنتج</option>
                        </select>
                    <input type="number" id="return-to-supplier-quantity" data-en-placeholder="Return Quantity" data-ar-placeholder="كمية الإرجاع" placeholder="كمية الإرجاع" class="input-field" required min="1">
                    <input type="text" id="return-to-supplier-name" data-en-placeholder="Supplier Name" data-ar-placeholder="اسم المورد" placeholder="اسم المورد" class="input-field" required>
                    <button type="submit" class="button-warning" style="width: 100%;" data-en="Return Product to Supplier" data-ar="إرجاع المنتج للمورد">إرجاع المنتج للمورد</button>
                </form>
                <div id="return-to-supplier-message-area" class="message-area"></div>

                <h3 data-en="Pending Replacements from Suppliers" data-ar="منتجات معلقة للاستبدال من الموردين" style="margin-top: 30px;">منتجات معلقة للاستبدال من الموردين</h3>
                <div id="pending-return-notifications-list">
                    </div>
            </section>

            <section id="user-panel-section" class="user-panel content-section">
                <h2 id="user-panel-title" data-en="User Panel" data-ar="لوحة المستخدم">لوحة المستخدم</h2>
                <button id="user-logout-btn" class="button-danger" style="margin-bottom: 20px;" data-en="Logout" data-ar="تسجيل الخروج">تسجيل الخروج</button>

                <h3 data-en="Products to Order" data-ar="المنتجات للطلب">المنتجات للطلب</h3>
                <div id="product-list">
                    </div>

                <h3 data-en="Your Cart" data-ar="سلتك">سلتك</h3>
                <div id="user-cart-summary" style="margin-bottom: 20px; padding:15px; background-color: var(--light-grey); border-radius: var(--border-radius);">
                    </div>
                <button id="place-order-btn" class="button-success" style="width: 100%; margin-top: 10px;" data-en="Place Order" data-ar="تقديم الطلب">تقديم الطلب</button>
                <div id="user-order-message-area" class="message-area"></div>


                <h3 data-en="Your Previous Orders" data-ar="طلباتك السابقة">طلباتك السابقة</h3>
                <div id="user-orders-list">
                    </div>
            </section>
        </main>
    </div>

    <script>
        // PHP থেকে পাসওয়ার্ডগুলো JavaScript ভেরিয়েবলে আনুন
        const ANONYMOUS_PASSWORDS = <?php echo json_encode($anonymous_passwords); ?>;

        // Firebase Configuration
        const firebaseConfig = {
            apiKey: "AIzaSyBnvcS8kpOrebq66LzQ2hz5Kvv_z_pu5p4", // আপনার আসল এপিআই কী ব্যবহার করুন
            authDomain: "stock-5d278.firebaseapp.com",
            projectId: "stock-5d278",
            storageBucket: "stock-5d278.firebasestorage.app",
            messagingSenderId: "730697386577",
            appId: "1:730697386577:web:8b7ef78adac14ee5990670",
            measurementId: "G-CFNVGX1S4W"
        };

        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);
        const auth = firebase.auth();
        const db = firebase.firestore();

        // --- DOM Elements ---
        const loginSection = document.getElementById('login-section');
        const adminDashboardSection = document.getElementById('admin-dashboard-section');
        const userPanelSection = document.getElementById('user-panel-section');
        const addProductSection = document.getElementById('add-product-section');
        const lowStockAlertSection = document.getElementById('low-stock-alert-section');
        const totalOrderReportSection = document.getElementById('total-order-report-section');
        const stockRestockSection = document.getElementById('stock-restock-section');
        const orderReturnSection = document.getElementById('order-return-section');

        const loginForm = document.getElementById('login-form');
        const loginEmailInput = document.getElementById('login-email');
        const loginPasswordInput = document.getElementById('login-password');
        const loginMessageArea = document.getElementById('login-message-area');

        const kitchenLoginBtn = document.getElementById('kitchen-login-btn');
        const menLoginBtn = document.getElementById('men-login-btn');
        const womenLoginBtn = document.getElementById('women-login-btn');

        // Admin Sub-navigation Buttons
        const showPendingOrdersBtn = document.getElementById('show-pending-orders-btn');
        const addProductBtn = document.getElementById('add-product-btn');
        const lowStockBtn = document.getElementById('low-stock-btn');
        const totalOrderReportBtn = document.getElementById('total-order-report-btn');
        const stockRestockBtn = document.getElementById('stock-restock-btn');
        const orderReturnBtn = document.getElementById('order-return-btn');
        const adminLogoutBtn = document.getElementById('admin-logout-btn');
        const userLogoutBtn = document.getElementById('user-logout-btn');

        const pendingOrdersList = document.getElementById('pending-orders-list');
        const addProductForm = document.getElementById('add-product-form');
        const addProductMessageArea = document.getElementById('add-product-message-area');
        const lowStockProductsList = document.getElementById('low-stock-products-list');
        const totalOrderReportContent = document.getElementById('total-order-report-content');
        const currentStockList = document.getElementById('current-stock-list');

        // Return to Supplier Elements
        const returnProductToSupplierForm = document.getElementById('return-product-to-supplier-form');
        const returnToSupplierProductSelect = document.getElementById('return-to-supplier-product-select');
        const returnToSupplierMessageArea = document.getElementById('return-to-supplier-message-area');
        const pendingReturnNotificationsList = document.getElementById('pending-return-notifications-list');


        const productList = document.getElementById('product-list');
        const userCartSummary = document.getElementById('user-cart-summary');
        const userOrdersList = document.getElementById('user-orders-list');
        const placeOrderBtn = document.getElementById('place-order-btn');
        const userPanelTitle = document.getElementById('user-panel-title');
        const userOrderMessageArea = document.getElementById('user-order-message-area');

        const globalNavigation = document.getElementById('global-navigation');
        const navLogoutBtn = document.getElementById('nav-logout-btn');

        const backToAdminBtns = document.querySelectorAll('.back-to-admin-btn');


        // --- Global Variables ---
        let currentUserCategory = null;
        let userCart = {}; // productId: { name_en, name_ar, quantity, type }

        // --- Language Management ---
        let currentLanguage = 'ar'; // Default language

        const translations = {
            'en': {
                'Khamer Store Management App': 'Khamer Store Management App',
                'This system Created by Amdadul': 'This system Created by Amdadul',
                'Login': 'Login',
                'Email (Admin)': 'Email (Admin)',
                'Password (Admin)': 'Password (Admin)',
                'Admin Login': 'Admin Login',
                'Kitchen Login': 'Kitchen Login',
                'Men\'s Section Login': 'Men\'s Section Login',
                'Women\'s Section Login': 'Women\'s Section Login',
                'Admin Control Panel': 'Admin Control Panel',
                'Pending Orders Management': 'Pending Orders Management',
                'Add New Product': 'Add New Product',
                'Inventory Alerts': 'Inventory Alerts',
                'Comprehensive Sales Report': 'Comprehensive Sales Report',
                'Manage Stock & Replenish': 'Manage Stock & Replenish',
                'Process Returns to Supplier': 'Process Returns to Supplier',
                'Logout': 'Logout',
                'Pending Orders': 'Pending Orders',
                'Loading Orders...': 'Loading Orders...',
                'No pending orders.': 'No pending orders.',
                'Department:': 'Department:',
                'Order ID:': 'Order ID:',
                'Order Date:': 'Order Date:',
                'Delivered': 'Delivered',
                'Cancel': 'Cancel',
                'Error loading orders:': 'Error loading orders:',
                'Order successfully delivered and stock updated.': 'Order successfully delivered and stock updated.',
                'Failed to deliver order.': 'Failed to deliver order.',
                'Order successfully cancelled.': 'Order successfully cancelled.',
                'Failed to cancel order.': 'Failed to cancel order.',
                'Product Name (English)': 'Product Name (English)',
                'Product Name (Arabic)': 'Product Name (Arabic)',
                'Quantity (Current Stock)': 'Quantity (Current Stock)',
                'Low Stock Alert Quantity': 'Low Stock Alert Quantity',
                'Select Category': 'Select Category',
                'Kitchen': 'Kitchen',
                'Men\'s Section': 'Men\'s Section',
                'Women\'s Section': 'Women\'s Section',
                'Select Quantity Type': 'Select Quantity Type',
                'Piece': 'Piece',
                'KG': 'KG',
                'Liter': 'Liter',
                'Carton': 'Carton',
                'Add Product': 'Add Product',
                'Please fill all fields correctly.': 'Please fill all fields correctly.',
                'Product already exists in this category.': 'Product already exists in this category.',
                'Product successfully added.': 'Product successfully added.',
                'Failed to add product.': 'Failed to add product.',
                'Loading Inventory Alerts...': 'Loading Inventory Alerts...',
                'No low stock alerts.': 'No low stock alerts.',
                'Current Stock:': 'Current Stock:',
                'Alert Quantity:': 'Alert Quantity:',
                'Remove Alert (Display Only)': 'Remove Alert (Display Only)',
                'Alert successfully removed (display only).': 'Alert successfully removed (display only).',
                'Error loading inventory alerts:': 'Error loading inventory alerts:',
                'Comprehensive Sales Report (Last 3 Months)': 'Comprehensive Sales Report (Last 3 Months)',
                'Category:': 'Category:',
                'All': 'All',
                'Show Report': 'Show Report',
                'Loading Report...': 'Loading Report...',
                'No delivered order reports found.': 'No delivered order reports found.',
                'Delivery Date:': 'Delivery Date:',
                'Share/Download PDF': 'Share/Download PDF',
                'Report load successful.': 'Report load successful.',
                'Failed to load report:': 'Failed to load report:',
                'Report content not found.': 'Report content not found.',
                'Generating PDF...': 'Generating PDF...',
                'PDF successfully downloaded.': 'PDF successfully downloaded.',
                'Loading Stock...': 'Loading Stock...',
                'No stock items.': 'No stock items.',
                'Remaining Stock:': 'Remaining Stock:',
                'Re-stock Quantity': 'Re-stock Quantity',
                'Re-stock': 'Re-stock',
                'Failed to load stock:': 'Failed to load stock:',
                'Stock successfully updated.': 'Stock successfully updated.',
                'Failed to re-stock:': 'Failed to re-stock:',
                'Select Product': 'Select Product',
                'No products available': 'No products available',
                'Return Quantity': 'Return Quantity',
                'Supplier Name': 'Supplier Name',
                'Return Product to Supplier': 'Return Product to Supplier',
                'Product successfully returned to supplier and stock updated.': 'Product successfully returned to supplier and stock updated.',
                'Failed to return product to supplier.': 'Failed to return product to supplier.',
                'Pending Replacements from Suppliers': 'Pending Replacements from Suppliers',
                'No pending replacements.': 'No pending replacements.',
                'Returned to:': 'Returned to:',
                'Quantity:': 'Quantity:',
                'Mark as Replaced & Re-stock': 'Mark as Replaced & Re-stock',
                'Replacement processed and stock updated.': 'Replacement processed and stock updated.',
                'Failed to process replacement.': 'Failed to process replacement.',
                'Please select product, valid quantity, and supplier name.': 'Please select product, valid quantity, and supplier name.',
                'Return to supplier in progress...': 'Return to supplier in progress...',
                'Cannot return more than current stock.': 'Cannot return more than current stock.',
                'Product not found in stock.': 'Product not found in stock.',
                'User Panel': 'User Panel',
                'Products to Order': 'Products to Order',
                'Loading Products...': 'Loading Products...',
                'No products in this category.': 'No products in this category.',
                'Available:': 'Available:',
                'Your Cart': 'Your Cart',
                'Your cart is empty.': 'Your cart is empty.',
                'Place Order': 'Place Order',
                'Added to cart.': 'Added to cart.',
                'Out of stock, cannot add more.': 'Out of stock, cannot add more.',
                'Removed from cart.': 'Removed from cart.',
                'Order being placed...': 'Order being placed...',
                'Sorry, insufficient stock for': 'Sorry, insufficient stock for',
                'Please check your cart.': 'Please check your cart.',
                'Order successfully placed.': 'Order successfully placed.',
                'Failed to place order:': 'Failed to place order:',
                'Your Previous Orders': 'Your Previous Orders',
                'Loading your orders...': 'Loading your orders...',
                'You have no orders.': 'You have no orders.',
                'Status:': 'Status:',
                'Cancel Order': 'Cancel Order',
                'Order cancelled successfully.': 'Order cancelled successfully.',
                'Failed to cancel order:': 'Failed to cancel order:',
                'Problem fetching your data:': 'Problem fetching your data:',
                'Unknown user category. Please log in again.': 'Unknown user category. Please log in again.',
                'Login in progress... please wait.': 'Login in progress... please wait.',
                'Login successful.': 'Login successful.',
                'Login failed:': 'Login failed:',
                'Email and password required.': 'Email and password required.',
                'Form elements not loaded.': 'Form elements not loaded.',
                'Admin setup error:': 'Admin setup error:',
                'Admin user document created. Role set to admin.': 'Admin user document created. Role set to admin.',
                'Logout successful.': 'Logout successful.',
                'Warning:': 'Warning:',
                'is out of stock.': 'is out of stock.',
                'Language Arabic': 'Language Arabic',
                'Order Update': 'Order Update',
                'Update Order': 'Update Order',
                'Order successfully updated.': 'Order successfully updated.',
                'Failed to update order.': 'Failed to update order.',
                'Access Denied. Please log in as Admin.': 'Access Denied. Please log in as Admin.',
                'Access Denied. Please log in as a regular user.': 'Access Denied. Please log in as a regular user.',
                'Back to Admin Dashboard': 'Back to Admin Dashboard',
                'Login cancelled.': 'Login cancelled.',
                'Wrong password.': 'Wrong password.',
                'Insufficient stock for': 'Insufficient stock for',
                'Order being delivered...': 'Order being delivered...',
                'Order being cancelled...': 'Order being cancelled...',
                'Product being added...': 'Product being added...',
                'Stock being updated...': 'Stock being updated...',
                'Failed to load return products:': 'Failed to load return products:',
                'Failed to load products:': 'Failed to load products:',
                'Failed to load your orders:': 'Failed to load your orders:',
                'Please enter a valid quantity.': 'Please enter a valid quantity.',
                'Order not found!': 'Order not found!',
                'All items removed. Order successfully cancelled.': 'All items removed. Order successfully cancelled.',
                'Delete': 'Delete',
                'Update': 'Update',
                'Are you sure you want to mark this order as delivered and update stock?': 'Are you sure you want to mark this order as delivered and update stock?',
                'Are you sure you want to cancel this order?': 'Are you sure you want to cancel this order?',
                'Are you sure you want to place this order?': 'Are you sure you want to place this order?',
                'Are you sure you want to delete this item from the order?': 'Are you sure you want to delete this item from the order?',
                'Loading stock...': 'Loading stock...',
                'Loading...': 'Loading...',
                'Are you sure you want to return this product to the supplier? Stock will be reduced.': 'Are you sure you want to return this product to the supplier? Stock will be reduced.',
                'Are you sure you want to mark this item as replaced and re-stock it?': 'Are you sure you want to mark this item as replaced and re-stock it?'
            },
            'ar': {
                'Khamer Store Management App': 'تطبيق خمر لإدارة المخزون',
                'This system Created by Amdadul': 'تم إنشاء هذا النظام بواسطة امدادول',
                'Login': 'تسجيل الدخول',
                'Email (Admin)': 'البريد الإلكتروني (المسؤول)',
                'Password (Admin)': 'كلمة المرور (المسؤول)',
                'Admin Login': 'تسجيل دخول المسؤول',
                'Kitchen Login': 'تسجيل دخول المطبخ',
                'Men\'s Section Login': 'تسجيل دخول قسم الرجال',
                'Women\'s Section Login': 'تسجيل دخول قسم النساء',
                'Admin Control Panel': 'لوحة تحكم المسؤول',
                'Pending Orders Management': 'إدارة الطلبات المعلقة',
                'Add New Product': 'إضافة منتج جديد',
                'Inventory Alerts': 'تنبيهات المخزون',
                'Comprehensive Sales Report': 'تقرير المبيعات الشامل',
                'Manage Stock & Replenish': 'إدارة وتجديد المخزون',
                'Process Returns to Supplier': 'معالجة الإرجاع للمورد',
                'Logout': 'تسجيل الخروج',
                'Pending Orders': 'الطلبات المعلقة',
                'Loading Orders...': 'جارٍ تحميل الطلبات...',
                'No pending orders.': 'لا توجد طلبات معلقة.',
                'Department:': 'القسم:',
                'Order ID:': 'رقم الطلب:',
                'Order Date:': 'تاريخ الطلب:',
                'Delivered': 'تم التوصيل',
                'Cancel': 'إلغاء',
                'Error loading orders:': 'خطأ في تحميل الطلبات:',
                'Order successfully delivered and stock updated.': 'تم توصيل الطلب وتحديث المخزون بنجاح.',
                'Failed to deliver order.': 'فشل في توصيل الطلب.',
                'Order successfully cancelled.': 'تم إلغاء الطلب بنجاح.',
                'Failed to cancel order.': 'فشل في إلغاء الطلب.',
                'Product Name (English)': 'اسم المنتج (الإنجليزية)',
                'Product Name (Arabic)': 'اسم المنتج (العربية)',
                'Quantity (Current Stock)': 'الكمية (المخزون الحالي)',
                'Low Stock Alert Quantity': 'كمية تنبيه المخزون المنخفض',
                'Select Category': 'اختر الفئة',
                'Kitchen': 'المطبخ',
                'Men\'s Section': 'قسم الرجال',
                'Women\'s Section': 'قسم النساء',
                'Select Quantity Type': 'اختر نوع الكمية',
                'Piece': 'قطعة',
                'KG': 'كيلوغرام',
                'Liter': 'لتر',
                'Carton': 'كرتون',
                'Add Product': 'إضافة منتج',
                'Please fill all fields correctly.': 'يرجى ملء جميع الحقول بشكل صحيح.',
                'Product already exists in this category.': 'المنتج موجود بالفعل في هذه الفئة.',
                'Product successfully added.': 'تمت إضافة المنتج بنجاح.',
                'Failed to add product.': 'فشل في إضافة المنتج.',
                'Loading Inventory Alerts...': 'جارٍ تحميل تنبيهات المخزون...',
                'No low stock alerts.': 'لا توجد تنبيهات مخزون منخفض.',
                'Current Stock:': 'المخزون الحالي:',
                'Alert Quantity:': 'كمية التنبيه:',
                'Remove Alert (Display Only)': 'إزالة التنبيه (للعرض فقط)',
                'Alert successfully removed (display only).': 'تمت إزالة التنبيه بنجاح (للعرض فقط).',
                'Error loading inventory alerts:': 'خطأ في تحميل تنبيهات المخزون:',
                'Comprehensive Sales Report (Last 3 Months)': 'تقرير المبيعات الشامل (آخر 3 أشهر)',
                'Category:': 'الفئة:',
                'All': 'الكل',
                'Show Report': 'عرض التقرير',
                'Loading Report...': 'جارٍ تحميل التقرير...',
                'No delivered order reports found.': 'لم يتم العثور على تقارير طلبات تم تسليمها.',
                'Delivery Date:': 'تاريخ التسليم:',
                'Share/Download PDF': 'مشاركة/تنزيل PDF',
                'Report load successful.': 'تم تحميل التقرير بنجاح.',
                'Failed to load report:': 'فشل في تحميل التقرير:',
                'Report content not found.': 'لم يتم العثور على محتوى التقرير.',
                'Generating PDF...': 'جارٍ إنشاء PDF...',
                'PDF successfully downloaded.': 'تم تنزيل PDF بنجاح.',
                'Loading Stock...': 'جارٍ تحميل المخزون...',
                'No stock items.': 'لا توجد عناصر في المخزون.',
                'Remaining Stock:': 'المخزون المتبقي:',
                'Re-stock Quantity': 'كمية إعادة التخزين',
                'Re-stock': 'إعادة التخزين',
                'Failed to load stock:': 'فشل في تحميل المخزون:',
                'Stock successfully updated.': 'تم تحديث المخزون بنجاح.',
                'Failed to re-stock:': 'فشل في إعادة التخزين:',
                'Select Product': 'اختر المنتج',
                'No products available': 'لا توجد منتجات متاحة',
                'Return Quantity': 'كمية الإرجاع',
                'Supplier Name': 'اسم المورد',
                'Return Product to Supplier': 'إرجاع المنتج للمورد',
                'Product successfully returned to supplier and stock updated.': 'تم إرجاع المنتج للمورد وتحديث المخزون بنجاح.',
                'Failed to return product to supplier.': 'فشل في إرجاع المنتج للمورد.',
                'Pending Replacements from Suppliers': 'منتجات معلقة للاستبدال من الموردين',
                'No pending replacements.': 'لا توجد منتجات معلقة للاستبدال.',
                'Returned to:': 'أُعيد إلى:',
                'Quantity:': 'الكمية:',
                'Mark as Replaced & Re-stock': 'وضع علامة كـ "تم الاستبدال" وإعادة التخزين',
                'Replacement processed and stock updated.': 'تمت معالجة الاستبدال وتحديث المخزون.',
                'Failed to process replacement.': 'فشل في معالجة الاستبدال.',
                'Please select product, valid quantity, and supplier name.': 'الرجاء تحديد المنتج وكمية صالحة واسم المورد.',
                'Return to supplier in progress...': 'جارٍ إرجاع المنتج للمورد...',
                'Cannot return more than current stock.': 'لا يمكن إرجاع كمية أكبر من المخزون الحالي.',
                'Product not found in stock.': 'المنتج غير موجود في المخزن.',
                'User Panel': 'لوحة المستخدم',
                'Products to Order': 'المنتجات للطلب',
                'Loading Products...': 'جارٍ تحميل المنتجات...',
                'No products in this category.': 'لا توجد منتجات في هذه الفئة.',
                'Available:': 'متاح:',
                'Your Cart': 'سلتك',
                'Your cart is empty.': 'سلتك فارغة.',
                'Place Order': 'تقديم الطلب',
                'Added to cart.': 'تمت الإضافة إلى السلة.',
                'Out of stock, cannot add more.': 'المخزون غير كافٍ، لا يمكن إضافة المزيد.',
                'Removed from cart.': 'تمت الإزالة من السلة.',
                'Order being placed...': 'جارٍ تقديم الطلب...',
                'Sorry, insufficient stock for': 'عذرًا، المخزون غير كافٍ لـ',
                'Please check your cart.': 'يرجى مراجعة سلتك.',
                'Order successfully placed.': 'تم تقديم الطلب بنجاح.',
                'Failed to place order:': 'فشل في تقديم الطلب:',
                'Your Previous Orders': 'طلباتك السابقة',
                'Loading your orders...': 'جارٍ تحميل طلباتك...',
                'You have no orders.': 'ليس لديك طلبات.',
                'Status:': 'الحالة:',
                'Cancel Order': 'إلغاء الطلب',
                'Order cancelled successfully.': 'تم إلغاء الطلب بنجاح.',
                'Failed to cancel order:': 'فشل في إلغاء الطلب:',
                'Problem fetching your data:': 'مشكلة في جلب بياناتك:',
                'Unknown user category. Please log in again.': 'فئة مستخدم غير معروفة. يرجى تسجيل الدخول مرة أخرى.',
                'Login in progress... please wait.': 'جارٍ تسجيل الدخول... يرجى الانتظار.',
                'Login successful.': 'تم تسجيل الدخول بنجاح.',
                'Login failed:': 'فشل تسجيل الدخول:',
                'Email and password required.': 'البريد الإلكتروني وكلمة المرور مطلوبان.',
                'Form elements not loaded.': 'لم يتم تحميل عناصر النموذج.',
                'Admin setup error:': 'خطأ في إعداد المسؤول:',
                'Admin user document created. Role set to admin.': 'تم إنشاء مستند مستخدم المسؤول. تم تعيين الدور للمسؤول.',
                'Logout successful.': 'تم تسجيل الخروج بنجاح.',
                'Warning:': 'تحذير:',
                'is out of stock.': 'نفد المخزون.',
                'Language Arabic': 'English',
                'Order Update': 'تحديث الطلب',
                'Update Order': 'تحديث الطلب',
                'Order successfully updated.': 'تم تحديث الطلب بنجاح.',
                'Failed to update order.': 'فشل في تحديث الطلب.',
                'Access Denied. Please log in as Admin.': 'الوصول مرفوض. يرجى تسجيل الدخول كمسؤول.',
                'Access Denied. Please log in as a regular user.': 'الوصول مرفوض. يرجى تسجيل الدخول كمستخدم عادي.',
                'Back to Admin Dashboard': 'العودة للوحة تحكم المسؤول',
                'Login cancelled.': 'تم إلغاء تسجيل الدخول.',
                'Wrong password.': 'كلمة مرور خاطئة.',
                'Insufficient stock for': 'المخزون غير كافٍ لـ',
                'Order being delivered...': 'جارٍ توصيل الطلب...',
                'Order being cancelled...': 'جارٍ إلغاء الطلب...',
                'Product being added...': 'جارٍ إضافة المنتج...',
                'Stock being updated...': 'جارٍ تحديث المخزون...',
                'Failed to load return products:': 'فشل في تحميل منتجات الإرجاع:',
                'Failed to load products:': 'فشل في تحميل المنتجات:',
                'Failed to load your orders:': 'فشل في تحميل طلباتك:',
                'Please enter a valid quantity.': 'الرجاء إدخال كمية صالحة.',
                'Order not found!': 'لم يتم العثور على الطلب!',
                'All items removed. Order successfully cancelled.': 'تمت إزالة جميع العناصر. تم إلغاء الطلب بنجاح.',
                'Delete': 'حذف',
                'Update': 'تحديث',
                'Are you sure you want to mark this order as delivered and update stock?': 'هل أنت متأكد أنك تريد وضع علامة على هذا الطلب كمُسلم وتحديث المخزون؟',
                'Are you sure you want to cancel this order?': 'هل أنت متأكد أنك تريد إلغاء هذا الطلব؟',
                'Are you sure you want to place this order?': 'هل أنت متأكد أنك تريد تقديم هذا الطلب؟',
                'Are you sure you want to delete this item from the order?': 'هل أنت متأكد أنك تريد حذف هذا العنصر من الطلب؟',
                'Loading stock...': 'جارٍ تحميل المخزون...',
                'Loading...': 'جار التحميل...',
                'Are you sure you want to return this product to the supplier? Stock will be reduced.': 'هل أنت متأكد أنك تريد إرجاع هذا المنتج إلى المورد؟ سيتم تخفيض المخزون.',
                'Are you sure you want to mark this item as replaced and re-stock it?': 'هل أنت متأكد أنك تريد وضع علامة على هذا العنصر كمستبدل وإعادة تخزينه؟'
            }
        };


        const translatableElements = document.querySelectorAll('[data-en], [data-ar-placeholder]');

        function setLanguage(lang) {
            currentLanguage = lang;
            document.body.className = lang;
            document.querySelector('html').lang = lang;

            translatableElements.forEach(el => {
                const enText = el.dataset.en;
                const arText = el.dataset.ar;
                const enPlaceholder = el.dataset.enPlaceholder;
                const arPlaceholder = el.dataset.arPlaceholder;

                if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
                    if (lang === 'en' && enPlaceholder) {
                        el.placeholder = enPlaceholder;
                    } else if (lang === 'ar' && arPlaceholder) {
                        el.placeholder = arPlaceholder;
                    } else { // Fallback if one placeholder is missing
                        el.placeholder = lang === 'en' ? (enPlaceholder || arPlaceholder) : (arPlaceholder || enPlaceholder);
                    }
                } else if (el.tagName === 'BUTTON' && el.id === 'language-toggle') {
                     el.textContent = lang === 'en' ? translations.ar['Language Arabic'] : translations.en['Language Arabic'];
                } else if (enText || arText) {
                     el.textContent = lang === 'en' ? (enText || arText) : (arText || enText);
                }
            });

            document.querySelectorAll('select option').forEach(option => {
                const optionEnText = option.dataset.en;
                const optionArText = option.dataset.ar;
                if (optionEnText || optionArText) {
                    option.textContent = lang === 'en' ? (optionEnText || optionArText) : (optionArText || optionEnText);
                }
            });

            if (currentUserCategory && userPanelTitle) {
                 userPanelTitle.textContent = _t('User Panel');
            }
            document.querySelectorAll('.restock-quantity').forEach(input => {
                input.placeholder = _t('Re-stock Quantity');
            });
            if (adminDashboardSection.classList.contains('active-section')) {
                loadAdminDashboard();
            }
            if (userPanelSection.classList.contains('active-section') && auth.currentUser) {
                loadUserOrders(auth.currentUser.uid);
            }
             // Refresh return notifications if that section is active
            if (orderReturnSection.classList.contains('active-section')) {
                loadReturnNotifications();
            }
        }

        const languageToggleButton = document.getElementById('language-toggle');
        if (languageToggleButton) {
            languageToggleButton.addEventListener('click', () => {
                const newLang = currentLanguage === 'ar' ? 'en' : 'ar';
                setLanguage(newLang);
                localStorage.setItem('appLang', newLang);
            });
        }

        const savedLang = localStorage.getItem('appLang');
        if (savedLang) {
            setLanguage(savedLang);
        } else {
            setLanguage('ar'); // Default to Arabic
        }

        function _t(key) {
            return translations[currentLanguage][key] || key;
        }

        function displayMessage(areaElement, messageKeyOrText, type) {
            if (!areaElement) {
                console.error("Message area element not found for message:", messageKeyOrText);
                return;
            }
            const message = _t(messageKeyOrText);
            areaElement.textContent = message;
            areaElement.className = `message-area ${type}`;
            areaElement.style.display = 'block';
            setTimeout(() => {
                areaElement.style.display = 'none';
                areaElement.textContent = '';
            }, 5000);
        }

        function showSection(sectionToShow, state = null) {
            const sections = [
                loginSection, adminDashboardSection, userPanelSection, addProductSection,
                lowStockAlertSection, totalOrderReportSection, stockRestockSection, orderReturnSection
            ];
            sections.forEach(section => {
                if (section) section.classList.remove('active-section');
            });

            if (sectionToShow) {
                sectionToShow.classList.add('active-section');
                globalNavigation.style.display = (sectionToShow === loginSection) ? 'none' : 'flex';

                const sectionId = sectionToShow.id;
                if (!history.state || history.state.sectionId !== sectionId) {
                    history.pushState({ sectionId: sectionId, category: currentUserCategory }, '', `#${sectionId}`);
                }
            } else {
                console.error("Section to show is null or undefined.");
                loginSection.classList.add('active-section');
                globalNavigation.style.display = 'none';
            }
        }

        window.addEventListener('popstate', (event) => {
            if (event.state && event.state.sectionId) {
                const targetSection = document.getElementById(event.state.sectionId);
                if (targetSection) {
                    currentUserCategory = event.state.category;
                    showSection(targetSection);
                    if (targetSection === adminDashboardSection) renderAdminDashboard();
                    else if (targetSection === addProductSection) { /* No specific load */ }
                    else if (targetSection === lowStockAlertSection) loadLowStockAlerts();
                    else if (targetSection === totalOrderReportSection) loadTotalOrderReport();
                    else if (targetSection === stockRestockSection) loadStockAndRestock();
                    else if (targetSection === orderReturnSection) {
                        loadProductsForReturnToSupplier();
                        loadReturnNotifications();
                    }
                    else if (targetSection === userPanelSection && auth.currentUser) {
                        loadUserProducts(currentUserCategory);
                        loadUserOrders(auth.currentUser.uid);
                        updateCartSummary();
                    }
                } else {
                     showSection(loginSection);
                }
            } else if (!auth.currentUser) {
                showSection(loginSection);
            }
        });


        async function initializeFirebaseCollections() {
            console.log("Firebase collections will be created on first write if they don't exist.");
        }

        auth.onAuthStateChanged(async (user) => {
            if (user) {
                const userDocRef = db.collection('users').doc(user.uid);
                let userDoc;
                try {
                    userDoc = await userDocRef.get();
                } catch (error) {
                    console.error("Error fetching user document:", error);
                    displayMessage(loginMessageArea, 'Problem fetching your data:', 'error');
                    auth.signOut();
                    return;
                }

                if (userDoc.exists) {
                    currentUserCategory = userDoc.data().role;
                    console.log(`User ${user.email || user.uid} logged in. Role: ${currentUserCategory}`);
                    if (currentUserCategory === 'admin') {
                        showSection(adminDashboardSection);
                        renderAdminDashboard();
                    } else if (['kitchen', 'men', 'women'].includes(currentUserCategory)) {
                        showSection(userPanelSection);
                        if (userPanelTitle) userPanelTitle.textContent = _t('User Panel');
                        loadUserProducts(currentUserCategory);
                        loadUserOrders(user.uid);
                        updateCartSummary();
                    } else {
                        console.warn("Unknown user role:", currentUserCategory);
                        displayMessage(loginMessageArea, 'Unknown user category. Please log in again.', 'error');
                        auth.signOut();
                    }
                } else {
                    if (!user.isAnonymous && user.email) {
                        try {
                            await userDocRef.set({
                                role: 'admin', email: user.email,
                                createdAt: firebase.firestore.FieldValue.serverTimestamp()
                            });
                            currentUserCategory = 'admin';
                            console.log(`Admin user document created for ${user.email}.`);
                            displayMessage(loginMessageArea, 'Admin user document created. Role set to admin.', 'success');
                            showSection(adminDashboardSection);
                            renderAdminDashboard();
                        } catch (error) {
                            console.error("Error creating admin user document:", error);
                            displayMessage(loginMessageArea, 'Admin setup error:', 'error');
                            auth.signOut();
                        }
                    } else {
                         console.warn(`User document for ${user.uid} not found, and user is anonymous or has no email. Logging out.`);
                         displayMessage(loginMessageArea, 'Unknown user category. Please log in again.', 'error');
                         auth.signOut();
                    }
                }
            } else {
                showSection(loginSection);
                currentUserCategory = null;
                userCart = {};
                updateCartSummary();
                if (loginEmailInput) loginEmailInput.value = '';
                if (loginPasswordInput) loginPasswordInput.value = '';
                history.replaceState({ sectionId: 'login-section' }, '', '#login-section');
            }
        });


        if (loginForm) {
            loginForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                if (!loginEmailInput || !loginPasswordInput) {
                    displayMessage(loginMessageArea, 'Form elements not loaded.', 'error'); return;
                }
                const email = loginEmailInput.value.trim();
                const password = loginPasswordInput.value;
                if (!email || !password) {
                    displayMessage(loginMessageArea, 'Email and password required.', 'error'); return;
                }
                displayMessage(loginMessageArea, 'Login in progress... please wait.', 'info');
                try {
                    await auth.signInWithEmailAndPassword(email, password);
                    displayMessage(loginMessageArea, 'Login successful.', 'success');
                } catch (error) {
                    displayMessage(loginMessageArea, `${_t('Login failed:')} ${error.message}`, 'error');
                }
            });
        }

        // Modified handleAnonymousLogin to use ANONYMOUS_PASSWORDS
        async function handleAnonymousLogin(category) {
            const password = prompt(_t(`${category} Login`));
            if (password === null) {
                displayMessage(loginMessageArea, 'Login cancelled.', 'info'); return;
            }
            // Passwords are now fetched from the global ANONYMOUS_PASSWORDS object
            if (password === ANONYMOUS_PASSWORDS[category]) {
                displayMessage(loginMessageArea, 'Login in progress... please wait.', 'info');
                try {
                    const userCredential = await auth.signInAnonymously();
                    const user = userCredential.user;
                    await db.collection('users').doc(user.uid).set({
                        role: category, email: 'anonymous',
                        createdAt: firebase.firestore.FieldValue.serverTimestamp()
                    });
                } catch (error) {
                    displayMessage(loginMessageArea, `${_t('Login failed:')} ${error.message}`, 'error');
                }
            } else {
                displayMessage(loginMessageArea, 'Wrong password.', 'error');
            }
        }

        // Updated event listeners to only pass the category
        if (kitchenLoginBtn) kitchenLoginBtn.addEventListener('click', () => handleAnonymousLogin('kitchen'));
        if (menLoginBtn) menLoginBtn.addEventListener('click', () => handleAnonymousLogin('men'));
        if (womenLoginBtn) womenLoginBtn.addEventListener('click', () => handleAnonymousLogin('women'));

        async function handleLogout() {
            await auth.signOut();
            displayMessage(loginMessageArea, 'Logout successful.', 'success');
        }
        if (adminLogoutBtn) adminLogoutBtn.addEventListener('click', handleLogout);
        if (userLogoutBtn) userLogoutBtn.addEventListener('click', handleLogout);
        if (navLogoutBtn) navLogoutBtn.addEventListener('click', handleLogout);


        function renderAdminDashboard() {
            loadAdminDashboard();
            const adminContentDiv = document.getElementById('admin-dashboard-content');
            if(adminContentDiv) adminContentDiv.style.display = 'block';
        }

        async function loadAdminDashboard() {
            if (!pendingOrdersList) return;
            pendingOrdersList.innerHTML = `<p>${_t('Loading Orders...')}</p>`;
            db.collection('orders').where('status', '==', 'pending').orderBy('orderDate', 'desc')
                .onSnapshot(snapshot => {
                    pendingOrdersList.innerHTML = '';
                    if (snapshot.empty) {
                        pendingOrdersList.innerHTML = `<p>${_t('No pending orders.')}</p>`;
                        return;
                    }
                    snapshot.forEach(doc => {
                        const order = doc.data();
                        const orderId = doc.id;
                        const orderItemDiv = document.createElement('div');
                        orderItemDiv.classList.add('order-item');
                        orderItemDiv.innerHTML = `
                            <h4>${_t('Department:')} ${order.category ? _t(order.category.charAt(0).toUpperCase() + order.category.slice(1)) : 'N/A'}</h4>
                            <p><strong>${_t('Order Date:')}</strong> ${order.orderDate.toDate().toLocaleString()}</p>
                            <ul>
                                ${Object.keys(order.products).map(productId => `
                                    <li>${currentLanguage === 'en' ? order.products[productId].name_en : order.products[productId].name_ar}: <strong>${order.products[productId].quantity}</strong> ${order.products[productId].type}</li>
                                `).join('')}
                            </ul>
                            <div class="button-group" style="margin-top:10px; justify-content: flex-end;">
                                <button class="button-success deliver-order-btn" data-order-id="${orderId}">${_t('Delivered')}</button>
                                <button class="button-danger cancel-order-btn" data-order-id="${orderId}">${_t('Cancel')}</button>
                            </div>
                        `;
                        pendingOrdersList.appendChild(orderItemDiv);
                    });

                    document.querySelectorAll('.deliver-order-btn').forEach(button => {
                        button.removeEventListener('click', handleDeliverOrder);
                        button.addEventListener('click', handleDeliverOrder);
                    });
                    document.querySelectorAll('.cancel-order-btn').forEach(button => {
                        button.removeEventListener('click', handleCancelOrder);
                        button.addEventListener('click', handleCancelOrder);
                    });
                }, error => {
                    console.error("Error loading pending orders:", error);
                    displayMessage(document.getElementById('admin-message-area'), `${_t('Error loading orders:')} ${error.message}`, 'error');
                });
        }

        async function handleDeliverOrder(event) {
            const orderId = event.target.dataset.orderId;
            const orderRef = db.collection('orders').doc(orderId);
            if (!confirm(_t('Are you sure you want to mark this order as delivered and update stock?'))) return;

            displayMessage(document.getElementById('admin-message-area'), _t('Order being delivered...'), 'info');
            try {
                const orderDoc = await orderRef.get();
                if (orderDoc.exists) {
                    const order = orderDoc.data();
                    const batch = db.batch();
                    let stockSufficient = true;

                    for (const productId in order.products) {
                        const productOrder = order.products[productId];
                        const productRef = db.collection('stock').doc(productId);
                        const stockDoc = await productRef.get();
                        if (stockDoc.exists) {
                            const currentStock = stockDoc.data().quantity;
                            if (currentStock < productOrder.quantity) {
                                stockSufficient = false;
                                displayMessage(document.getElementById('admin-message-area'), `${_t('Warning:')} ${_t('Insufficient stock for')} ${currentLanguage === 'en' ? productOrder.name_en : productOrder.name_ar}. ${_t('Available:')} ${currentStock}`, 'error');
                                break;
                            }
                            batch.update(productRef, {
                                quantity: firebase.firestore.FieldValue.increment(-productOrder.quantity)
                            });
                        } else {
                            stockSufficient = false;
                            displayMessage(document.getElementById('admin-message-area'), `${_t('Warning:')} ${currentLanguage === 'en' ? productOrder.name_en : productOrder.name_ar} ${_t('is out of stock.')}`, 'error');
                            break;
                        }
                    }
                    if (stockSufficient) {
                        batch.update(orderRef, { status: 'delivered', deliveredAt: firebase.firestore.FieldValue.serverTimestamp() });
                        await batch.commit();
                        displayMessage(document.getElementById('admin-message-area'), _t('Order successfully delivered and stock updated.'), 'success');
                    } else {
                         displayMessage(document.getElementById('admin-message-area'), _t('Failed to deliver order due to stock issues.'), 'error');
                    }
                }
            } catch (error) {
                console.error("Error delivering order:", error);
                displayMessage(document.getElementById('admin-message-area'), `${_t('Failed to deliver order:')} ${error.message}`, 'error');
            }
        }

        async function handleCancelOrder(event) {
            const orderId = event.target.dataset.orderId;
            if (!confirm(_t('Are you sure you want to cancel this order?'))) return;
            displayMessage(document.getElementById('admin-message-area'), _t('Order being cancelled...'), 'info');
            try {
                await db.collection('orders').doc(orderId).update({
                    status: 'cancelled',
                    cancelledAt: firebase.firestore.FieldValue.serverTimestamp()
                });
                displayMessage(document.getElementById('admin-message-area'), _t('Order successfully cancelled.'), 'success');
            } catch (error) {
                displayMessage(document.getElementById('admin-message-area'), `${_t('Failed to cancel order:')} ${error.message}`, 'error');
            }
        }

        if (addProductForm) {
            addProductForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const nameEn = document.getElementById('product-name-en').value.trim();
                const nameAr = document.getElementById('product-name-ar').value.trim();
                const quantity = parseInt(document.getElementById('product-quantity').value);
                const lowStockAlertQuantity = parseInt(document.getElementById('low-stock-alert-quantity').value);
                const category = document.getElementById('product-category').value;
                const quantityType = document.getElementById('quantity-type').value;

                if (!nameEn || !nameAr || isNaN(quantity) || quantity < 0 || isNaN(lowStockAlertQuantity) || lowStockAlertQuantity < 0 || !category || !quantityType) {
                    displayMessage(addProductMessageArea, 'Please fill all fields correctly.', 'error'); return;
                }
                displayMessage(addProductMessageArea, _t('Product being added...'), 'info');
                try {
                    const q = db.collection('products')
                                .where('name_en', '==', nameEn)
                                .where('category', '==', category);
                    const existingProductSnapshot = await q.get();

                    if (!existingProductSnapshot.empty) {
                        displayMessage(addProductMessageArea, 'Product already exists in this category.', 'error');
                        return;
                    }

                    const newProductRef = db.collection('products').doc();
                    await newProductRef.set({
                        name_en: nameEn, name_ar: nameAr, category: category, quantity_type: quantityType,
                        createdAt: firebase.firestore.FieldValue.serverTimestamp()
                    });
                    await db.collection('stock').doc(newProductRef.id).set({
                        productId: newProductRef.id, name_en: nameEn, name_ar: nameAr,
                        quantity: quantity, lowStockAlert: lowStockAlertQuantity, category: category,
                        quantity_type: quantityType, updatedAt: firebase.firestore.FieldValue.serverTimestamp()
                    });
                    displayMessage(addProductMessageArea, 'Product successfully added.', 'success');
                    addProductForm.reset();
                } catch (error) {
                    displayMessage(addProductMessageArea, `${_t('Failed to add product:')} ${error.message}`, 'error');
                }
            });
        }

        async function loadLowStockAlerts() {
            if (!lowStockProductsList) return;
            lowStockProductsList.innerHTML = `<p>${_t('Loading Inventory Alerts...')}</p>`;
            db.collection('stock').onSnapshot(snapshot => {
                lowStockProductsList.innerHTML = '';
                let hasLowStock = false;
                snapshot.forEach(doc => {
                    const product = doc.data();
                    if (product.quantity <= product.lowStockAlert && product.quantity > 0) {
                        hasLowStock = true;
                        const alertItemDiv = document.createElement('div');
                        alertItemDiv.classList.add('alert-item');
                        alertItemDiv.innerHTML = `
                            <p><strong>${currentLanguage === 'en' ? product.name_en : product.name_ar}</strong></p>
                            <p>${_t('Current Stock:')} ${product.quantity} ${product.quantity_type}</p>
                            <p>${_t('Alert Quantity:')} ${product.lowStockAlert}</p>
                            <button class="button-info remove-alert-btn" data-product-id="${doc.id}">${_t('Remove Alert (Display Only)')}</button>
                        `;
                        lowStockProductsList.appendChild(alertItemDiv);
                    }
                });
                if (!hasLowStock) {
                    lowStockProductsList.innerHTML = `<p>${_t('No low stock alerts.')}</p>`;
                }
            }, error => {
                displayMessage(document.getElementById('low-stock-message-area'), `${_t('Error loading inventory alerts:')} ${error.message}`, 'error');
            });
        }
        document.addEventListener('click', async (e) => {
            if (e.target.classList.contains('remove-alert-btn')) {
                e.target.closest('.alert-item').remove();
                displayMessage(document.getElementById('low-stock-message-area'), _t('Alert successfully removed (display only).'), 'success');
            }
        });


        async function loadTotalOrderReport(category = 'all') {
            if(!totalOrderReportContent) return;
            totalOrderReportContent.innerHTML = `<p>${_t('Loading Report...')}</p>`;
            displayMessage(document.getElementById('report-message-area'), _t('Loading Report...'), 'info');

            const today = new Date();
            let reportStartMonth = today.getMonth();
            let reportStartYear = today.getFullYear();

            if (today.getDate() < 20) {
                reportStartMonth -= 1;
            }
            reportStartMonth -=2;

            if (reportStartMonth < 0) {
                reportStartMonth += 12;
                reportStartYear -=1;
            }
            const startDate = new Date(reportStartYear, reportStartMonth, 20);

            let query = db.collection('orders')
                .where('status', '==', 'delivered')
                .where('deliveredAt', '>=', startDate);

            if (category !== 'all') {
                query = query.where('category', '==', category);
            }

            try {
                const snapshot = await query.orderBy('deliveredAt', 'desc').get();
                const orders = [];
                snapshot.forEach(doc => {
                    orders.push({ id: doc.id, ...doc.data() });
                });

                if (orders.length === 0) {
                    totalOrderReportContent.innerHTML = `<p>${_t('No delivered order reports found.')}</p>`;
                    displayMessage(document.getElementById('report-message-area'), _t('No delivered order reports found.'), 'info');
                    return;
                }

                const groupedOrders = {};
                orders.forEach(order => {
                    if (order.deliveredAt) {
                        const deliveredDate = order.deliveredAt.toDate();
                        let year = deliveredDate.getFullYear();
                        let month = deliveredDate.getMonth();

                        let reportMonthKey;
                        if (deliveredDate.getDate() >= 20) {
                            let nextMonth = month + 1;
                            let nextYear = year;
                            if (nextMonth > 11) { nextMonth = 0; nextYear++; }
                            reportMonthKey = `${nextYear}-${(nextMonth + 1).toString().padStart(2, '0')}`;
                        } else {
                            reportMonthKey = `${year}-${(month + 1).toString().padStart(2, '0')}`;
                        }

                        if (!groupedOrders[reportMonthKey]) {
                            groupedOrders[reportMonthKey] = [];
                        }
                        groupedOrders[reportMonthKey].push(order);
                    }
                });

                let reportHtml = '';
                const sortedMonthKeys = Object.keys(groupedOrders).sort().reverse();

                if (sortedMonthKeys.length === 0) {
                     totalOrderReportContent.innerHTML = `<p>${_t('No delivered order reports found.')}</p>`;
                     displayMessage(document.getElementById('report-message-area'), _t('No delivered order reports found.'), 'info');
                     return;
                }

                sortedMonthKeys.forEach(monthKey => {
                    const [year, monthNum] = monthKey.split('-');
                    const monthDate = new Date(year, parseInt(monthNum)-1, 1);
                    const monthName = monthDate.toLocaleString(currentLanguage === 'ar' ? 'ar-SA' : 'en-US', { month: 'long', year: 'numeric' });

                    reportHtml += `<div class="report-month-section" id="report-section-${monthKey.replace('-', '_')}">`;
                    reportHtml += `<h3>${_t('Report for period ending')} ${monthName} 19</h3>`;
                    reportHtml += `<ul>`;
                    groupedOrders[monthKey].forEach(order => {
                        reportHtml += `
                            <li class="order-item">
                                <p><strong>${_t('Order ID:')}</strong> ${order.id}</p>
                                <p><strong>${_t('Department:')}</strong> ${order.category ? _t(order.category.charAt(0).toUpperCase() + order.category.slice(1)) : 'N/A'}</p>
                                <p><strong>${_t('Delivery Date:')}</strong> ${order.deliveredAt.toDate().toLocaleString()}</p>
                                <ul>
                                    ${Object.keys(order.products).map(productId => `
                                        <li>${currentLanguage === 'en' ? order.products[productId].name_en : order.products[productId].name_ar}: <strong>${order.products[productId].quantity}</strong> ${order.products[productId].type}</li>
                                    `).join('')}
                                </ul>
                            </li>
                        `;
                    });
                    reportHtml += `</ul>`;
                     reportHtml += `<button class="button-info share-report-btn" data-month-key="${monthKey}">${_t('Share/Download PDF')}</button><hr style="margin-top:20px; margin-bottom:20px;">`;
                    reportHtml += `</div>`;
                });
                totalOrderReportContent.innerHTML = reportHtml;
                displayMessage(document.getElementById('report-message-area'), _t('Report load successful.'), 'success');

                document.querySelectorAll('.share-report-btn').forEach(button => {
                    button.removeEventListener('click', handleShareReport);
                    button.addEventListener('click', handleShareReport);
                });

            } catch (error) {
                console.error("Error loading total order report:", error);
                displayMessage(document.getElementById('report-message-area'), `${_t('Failed to load report:')} ${error.message}`, 'error');
            }
        }

        async function handleShareReport(event) {
            const monthKey = event.target.dataset.monthKey;
            const reportElementContainer = document.getElementById(`report-section-${monthKey.replace('-', '_')}`);

            if (!reportElementContainer) {
                displayMessage(document.getElementById('report-message-area'), _t('Report content not found.'), 'error');
                return;
            }
            displayMessage(document.getElementById('report-message-area'), _t('Generating PDF...'), 'info');

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({
                orientation: 'p',
                unit: 'pt',
                format: 'a4'
            });

            const contentToPrint = reportElementContainer.cloneNode(true);
            contentToPrint.querySelectorAll('.share-report-btn, hr').forEach(el => el.remove());

            const pdfTitle = contentToPrint.querySelector('h3').textContent;
            doc.text(pdfTitle, 40, 40);

            await doc.html(contentToPrint, {
                callback: function (doc) {
                    doc.save(`Order_Report_${monthKey}.pdf`);
                    displayMessage(document.getElementById('report-message-area'), _t('PDF successfully downloaded.'), 'success');
                },
                x: 20,
                y: 60,
                width: 555,
                windowWidth: contentToPrint.scrollWidth || 800,
                html2canvas: {
                    scale: 0.70,
                    logging: false,
                    useCORS: true
                },
                 autoPaging: 'slice'
            });
        }


        async function loadStockAndRestock() {
            if (!currentStockList) return;
            currentStockList.innerHTML = `<p>${_t('Loading Stock...')}</p>`;
            db.collection('stock').onSnapshot(snapshot => {
                currentStockList.innerHTML = '';
                if (snapshot.empty) {
                    currentStockList.innerHTML = `<p>${_t('No stock items.')}</p>`;
                    return;
                }
                snapshot.forEach(doc => {
                    const product = doc.data();
                    const stockItemDiv = document.createElement('div');
                    stockItemDiv.classList.add('stock-item');
                    stockItemDiv.innerHTML = `
                        <p><strong>${currentLanguage === 'en' ? product.name_en : product.name_ar}</strong> - ${_t('Remaining Stock:')} <strong>${product.quantity}</strong> ${product.quantity_type}</p>
                        <div style="display:flex; gap:10px; align-items:center;">
                            <input type="number" class="input-field restock-quantity" data-en-placeholder="${_t('Re-stock Quantity')}" data-ar-placeholder="${_t('Re-stock Quantity')}" placeholder="${_t('Re-stock Quantity')}" data-product-id="${doc.id}" min="1" style="flex-grow:1; margin-bottom:0;">
                            <button class="button-info restock-btn" data-product-id="${doc.id}">${_t('Re-stock')}</button>
                        </div>
                    `;
                    currentStockList.appendChild(stockItemDiv);
                });

                document.querySelectorAll('.restock-btn').forEach(button => {
                    button.removeEventListener('click', handleRestock);
                    button.addEventListener('click', handleRestock);
                });
            }, error => {
                displayMessage(document.getElementById('stock-message-area'), `${_t('Failed to load stock:')} ${error.message}`, 'error');
            });
        }

        async function handleRestock(event) {
            const productId = event.target.dataset.productId;
            const quantityInput = event.target.closest('div').querySelector('.restock-quantity');
            const restockQuantity = parseInt(quantityInput.value);

            if (isNaN(restockQuantity) || restockQuantity <= 0) {
                displayMessage(document.getElementById('stock-message-area'), _t('Please enter a valid quantity.'), 'error'); return;
            }
            displayMessage(document.getElementById('stock-message-area'), _t('Stock being updated...'), 'info');
            try {
                await db.collection('stock').doc(productId).update({
                    quantity: firebase.firestore.FieldValue.increment(restockQuantity),
                    updatedAt: firebase.firestore.FieldValue.serverTimestamp()
                });
                displayMessage(document.getElementById('stock-message-area'), _t('Stock successfully updated.'), 'success');
                quantityInput.value = '';
            } catch (error) {
                displayMessage(document.getElementById('stock-message-area'), `${_t('Failed to re-stock:')} ${error.message}`, 'error');
            }
        }

        // --- New Functions for Return to Supplier ---
        async function loadProductsForReturnToSupplier() {
            if (!returnToSupplierProductSelect) return;
            returnToSupplierProductSelect.innerHTML = `<option value="">${_t('Select Product')}</option>`;
            try {
                const snapshot = await db.collection('stock').orderBy('name_en').get();
                if (snapshot.empty) {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = _t('No products available');
                    option.disabled = true;
                    returnToSupplierProductSelect.appendChild(option);
                    return;
                }
                snapshot.forEach(doc => {
                    const product = doc.data();
                    const option = document.createElement('option');
                    option.value = doc.id;
                    option.textContent = `${currentLanguage === 'en' ? product.name_en : product.name_ar} (${_t('Remaining Stock:')} ${product.quantity} ${product.quantity_type})`;
                    option.dataset.productNameEn = product.name_en;
                    option.dataset.productNameAr = product.name_ar;
                    option.dataset.quantityType = product.quantity_type;
                    returnToSupplierProductSelect.appendChild(option);
                });
            } catch (error) {
                displayMessage(returnToSupplierMessageArea, `${_t('Failed to load return products:')} ${error.message}`, 'error');
            }
        }

        if (returnProductToSupplierForm) {
            returnProductToSupplierForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const selectedOption = returnToSupplierProductSelect.options[returnToSupplierProductSelect.selectedIndex];
                const productId = selectedOption.value;
                const productNameEn = selectedOption.dataset.productNameEn;
                const productNameAr = selectedOption.dataset.productNameAr;
                const quantityType = selectedOption.dataset.quantityType;

                const returnQuantity = parseInt(document.getElementById('return-to-supplier-quantity').value);
                const supplierName = document.getElementById('return-to-supplier-name').value.trim();

                if (!productId || isNaN(returnQuantity) || returnQuantity <= 0 || !supplierName) {
                    displayMessage(returnToSupplierMessageArea, _t('Please select product, valid quantity, and supplier name.'), 'error');
                    return;
                }

                if (!confirm(_t('Are you sure you want to return this product to the supplier? Stock will be reduced.'))) return;

                displayMessage(returnToSupplierMessageArea, _t('Return to supplier in progress...'), 'info');
                try {
                    const stockRef = db.collection('stock').doc(productId);
                    const stockDoc = await stockRef.get();

                    if (!stockDoc.exists) {
                        displayMessage(returnToSupplierMessageArea, _t('Product not found in stock.'), 'error');
                        return;
                    }

                    const currentStock = stockDoc.data().quantity;
                    if (returnQuantity > currentStock) {
                        displayMessage(returnToSupplierMessageArea, _t('Cannot return more than current stock.'), 'error');
                        return;
                    }

                    // Decrease stock
                    await stockRef.update({
                        quantity: firebase.firestore.FieldValue.increment(-returnQuantity),
                        updatedAt: firebase.firestore.FieldValue.serverTimestamp()
                    });

                    // Log the return to supplier
                    await db.collection('returnsToSupplier').add({
                        productId: productId,
                        productName_en: productNameEn,
                        productName_ar: productNameAr,
                        quantity_type: quantityType,
                        returnQuantity: returnQuantity,
                        supplierName: supplierName,
                        returnDate: firebase.firestore.FieldValue.serverTimestamp(),
                        status: 'pending_replacement' // 'pending_replacement', 'replaced'
                    });

                    displayMessage(returnToSupplierMessageArea, _t('Product successfully returned to supplier and stock updated.'), 'success');
                    returnProductToSupplierForm.reset();
                    loadProductsForReturnToSupplier(); // Refresh product list (shows updated stock)
                    // loadReturnNotifications(); // onSnapshot will update this
                } catch (error) {
                    console.error("Error returning product to supplier:", error);
                    displayMessage(returnToSupplierMessageArea, `${_t('Failed to return product to supplier.')} ${error.message}`, 'error');
                }
            });
        }

        async function loadReturnNotifications() {
            if (!pendingReturnNotificationsList) return;
            pendingReturnNotificationsList.innerHTML = `<p>${_t('Loading...')}</p>`;

            db.collection('returnsToSupplier').where('status', '==', 'pending_replacement').orderBy('returnDate', 'desc')
                .onSnapshot(snapshot => {
                    pendingReturnNotificationsList.innerHTML = '';
                    if (snapshot.empty) {
                        pendingReturnNotificationsList.innerHTML = `<p>${_t('No pending replacements.')}</p>`;
                        return;
                    }
                    snapshot.forEach(doc => {
                        const returnData = doc.data();
                        const returnId = doc.id;
                        const itemDiv = document.createElement('div');
                        itemDiv.classList.add('return-notification-item');
                        itemDiv.innerHTML = `
                            <h4>${currentLanguage === 'en' ? returnData.productName_en : returnData.productName_ar}</h4>
                            <p><strong>${_t('Returned to:')}</strong> ${returnData.supplierName}</p>
                            <p><strong>${_t('Quantity:')}</strong> ${returnData.returnQuantity} ${returnData.quantity_type}</p>
                            <p><strong>${_t('Return Date:')}</strong> ${returnData.returnDate.toDate().toLocaleString()}</p>
                            <button class="button-success process-replacement-btn" data-return-id="${returnId}" data-product-id="${returnData.productId}" data-quantity="${returnData.returnQuantity}">${_t('Mark as Replaced & Re-stock')}</button>
                        `;
                        pendingReturnNotificationsList.appendChild(itemDiv);
                    });

                    document.querySelectorAll('.process-replacement-btn').forEach(button => {
                        button.removeEventListener('click', handleProcessReplacement);
                        button.addEventListener('click', handleProcessReplacement);
                    });

                }, error => {
                    console.error("Error loading return notifications:", error);
                    pendingReturnNotificationsList.innerHTML = `<p>${_t('Error loading notifications.')}</p>`;
                    displayMessage(returnToSupplierMessageArea, `${_t('Error loading notifications:')} ${error.message}`, 'error');
                });
        }

        async function handleProcessReplacement(event) {
            const returnId = event.target.dataset.returnId;
            const productId = event.target.dataset.productId;
            const quantity = parseInt(event.target.dataset.quantity);

            if (!confirm(_t('Are you sure you want to mark this item as replaced and re-stock it?'))) return;

            displayMessage(returnToSupplierMessageArea, _t('Stock being updated...'), 'info');
            try {
                const stockRef = db.collection('stock').doc(productId);
                const returnRef = db.collection('returnsToSupplier').doc(returnId);

                // Increment stock
                await stockRef.update({
                    quantity: firebase.firestore.FieldValue.increment(quantity),
                    updatedAt: firebase.firestore.FieldValue.serverTimestamp()
                });

                // Update return status
                await returnRef.update({
                    status: 'replaced',
                    replacedDate: firebase.firestore.FieldValue.serverTimestamp()
                });

                displayMessage(returnToSupplierMessageArea, _t('Replacement processed and stock updated.'), 'success');
                // loadReturnNotifications(); // onSnapshot will update this
                loadProductsForReturnToSupplier(); // Refresh product list in dropdown to show updated stock
            } catch (error) {
                console.error("Error processing replacement:", error);
                displayMessage(returnToSupplierMessageArea, `${_t('Failed to process replacement.')} ${error.message}`, 'error');
            }
        }

        // --- End of New Functions for Return to Supplier ---


        async function loadUserProducts(category) {
            if(!productList || !category) return;
            productList.innerHTML = `<p>${_t('Loading Products...')}</p>`;
            db.collection('products').where('category', '==', category)
                .onSnapshot(productsSnapshot => {
                    productList.innerHTML = '';
                    if (productsSnapshot.empty) {
                        productList.innerHTML = `<p>${_t('No products in this category.')}</p>`;
                        return;
                    }
                    productsSnapshot.forEach(productDoc => {
                        const product = productDoc.data();
                        const productId = productDoc.id;

                        const productDiv = document.createElement('div');
                        productDiv.classList.add('product-item');
                        productDiv.innerHTML = `
                            <h4>${currentLanguage === 'en' ? product.name_en : product.name_ar}</h4>
                            <p>${_t('Available:')} <span id="stock-${productId}">${_t('Loading...')}</span> ${product.quantity_type}</p>
                            <div class="quantity-control">
                                <button class="add-to-cart-btn" data-product-id="${productId}" data-product-name-en="${product.name_en}" data-product-name-ar="${product.name_ar}" data-quantity-type="${product.quantity_type}">+</button>
                                <span id="cart-quantity-${productId}">${userCart[productId] ? userCart[productId].quantity : 0}</span>
                                <button class="remove-from-cart-btn" data-product-id="${productId}">-</button>
                            </div>
                        `;
                        productList.appendChild(productDiv);

                        db.collection('stock').doc(productId).onSnapshot(stockDoc => {
                            const stockSpan = document.getElementById(`stock-${productId}`);
                            if (stockSpan) {
                                stockSpan.textContent = stockDoc.exists ? stockDoc.data().quantity : '0';
                            }
                        }, stockError => {
                             console.error(`Error fetching stock for ${productId}:`, stockError);
                             const stockSpan = document.getElementById(`stock-${productId}`);
                             if(stockSpan) stockSpan.textContent = 'N/A';
                        });
                    });
                }, error => {
                    displayMessage(userOrderMessageArea, `${_t('Failed to load products:')} ${error.message}`, 'error');
                });
        }

        function updateCartSummary() {
            if (!userCartSummary) return;
            userCartSummary.innerHTML = '';
            if (Object.keys(userCart).length === 0) {
                userCartSummary.innerHTML = `<p>${_t('Your cart is empty.')}</p>`;
                return;
            }
            let cartHtml = '<ul>';
            for (const productId in userCart) {
                const item = userCart[productId];
                cartHtml += `<li>${currentLanguage === 'en' ? item.name_en : item.name_ar}: <strong>${item.quantity}</strong> ${item.type}</li>`;
            }
            cartHtml += '</ul>';
            userCartSummary.innerHTML = cartHtml;
        }

        document.addEventListener('click', async (e) => {
            if (e.target.classList.contains('add-to-cart-btn')) {
                const productId = e.target.dataset.productId;
                const productNameEn = e.target.dataset.productNameEn;
                const productNameAr = e.target.dataset.productNameAr;
                const quantityType = e.target.dataset.quantityType;

                const stockDoc = await db.collection('stock').doc(productId).get();
                const currentStock = stockDoc.exists ? stockDoc.data().quantity : 0;

                if (!userCart[productId]) {
                    userCart[productId] = { name_en: productNameEn, name_ar: productNameAr, quantity: 0, type: quantityType };
                }

                if (userCart[productId].quantity < currentStock) {
                    userCart[productId].quantity++;
                    displayMessage(userOrderMessageArea, _t('Added to cart.'), 'success');
                } else {
                    displayMessage(userOrderMessageArea, _t('Out of stock, cannot add more.'), 'error');
                }
                const cartQuantitySpan = document.getElementById(`cart-quantity-${productId}`);
                if(cartQuantitySpan) cartQuantitySpan.textContent = userCart[productId].quantity;
                updateCartSummary();

            } else if (e.target.classList.contains('remove-from-cart-btn')) {
                const productId = e.target.dataset.productId;
                if (userCart[productId] && userCart[productId].quantity > 0) {
                    userCart[productId].quantity--;
                     const cartQuantitySpan = document.getElementById(`cart-quantity-${productId}`);
                    if(cartQuantitySpan) cartQuantitySpan.textContent = userCart[productId].quantity;
                    if (userCart[productId].quantity === 0) {
                        delete userCart[productId];
                    }
                    displayMessage(userOrderMessageArea, _t('Removed from cart.'), 'success');
                }
                updateCartSummary();
            }
        });

        if (placeOrderBtn) {
            placeOrderBtn.addEventListener('click', async () => {
                if (Object.keys(userCart).length === 0) {
                    displayMessage(userOrderMessageArea, _t('Your cart is empty.'), 'error'); return;
                }
                if (!confirm(_t('Are you sure you want to place this order?'))) return;

                displayMessage(userOrderMessageArea, _t('Order being placed...'), 'info');
                let stockSufficient = true;
                let orderProducts = {};

                for (const productId in userCart) {
                    const requestedQuantity = userCart[productId].quantity;
                    if (requestedQuantity <= 0) continue;

                    const stockRef = db.collection('stock').doc(productId);
                    const stockDoc = await stockRef.get();

                    if (!stockDoc.exists || stockDoc.data().quantity < requestedQuantity) {
                        stockSufficient = false;
                        displayMessage(userOrderMessageArea, `${_t('Sorry, insufficient stock for')} ${currentLanguage === 'en' ? userCart[productId].name_en : userCart[productId].name_ar}. ${_t('Please check your cart.')}`, 'error');
                        break;
                    }
                    orderProducts[productId] = {
                        name_en: userCart[productId].name_en,
                        name_ar: userCart[productId].name_ar,
                        quantity: requestedQuantity,
                        type: userCart[productId].type
                    };
                }

                if (!stockSufficient) {
                    displayMessage(userOrderMessageArea, _t('Failed to place order due to stock issues.'), 'error');
                    return;
                }
                if (Object.keys(orderProducts).length === 0) {
                     displayMessage(userOrderMessageArea, _t('Your cart is effectively empty.'), 'error'); return;
                }


                try {
                    await db.collection('orders').add({
                        userId: auth.currentUser.uid,
                        category: currentUserCategory,
                        products: orderProducts,
                        orderDate: firebase.firestore.FieldValue.serverTimestamp(),
                        status: 'pending'
                    });
                    displayMessage(userOrderMessageArea, _t('Order successfully placed.'), 'success');
                    userCart = {};
                    updateCartSummary();
                    loadUserProducts(currentUserCategory);
                } catch (error) {
                    displayMessage(userOrderMessageArea, `${_t('Failed to place order:')} ${error.message}`, 'error');
                }
            });
        }

        async function loadUserOrders(userId) {
            if (!userOrdersList || !userId) return;
            userOrdersList.innerHTML = `<p>${_t('Loading your orders...')}</p>`;
            db.collection('orders').where('userId', '==', userId).orderBy('orderDate', 'desc')
                .onSnapshot(snapshot => {
                    userOrdersList.innerHTML = '';
                    if (snapshot.empty) {
                        userOrdersList.innerHTML = `<p>${_t('You have no orders.')}</p>`;
                        return;
                    }
                    snapshot.forEach(doc => {
                        const order = doc.data();
                        const orderId = doc.id;
                        const orderItemDiv = document.createElement('div');
                        orderItemDiv.classList.add('user-order-item');

                        let productDetailsHtml = '<ul class="editable-order-item-list">';
                        for (const productId in order.products) {
                            const product = order.products[productId];
                            productDetailsHtml += `
                                <li data-product-id="${productId}" data-order-id="${orderId}" class="order-item-detail">
                                    <div style="flex-grow: 1;">
                                        <span>${currentLanguage === 'en' ? product.name_en : product.name_ar}:</span>
                                        <span class="quantity-display"> ${product.quantity} ${product.type}</span>
                                    </div>
                                    ${order.status === 'pending' ? `
                                    <div class="edit-controls" style="display:flex; align-items:center; gap:5px;">
                                        <div class="edit-quantity-control">
                                            <button class="edit-order-item-btn button-secondary" data-action="decrease" style="padding:3px 6px; font-size:0.8em;">-</button>
                                            <input type="number" value="${product.quantity}" min="0" class="edit-item-quantity-input input-field" style="width:40px; padding:3px; font-size:0.8em; margin-bottom:0;">
                                            <button class="edit-order-item-btn button-secondary" data-action="increase" style="padding:3px 6px; font-size:0.8em;">+</button>
                                        </div>
                                        <button class="save-item-edit-btn button-success" data-product-id="${productId}" data-order-id="${orderId}" style="padding:5px 8px; font-size:0.8em;">${_t('Update')}</button>
                                        <button class="delete-item-btn button-danger" data-product-id="${productId}" data-order-id="${orderId}" style="padding:5px 8px; font-size:0.8em;">${_t('Delete')}</button>
                                    </div>
                                    ` : ''}
                                </li>`;
                        }
                        productDetailsHtml += '</ul>';

                        orderItemDiv.innerHTML = `
                            <p><strong>${_t('Order Date:')}</strong> ${order.orderDate.toDate().toLocaleString()}</p>
                            <p><strong>${_t('Status:')}</strong> <strong style="color: ${order.status === 'pending' ? 'var(--warning-color)' : order.status === 'delivered' ? 'var(--success-color)' : 'var(--danger-color)'}; text-transform: capitalize;">${_t(order.status)}</strong></p>
                            ${productDetailsHtml}
                            ${order.status === 'pending' ? `<div class="button-group" style="margin-top:10px; justify-content:flex-end;"><button class="button-danger cancel-user-order-btn" data-order-id="${orderId}">${_t('Cancel Order')}</button></div>` : ''}
                        `;
                        userOrdersList.appendChild(orderItemDiv);
                    });

                    document.querySelectorAll('.cancel-user-order-btn').forEach(button => {
                        button.removeEventListener('click', handleCancelUserOrder);
                        button.addEventListener('click', handleCancelUserOrder);
                    });
                    document.querySelectorAll('.edit-order-item-btn').forEach(button => {
                        button.removeEventListener('click', handleEditOrderItemQuantity);
                        button.addEventListener('click', handleEditOrderItemQuantity);
                    });
                    document.querySelectorAll('.delete-item-btn').forEach(button => {
                        button.removeEventListener('click', handleDeleteOrderItem);
                        button.addEventListener('click', handleDeleteOrderItem);
                    });
                    document.querySelectorAll('.save-item-edit-btn').forEach(button => {
                        button.removeEventListener('click', handleSaveOrderItemEdit);
                        button.addEventListener('click', handleSaveOrderItemEdit);
                    });

                }, error => {
                    displayMessage(userOrderMessageArea, `${_t('Failed to load your orders:')} ${error.message}`, 'error');
                });
        }

        function handleEditOrderItemQuantity(event) {
            const button = event.target;
            const action = button.dataset.action;
            const input = button.closest('.edit-quantity-control').querySelector('.edit-item-quantity-input');
            let currentValue = parseInt(input.value);
            if (action === 'increase') {
                input.value = currentValue + 1;
            } else if (action === 'decrease' && currentValue > 0) {
                input.value = currentValue - 1;
            }
        }

        async function handleSaveOrderItemEdit(event) {
            const button = event.target;
            const orderId = button.dataset.orderId;
            const productId = button.dataset.productId;
            const newQuantity = parseInt(button.closest('li').querySelector('.edit-item-quantity-input').value);

            if (isNaN(newQuantity) || newQuantity < 0) {
                displayMessage(userOrderMessageArea, _t('Please enter a valid quantity.'), 'error'); return;
            }
            displayMessage(userOrderMessageArea, _t('Order Update') + "...", 'info');
            try {
                const orderRef = db.collection('orders').doc(orderId);
                const orderDoc = await orderRef.get();
                if (!orderDoc.exists) {
                    displayMessage(userOrderMessageArea, _t('Order not found!'), 'error'); return;
                }
                const currentOrder = orderDoc.data();
                const products = { ...currentOrder.products };

                const stockDoc = await db.collection('stock').doc(productId).get();
                const availableStock = stockDoc.exists ? stockDoc.data().quantity : 0;

                if (newQuantity > 0 && newQuantity > availableStock) {
                     displayMessage(userOrderMessageArea, `${_t('Insufficient stock for')} ${currentLanguage === 'en' ? products[productId].name_en : products[productId].name_ar}. ${_t('Available:')} ${availableStock}.`, 'error');
                     return;
                }

                if (newQuantity === 0) {
                    delete products[productId];
                } else {
                    products[productId].quantity = newQuantity;
                }

                if (Object.keys(products).length === 0) {
                    await orderRef.update({ status: 'cancelled', cancelledAt: firebase.firestore.FieldValue.serverTimestamp(), products: {} });
                    displayMessage(userOrderMessageArea, _t('All items removed. Order successfully cancelled.'), 'success');
                } else {
                    await orderRef.update({ products: products, updatedAt: firebase.firestore.FieldValue.serverTimestamp() });
                    displayMessage(userOrderMessageArea, _t('Order successfully updated.'), 'success');
                }
            } catch (error) {
                displayMessage(userOrderMessageArea, `${_t('Failed to update order.')} ${error.message}`, 'error');
            }
        }
        async function handleDeleteOrderItem(event) {
            const button = event.target;
            const orderId = button.dataset.orderId;
            const productId = button.dataset.productId;
            if (!confirm(_t('Are you sure you want to delete this item from the order?'))) return;

            displayMessage(userOrderMessageArea, _t('Order Update') + "...", 'info');
            try {
                const orderRef = db.collection('orders').doc(orderId);
                const orderDoc = await orderRef.get();
                 if (!orderDoc.exists) {
                    displayMessage(userOrderMessageArea, _t('Order not found!'), 'error'); return;
                }
                const currentOrder = orderDoc.data();
                const products = { ...currentOrder.products };
                delete products[productId];

                if (Object.keys(products).length === 0) {
                    await orderRef.update({ status: 'cancelled', cancelledAt: firebase.firestore.FieldValue.serverTimestamp(), products: {} });
                    displayMessage(userOrderMessageArea, _t('All items removed. Order successfully cancelled.'), 'success');
                } else {
                    await orderRef.update({ products: products, updatedAt: firebase.firestore.FieldValue.serverTimestamp() });
                    displayMessage(userOrderMessageArea, _t('Order successfully updated.'), 'success');
                }
            } catch (error) {
                 displayMessage(userOrderMessageArea, `${_t('Failed to update order.')} ${error.message}`, 'error');
            }
        }


        async function handleCancelUserOrder(event) {
            const orderId = event.target.dataset.orderId;
            if (!confirm(_t('Are you sure you want to cancel this order?'))) return;
            displayMessage(userOrderMessageArea, _t('Order being cancelled...'), 'info');
            try {
                await db.collection('orders').doc(orderId).update({
                    status: 'cancelled',
                    cancelledAt: firebase.firestore.FieldValue.serverTimestamp()
                });
                displayMessage(userOrderMessageArea, _t('Order cancelled successfully.'), 'success');
            } catch (error) {
                displayMessage(userOrderMessageArea, `${_t('Failed to cancel order:')} ${error.message}`, 'error');
            }
        }

        // --- Admin Sub-Navigation Button Click Handlers ---
        if (showPendingOrdersBtn) showPendingOrdersBtn.addEventListener('click', () => {
            showSection(adminDashboardSection); renderAdminDashboard();
        });
        if (addProductBtn) addProductBtn.addEventListener('click', () => showSection(addProductSection));
        if (lowStockBtn) lowStockBtn.addEventListener('click', () => {
            showSection(lowStockAlertSection); loadLowStockAlerts();
        });
        if (totalOrderReportBtn) totalOrderReportBtn.addEventListener('click', () => {
            showSection(totalOrderReportSection);
            const reportCategorySelect = document.getElementById('report-category');
            if (reportCategorySelect) loadTotalOrderReport(reportCategorySelect.value);
            else loadTotalOrderReport();
        });
        if (stockRestockBtn) stockRestockBtn.addEventListener('click', () => {
            showSection(stockRestockSection); loadStockAndRestock();
        });
        if (orderReturnBtn) orderReturnBtn.addEventListener('click', () => {
            showSection(orderReturnSection);
            loadProductsForReturnToSupplier();
            loadReturnNotifications(); // Load existing notifications
        });

        const generateReportBtn = document.getElementById('generate-report-btn');
        if (generateReportBtn) {
            generateReportBtn.addEventListener('click', () => {
                const selectedCategory = document.getElementById('report-category').value;
                loadTotalOrderReport(selectedCategory);
            });
        }

        backToAdminBtns.forEach(button => {
            button.addEventListener('click', () => {
                showSection(adminDashboardSection); renderAdminDashboard();
            });
        });

        window.addEventListener('load', () => {
            initializeFirebaseCollections();
            const initialHash = window.location.hash.substring(1);
            if (initialHash && document.getElementById(initialHash)) {
                if(auth.currentUser){
                     const targetSection = document.getElementById(initialHash);
                     const isAdminSection = ['admin-dashboard-section', 'add-product-section', 'low-stock-alert-section', 'total-order-report-section', 'stock-restock-section', 'order-return-section'].includes(initialHash);
                     const isUserSection = initialHash === 'user-panel-section';

                     if(targetSection){
                        if (isAdminSection && currentUserCategory === 'admin') {
                             showSection(targetSection);
                             if (initialHash === 'admin-dashboard-section') renderAdminDashboard();
                             else if (initialHash === 'low-stock-alert-section') loadLowStockAlerts();
                             else if (initialHash === 'total-order-report-section') loadTotalOrderReport();
                             else if (initialHash === 'stock-restock-section') loadStockAndRestock();
                             else if (initialHash === 'order-return-section') {
                                 loadProductsForReturnToSupplier();
                                 loadReturnNotifications();
                             }
                        } else if (isUserSection && ['kitchen', 'men', 'women'].includes(currentUserCategory)) {
                             showSection(targetSection);
                             loadUserProducts(currentUserCategory);
                             loadUserOrders(auth.currentUser.uid);
                             updateCartSummary();
                        } else if (initialHash === 'login-section'){
                            showSection(loginSection);
                        }
                     }
                } else {
                     if (initialHash === 'login-section' || !document.getElementById(initialHash)) {
                        showSection(loginSection);
                    }
                }
            } else if (!auth.currentUser) {
                showSection(loginSection);
            }
             setLanguage(currentLanguage);
        });

    </script>
</body>
</html>