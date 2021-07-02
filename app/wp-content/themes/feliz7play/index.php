<?php get_template_part('head') ?>
<?php 
    if(!isset($_GET['content']))
        get_header(); 
?>

<main>
    <?= the_content() ?>
</main>

<?php 
    if(!isset($_GET['content']))
        get_footer(); 
?>