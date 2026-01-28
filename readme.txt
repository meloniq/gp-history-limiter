=== GlotCore History Limiter ===
Contributors: meloniq
Tags: glotpress, history, limit, revisions
Tested up to: 6.9
Stable tag: 1.0
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a limit to the number of history entries stored in GlotPress.

== Description ==

Adds a limit to the number of history entries stored in [GlotPress](https://wordpress.org/plugins/glotpress/).
It helps to prevent growing database size by limiting the number of revisions kept for each string.
For larger projects, consider using the paid GlotCore service instead.


= Configuration =

Once you have installed GlotCore History Limiter it is ready to use, and the initial limit is set to 1 history entry per string.
You can change this limit by defining the following constant in your `wp-config.php` file:
```php
define( 'GLOTCORE_HISTORY_LIMIT', 5 ); // Change 5 to your desired limit
```


== Changelog ==

= 1.0 =
* Initial release.
