# Bitrix module up.TutorToday

Clone repository to `${doc_root}/local/modules`\
Move directory `up.TutorToday` from `${doc_root}/local/modules/team2` to `${doc_root}/local/modules`\
Delete directory `team2`

Install module using admin panel

Set `Main template` as your primary site template

## Setup modern Bitrix routing

Add `web.php` in `routing` section of `${doc_root}/bitrix/.settings.php` file:

```php
'routing' => ['value' => [
	'config' => ['web.php']
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

## Symlinks for handy development

You probably want to make following symlinks:

```
local/components/up -> local/modules/up.TutorToday/install/components/up
local/templates/TutorToday -> local/modules/up.TutorToday/install/templates/TutorToday
local/routes/web.php -> local/modules/up.TutorToday/install/routes/web.php
local/view/ -> local/modules/up.TutorToday/install/view
```

## Examples of TutorToday
To set up demonstrative content, run `install_data_example.sql` in `up.TutorToday/install/db/`
