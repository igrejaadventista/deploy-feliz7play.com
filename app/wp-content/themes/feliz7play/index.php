<?php get_template_part('head') ?>
<?php 
    get_header(); 
?>

<main class="<?= isset($_GET['content']) ? 'content' : 'full' ?>">
    <div class="container-fluid">
        <?= the_content() ?>
    </div>
</main>

<?php 
    get_footer(); 
?>