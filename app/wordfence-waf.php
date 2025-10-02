<?php
// Before removing this file, please verify the PHP ini setting `auto_prepend_file` does not point to this.

// This file was the current value of auto_prepend_file during the Wordfence WAF installation (Fri, 15 Aug 2025 21:06:59 +0000)
if (file_exists('/var/www/v3.feliz7play.com/htdocs/wordfence-waf.php')) {
	include_once '/var/www/v3.feliz7play.com/htdocs/wordfence-waf.php';
}
if (file_exists(__DIR__.'/wp-content/plugins/wordfence/waf/bootstrap.php')) {
	define("WFWAF_LOG_PATH", __DIR__.'/wp-content/wflogs/');
	include_once __DIR__.'/wp-content/plugins/wordfence/waf/bootstrap.php';
}