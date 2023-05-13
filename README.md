# Bitrix module up.TutorToday

Clone repository to `${doc_root}/local/modules/up.tutortoday`

Install module using admin panel

Set `tutortoday template` as your primary site template

## Setup modern Bitrix routing

Add `routes.php` in `routing` section of `${doc_root}/bitrix/.settings.php` file:

```php
'routing' => ['value' => [
	'config' => ['routes.php']
]],
```

Put following content into your `${doc_root}/index.php` file:

```php
<?php
require_once __DIR__ . '/bitrix/routing_index.php';
```

Replace following lines in your `${doc_root}/.htaccess` file:

```
-RewriteCond %{REQUEST_FILENAME} !/bitrix/urlrewrite.php$
-RewriteRule ^(.*)$ /bitrix/urlrewrite.php [L]

+RewriteCond %{REQUEST_FILENAME} !/index.php$
+RewriteRule ^(.*)$ /index.php [L]
```

Add the following strings in `local/php_interface/init.php`

```
\Bitrix\Main\Loader::includeModule('up.tutortoday');
```

## Symlinks for handy development

You probably want to make following symlinks:

```
local/components/up -> local/modules/up.tutortoday/install/components/up
local/templates/tutortoday -> local/modules/up.tutortoday/install/templates/tutortoday
local/routes/routes.php -> local/modules/up.tutortoday/install/routes/routes.php
local/view/ -> local/modules/up.tutortoday/install/view
local/js/ -> local/modules/up.tutortoday/install/js/up/
```
