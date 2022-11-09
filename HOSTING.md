# Hosting your own instance of NetVOD

## Introduction

NetVOD is a free and open source project. You can host your own instance of NetVOD on your own server. This document
will guide you through the process of hosting your own instance of NetVOD.

## Web server configuration

### Apache

You can use the following .htaccess file to configure your Apache web server:

```apacheconf
Options -Indexes

Order Deny,Allow
Deny from all


<FilesMatch "^((index)\.php)?$">  # Basically, it accepts the three PHP files and the empty string.
    Allow from all
</FilesMatch>
```