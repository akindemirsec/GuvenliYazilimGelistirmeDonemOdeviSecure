# Secure Software Development - Secure Version

(Gazi Üniversitesi - Bilişim Güvenliği Teknolojisi bölümünün Güvenli Yazılım Geliştirme dersi dönem ödevi için yapılmış zafiyetli web uygulamasının güvenli versiyonudur.) (Vulnerable version https://github.com/akindemirsec/GuvenliYazilimGelistirmeDonemOdevi)

## Features

- User authentication with hashed passwords
- Product listing and search functionality
- Cart management
- Profile management with secure image upload
- Admin features for adding, editing, and deleting products

## Security Measures

This application implements several security measures to protect against common web application vulnerabilities:

1. **SQL Injection Protection**: All database queries use parameterized statements to prevent SQL injection.
2. **Cross-Site Scripting (XSS) Protection**: User inputs are properly sanitized to prevent XSS attacks.
3. **Cross-Site Request Forgery (CSRF) Protection**: Forms include CSRF tokens to prevent CSRF attacks.
4. **Secure File Upload**: Profile image uploads are validated and sanitized to ensure security.
