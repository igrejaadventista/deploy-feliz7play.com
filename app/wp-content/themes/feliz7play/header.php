<?php $lang = get_locale(); ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> >
    <head>    
        <title><?php wp_title('-', true, 'right'); bloginfo() ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
        
        <?php wp_head(); ?>
    </head>
    <body <?php body_class($lang); ?>>





