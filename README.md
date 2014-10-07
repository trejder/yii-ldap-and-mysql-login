# Yii 1.x base application with LDAP and MySQL support

This is pure, auto-generated Yii 1.x application, the same, as you get after `yiic webapp path`, enhanced to use MySQL- and LDAP-based user authentication.

## Changes

1. General settings (MySQL and LDAP connection) stored in external `includes/settings.inc` file. Can be changed locally (file is not added to repository) without touching `protected/config/main.php` (see below for details).

2. Login process uses e-mail address instead of real login. There's no checking, so you can force any string, but field in database/model, that is used is named `email`, not `login`.

3. Database connection configured to use MySQL (see below) and to authenticate user against `user` table in MySQL database or LDAP directory.

4. All files with _Windows_ line endings (`CR/LF`) and _four spaces_ indentation.