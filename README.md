# MyWebsite — Project Report

## Overview
An enhanced PHP web application built on the lab starter code, redesigned with a modular structure, Bootstrap 5 theming, and extended features.

---

## Theme
**Custom Bootstrap 5 theme** with a dark navy/indigo color palette (`#0f172a` background, `#4f46e5` primary accent). Typography uses **Plus Jakarta Sans** (Google Fonts) for a modern, clean feel. The design language follows a "product dashboard" aesthetic — professional, minimal, and data-focused.

No external paid template was used. All styling is custom CSS built on top of Bootstrap 5's utility and component system, defined in `assets/css/style.css` using CSS custom properties.

---

## Project Structure

```
mywebsite/
├── index.php              # Home / hero page
├── register.php           # User registration
├── login.php              # User login
├── dashboard.php          # Protected dashboard with stats
├── users.php              # Feature 1: User directory table
├── profile.php            # Feature 2: Profile update & delete
├── logout.php             # Session destroy & redirect
├── mywebsite_db.sql       # Database schema + sample data
│
├── includes/
│   ├── connection.php     # DB connection using constants
│   ├── header.php         # Navbar, session init, flash alerts
│   └── footer.php         # Footer + JS imports
│
└── assets/
    ├── css/style.css      # All custom styles (CSS vars, layout)
    └── js/main.js         # Navbar effects, password strength, confirm delete, table search
```

---

## Implemented Features

### Feature 1 — User Directory (`users.php`)
- Displays all registered users in a styled Bootstrap table with avatar initials, join date, and row highlights.
- **Live search filter** (JavaScript, no page reload) lets users filter by name or email instantly.
- "You" badge highlights the currently logged-in user's own row.
- Protected by session auth guard — only logged-in users can access.

### Feature 2 — Profile Management (`profile.php`)
- **View profile**: Profile banner card showing avatar (initial), name, email, user ID, and join date.
- **Update profile**: Edit name and email. Optionally change password (with confirmation field).
- **Password strength meter**: Visual bar + label (Weak / Fair / Good / Strong) updates in real time.
- **Delete account**: Triggers a Bootstrap modal for confirmation before permanently removing the record and destroying the session.

---

## Security Practices

| Practice | Implementation |
|---|---|
| Password hashing | `password_hash()` with `PASSWORD_BCRYPT` on registration and profile update |
| Password verification | `password_verify()` on login — never plain-text comparison |
| SQL injection prevention | `mysqli_real_escape_string()` on all user inputs before queries |
| Duplicate email check | Checked before insert (registration) and before update (profile) |
| Session auth guards | All protected pages redirect to login if `$_SESSION['user_id']` is unset |
| XSS prevention | `htmlspecialchars()` on all output of user-supplied data |
| Form validation | Server-side validation on all forms with clear error messages |

---

## Bootstrap Components Used

- **Navbar** (responsive, collapsible, sticky-top, with dropdown menu for logged-in user)
- **Cards** (stat cards, feature cards, profile card, action cards)
- **Tables** (hover, responsive wrapper, thead styling)
- **Alerts** (flash message banners with auto-dismiss via JS)
- **Modal** (delete account confirmation dialog)
- **Forms** (input groups with icons, validation states)
- **Badges** (user role indicators)
- **Buttons** (primary, outline, danger, sizes)

---

## How to Run

1. Copy the `mywebsite/` folder into your XAMPP `htdocs/` directory.
2. Open **phpMyAdmin** and import `mywebsite_db.sql`.
3. Visit `http://localhost/mywebsite/` in your browser.
4. Register a new account or use the sample credentials:
   - Email: `alice@example.com` | Password: `password`

---

*Built with PHP 8+, MySQL, Bootstrap 5.3, Bootstrap Icons, and Plus Jakarta Sans.*
this is my readme so i want to cerate repositry so which name wold you suggest for me
