# Yii base app with LDAP and MySQL support

This is pure, auto-generated Yii 1.x application, the same, as you get after `yiic webapp path`, enhanced to use MySQL- and LDAP-based user authentication.

It changes default application to allow users to login either with e-mail and password stored in local `users` table (in MySQL database) or authenticate with e-mail against given LDAP directory and login only after successful authentication there.

## Changes

1. General settings (MySQL and LDAP connection) stored in external `includes/settings.inc` file. Can be changed locally (file is not added to repository) without touching `protected/config/main.php` (see below for details).

2. Login process uses e-mail address instead of real login. There's no checking, so you can force any string, but field in database/model, that is used is named `email`, not `login`.

3. Database connection configured to use MySQL (see below) and to authenticate user against `user` table in MySQL database or LDAP directory.

4. All files with _Windows_ line endings (`CR/LF`) and _four spaces_ indentation.

## Installation

1. Clone repository locally.

2. Modify `protected/data/schema.mysql.sql` and import it into your MySQL database or create `users` table add required users in any other way.

3. Create `includes/settings.inc` with following contact (modify to suit you):

    <?php
        /**
         * Database configuration; used in "/protected/config/main.php", in 'db' key.
         */
        $yiihost = 'localhost';
        $yiiuser = 'user';
        $yiipass = 'pass';
        $yiiname = 'name';
        
        /**
         * LDAP configuration; used in "/protected/config/main.php", in 'params' key
         * and in /protected/components/UserIdentity.php.
         */
        $ldapHost = 'ldap.company.com';
        $ldapDn = 'vd=company.com,o=hosting,dc=company,dc=com';

        /**
         * Other configuration; used in "/protected/config/main.php", in 'params' key.
         */
        $adminEmail = 'webmaster@company.com';
    ?>

That should be all. Modify, test, run, enjoy!

## Usage

Your users can login with either local (MySQL `users` table) or external, LDAP-based credentials.

For security reasons, user **must** exist in `users` table, even if it uses LDAP-based authentication. You only differentiate, whether user is authenticated via MySQL `users` table or LDAP directory by setting (MD5 hashed) password in `password` column or not.