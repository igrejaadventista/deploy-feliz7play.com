<div class="wrap">
    <h1>Algolia</h1>

    <div class="nav-tab-wrapper">
        <a href="#config" class="nav-tab nav-tab-active">Configuração</a>
        <a href="#index-data" class="nav-tab">Indexar dados</a>
    </div>

    <div id="tabs">
        <div id="config" class="tabs-panel is-active">
            <form method="POST">
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for="algolia_index">Index:</label>
                            </th>
                            <td>
                                <input type="text" id="algolia_index" name="algolia_index" class="regular-text" value="<?php echo get_option('algolia_index'); ?>">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="algolia_app_id">Application ID:</label>
                            </th>
                            <td>
                                <input type="text" id="algolia_app_id" name="algolia_app_id" class="regular-text" value="<?php echo get_option('algolia_app_id'); ?>">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="algolia_api_key_search">API Key - Search:</label>
                            </th>
                            <td>
                                <input type="text" id="algolia_api_key_search" name="algolia_api_key_search" class="regular-text" value="<?php echo get_option('algolia_api_key_search'); ?>">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="algolia_api_key_write">API Key - Write:</label>
                            </th>
                            <td>
                                <input type="text" id="algolia_api_key_write" name="algolia_api_key_write" class="regular-text" value="<?php echo get_option('algolia_api_key_write'); ?>">
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p class="submit">
                    <input class="button button-primary" type="submit" value="Salvar configurações">
                </p>
            </form>
        </div>
        <div id="index-data" class="tabs-panel" style="display:none;">
            <div class="wrap">
                <button class="button button-primary button__indexData">Indexar dados</button>
            </div>
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function($) {
        $('.nav-tab').click(function(event) {
            event.preventDefault();
            $('.nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
            $('.tabs-panel').removeClass('is-active').hide();
            $($(this).attr('href')).addClass('is-active').show();
        });

        $('.button__indexData').click(function(event) {
            var button = $(event.target).get(0);
            var buttonText = button.innerHTML;
            button.innerHTML = 'Indexando...';
            button.disabled = true;
            $(button).after('<img class="loader" src="<?php echo esc_url(get_admin_url() . 'images/loading.gif'); ?>" />');

            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: {
                    action: 'index_data'
                },
                success: function(response) {
                    button.innerHTML = buttonText;
                    button.disabled = false;
                    $('.loader').remove();
                    alert('Dados indexados com sucesso!');
                }
            });
        });
    });
</script>

<style>
    #index-data .wrap {
        display: flex;
        align-items: center;
    }

    .button__indexData {
        margin-right: 0.5rem !important;
    }
</style>
