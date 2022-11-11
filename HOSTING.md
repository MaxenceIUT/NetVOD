# Hosting your own instance of NetVOD

## Introduction

NetVOD is a free and open source project. You can host your own instance of NetVOD on your own server. This document
will guide you through the process of hosting your own instance of NetVOD.

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
