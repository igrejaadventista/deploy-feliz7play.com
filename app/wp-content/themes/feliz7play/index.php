<?php get_template_part('head') ?>
<?php 
    if(!isset($_GET['content']))
        get_header(); 
?>

<main class="<?= isset($_GET['content']) ? 'content' : 'full' ?>">
    <div class="container-fluid">
        <?= the_content() ?>
    </div>
</main>

<?php 
    if(!isset($_GET['content']))
        get_footer(); 
?>