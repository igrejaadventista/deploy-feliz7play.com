=== WP Headless CMS Framework ===
Contributors: ben.moody
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html
Tags: WordPress as a headless cms, headless cms, headless, cms, rest api, rest, react, react native, angular
Requires at least: 5.0.0
Tested up to: 5.4
Stable tag: 1.15

== Description ==

Plugin provides a framework sets up WordPress as a headless CMS.

Framework options include:

* JSON Web Token Authentication (optional)
* Adds Gutenberg block data to REST responses (optional)
* Protects REST requests with nonce (optional)
* Cleans up REST response objects (optional)
* Removes non required REST endpoints (optional)
* Disables the frontend (optional)

All modules can be enabled/disabled or configured to suit most if not all WordPress as a headless CMS use cases.

Developing a single page React app but still want to use the WordPress theme based fronted.

Not a problem, maybe you just use these modules:

* Adds Gutenberg block data to REST responses
* Protects REST requests with nonce
* Cleans up REST response objects
* Removes non required REST endpoints


Developing a truly headless WordPress single page app or mobile app and just want WordPress for the backend?

Maybe use these modules:

* JSON Web Token Authentication
* Adds Gutenberg block data to REST responses
* Protects REST requests with nonce
* Cleans up REST response objects
* Removes non required REST endpoints
* Disable the WordPress frontend

You get the idea.

The plugin documentation is held inside the plugin ‘options’ page in the WordPress admin, although there are some docs for the custom rest endpoints:

[Learn More about plugin endpoint requests][pluginendpoint learnmore]

[pluginendpoint learnmore]: https://documenter.getpostman.com/view/1175138/RztkPpV4#fc567f81-ff31-442e-a236-fa247bd1fd6c


Anyway if you are looking to use WordPress as a headless CMS on a React, React Native, Angular, iOS/Android native project. This plugin should go a long way to setting WordPress up as you need it.

== Installation ==

1. Upload plugin folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to WP REST Headless admin menu for the framework documentation

== Frequently Asked Questions ==

The plugin is configured using WordPress filters. What!! why no admin options page you ask?

Well the whole point of a REST API is speed right!? Fetching tons of options from the database slows things down.

Anyway, we’re developers we can handle 5 min of configuration.

The plugin documentation is held inside the plugin ‘options’ page in the WordPress admin, although there are some docs for the custom rest endpoints:

[Learn More about plugin endpoint requests][pluginendpoint learnmore]

[pluginendpoint learnmore]: https://documenter.getpostman.com/view/1175138/RztkPpV4#fc567f81-ff31-442e-a236-fa247bd1fd6c

== Changelog ==

= 1.0 =

Inital commit to plugin repo

= 1.1 =

Made rest cleanup more specific
Improved cleanup detection code - credit 'Daniele Scasciafratte' https://wordpress.org/support/users/mte90/
Added ability for JWT get-token requests to bypass nonce check if enabled
Updated docs

= 1.15 =
Confirmed WP 5.4 compatibility

== Upgrade Notice ==

= 1.0 =
This is the first version of plugin.

= 1.1 =
Improved REST cleanup code - credit 'Daniele Scasciafratte' https://wordpress.org/support/users/mte90/
Added ability for JWT get-token requests to bypass nonce check if enabled
Updated docs

= 1.15 =
Confirmed WP 5.4 compatibility
