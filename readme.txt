=== GlotCore History Limiter ===
Contributors: meloniq
Tags: glotpress, history, limit, revisions
Tested up to: 6.9
Stable tag: 1.0
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Limits and manages translation history in GlotPress by controlling the number of waiting and old entries per string to prevent database bloat.

== Description ==

Controls and optimizes translation history in [GlotPress](https://wordpress.org/plugins/glotpress/) by enforcing limits on `waiting` and `old` entries per string,
reducing unnecessary revisions and keeping the database lean and efficient.

1. It limits the number of `waiting` translations.
Any `waiting` translations above the limit are set to `old` status and are no longer shown in the waiting translations list.

2. It limits the number of `old` translations.
Any `old` translations above the limit are permanently deleted from the database.


### How the limit works

Assume the limit is set to **2 translations per string**.

---

### 1. Single user behavior

When a single user submits multiple translations:

- The **latest translation** is always marked as `waiting`
- The previous `waiting` translation becomes `old`
- If the number of `old` translations exceeds the limit:
  - The **oldest `old` translation is deleted**

**Result:**
- `1 waiting` translation (latest)
- Up to `2 old` translations (limited)

---

### 2. Multiple users

When multiple users submit translations for the same string:

- Each user can have **one `waiting` translation**
- A new submission:
  - Replaces that user’s previous `waiting` (which becomes `old`)
  - Does **affect other users `waiting` translations** if the total number of `waiting` translations exceeds the limit
  - The **oldest `waiting` translation** over the limit (regardless of user) becomes `old`

- All `old` translations are shared (counted globally per string)
- All `waiting` translations are shared (counted globally per string)


= Configuration =

Once installed, GlotCore History Limiter is ready to use. The default limit is set to 3 history entries per string.

You can change this limit by defining the following constant in your `wp-config.php` file:
```php
define( 'GLOTCORE_HISTORY_LIMIT', 3 ); // Change 3 to your desired limit
```

You can also modify the limit by hooking into the `glotcore_history_limit` filter in your theme or plugin:
```php
add_filter( 'glotcore_history_limit', function( $limit ) {
	return 3; // Change 3 to your desired limit
} );
```


== Changelog ==

= 1.0 =
* Initial release.
