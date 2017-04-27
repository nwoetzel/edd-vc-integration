=== Easy Digital Downloads Visual Composer Integration ===
Contributors: nwoetzel
Tags: edd, easy digital downloads, vc, visual composer
Requires at least: 4.6
Tested up to: 4.7.2
Stable tag: 1.2.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This pluging integrates shortcodes defined by the easy-digital-downloads plugin as elements into visual composer.

== Description ==

This plugin requires that you have installed:
* [Visual Composer](https://vc.wpbakery.com/) - tested for version 5.0.1
* [Easy digital downloads](https://wordpress.org/plugins/easy-digital-downloads/) - tested for version 2.7.4

The [easy digital downloads shortcodes](https://github.com/nwoetzel/edd-vc-integration/) are mapped as Visual Composer elements.

== Installation ==

Download the latest release from github as zip and install it through wordpress.
Or use [wp-cli](http://wp-cli.org/) with the latest release:
<pre>
wp-cli.phar plugin install https://github.com/nwoetzel/edd-vc-integration/archive/1.2.0.zip --activate
</pre>

Or add them as a composer package in your wordpress' composer.json file:
<pre>
{
        "repositories": [
                {
                        "type":  "vcs",
                        "url":   "https://github.com/nwoetzel/edd-vc-integration.git"
                }

        ],
        "require"     : {
                "nwoetzel/edd-vc-integration":"~1.2"
        }
}
</pre>
Read more about that at http://composer.rarst.net/

== Frequently Asked Questions ==

= The elements icons do not look nice =

There might be some improvement in the future. No high priority.

== Screenshots ==

== Changelog ==

= 1.2.0 =
* added support for composer http://composer.rarst.net/

= 1.1.0 =
* added load_textdomain
* added translation for de_DE

= 1.0.3 =
* fixed issue #2 so that unchecked checkboxes are really saved as empty and are treated as false

= 1.0.2 =
* Improved readme.txt
* corrected EDD_VC_Intergation class name

= 1.0.1 =
* Improved readme.txt

= 1.0.0 =
* Initial release
