# 🏭 Hosting your own instance of NetVOD

## Introduction

NetVOD is a free and open source project. You can host your own instance of NetVOD on your own server. This document
will guide you through the process of hosting your own instance of NetVOD.

## Requirements

- A web server (Apache, Nginx, etc.)
- PHP installed on your web server, version 8.0.25 or higher
- A MariaDB database, version 5.5.68 or higher
- Composer installed on your web server

## Web server configuration

### Apache

You can use the following .htaccess file to configure your Apache web server:

```apacheconf
Options -Indexes

RewriteEngine On
RewriteRule ^sql(/.*|)$ - [NC,F]
RewriteRule ^src(/.*|)$ - [NC,F]
RewriteRule ^vendor(/.*|)$ - [NC,F]

<FilesMatch "^\.">
    Order allow,deny
    Deny from all
</FilesMatch>

<FilesMatch "\.(ini|sql)$">
  Order allow,deny
  Deny from all
</FilesMatch>
```

> This blocks access to .ini and .sql files, and the sql/, src/ and vendor/ directories

## Database configuration

In order for the website to work, you have to configure a database.
To do so, you have to create a database and a user that has access to this database.

> This step depends on your database server and administration tool(s). Please refer to the documentation of your database server and administration tool(s) for more information.

Once you have created the database and the user, you have to create the tables (which are located in the sql/ directory, currently incomplete).
Once your tables are created, follow those steps:
- `cp db.config.example.ini db.config.ini`
- Edit the `db.config.ini` file and fill in the database credentials

✅ Congratulations! Your website should now work!

## Any questions?

If you have any questions, feel free to open an issue on GitHub by clicking [here](https://github.com/MaxenceIUT/NetVOD/issues/new/).