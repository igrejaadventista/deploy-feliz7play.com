<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Variables that should be passed to the view
 * @var string $script_url
 * @var string $hash
 */
?>
<script>function loadScript(a){var b=document.getElementsByTagName("head")[0],c=document.createElement("script");c.type="text/javascript",c.src="<?php echo esc_attr($script_url) ?>",c.onreadystatechange=a,c.onload=a,b.appendChild(c)}loadScript(function(){beTracker.t({hash:"<?php echo esc_html($hash) ?>"})});</script>