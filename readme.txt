=== Flocpress Builder ===
Contributors: lady
Tags: page builder, flocss, solid, psr-11
Requires at least: 6.0
Tested up to: 6.8
Requires PHP: 8.1
Stable tag: 1.1.0
License: GPLv2 or later

Page builder theo SOLID, PSR-4, PSR-11, FLOCSS.

== Installation ==
1. Upload thu muc flocpress-builder vao /wp-content/plugins/
2. Activate plugin (khong can composer install)
3. Vao Pages -> Edit -> tick "Dung Flocpress Builder"
4. Bam ten element de chen, hoac sua JSON, roi Save

== Changelog ==
= 1.1.0 =
* Gop toan bo thanh mot ban.
* PSR-11 dung interface, them RegistrableContainer, RegistrableInterface.
* Autoload fallback + PSR-11 bundled, khong can composer.
* has() dung spec PSR-11.
* ID node theo vi tri cay, khong trung.
* Bo div rong khi element khong co noi dung.
* Editor tam: chen element + JSON + REST load/save.

= 1.0.1 =
* Tach Plugin thanh service nho, sua quyen REST, nonce metabox.

= 1.0.0 =
* Ban dau.