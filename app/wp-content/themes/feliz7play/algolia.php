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
                                <input type="text" id="algolia_index" name="algolia_index" class="regular-text" value="<?php echo get_option('algolia_index'); ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="algolia_app_id">Application ID:</label>
                            </th>
                            <td>
                                <input type="text" id="algolia_app_id" name="algolia_app_id" class="regular-text" value="<?php echo get_option('algolia_app_id'); ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="algolia_api_key_search">API Key - Search:</label>
                            </th>
                            <td>
                                <input type="text" id="algolia_api_key_search" name="algolia_api_key_search" class="regular-text" value="<?php echo get_option('algolia_api_key_search'); ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="algolia_api_key_write">API Key - Write:</label>
                            </th>
                            <td>
                                <input type="text" id="algolia_api_key_write" name="algolia_api_key_write" class="regular-text" value="<?php echo get_option('algolia_api_key_write'); ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="algolia_api_batch">Lote para indexação:</label>
                            </th>
                            <td>
                                <input type="number" id="algolia_api_batch" name="algolia_api_batch" class="regular-text" value="<?php echo get_option('algolia_api_batch'); ?>" required>
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
            <div class="wrap" id="results"><div class="inner"></div></div>
        </div>
    </div>
</div>

<script>
(function($) {
    var url = new URL(location);

    $('.nav-tab').click(function(event) {
        event.preventDefault();

        $('.nav-tab').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        $('.tabs-panel').removeClass('is-active').hide();
        $($(this).attr('href')).addClass('is-active').show();

        url.searchParams.set('tab', $(this).attr('href').replace('#', ''));
        history.pushState({}, '', url);
    });

    if (url.searchParams.get('tab')) {
        $('.nav-tab[href="#' + url.searchParams.get('tab') + '"]').click();
    }

    $('.button__indexData').click(function(event) {
        var button = $(event.target).get(0);
        var buttonText = button.innerHTML;
        button.innerHTML = 'Indexando...';
        button.disabled = true;
        $(button).after('<img class="loader" src="<?php echo esc_url(get_admin_url() . 'images/loading.gif'); ?>" />');

        $('#results .inner').empty();

        var ajaxUrl = '<?php echo admin_url('admin-ajax.php'); ?>';

        $.post(ajaxUrl, {
            action: 'get_data_to_index'
        })
        .done(function (items) {
            items.forEach(item => {
                $.post(ajaxUrl, {
                    action: 'index_data',
                    item: item
                })
                .done(function (response) {
                    var {title, type, language, edit_link, message} = response;

                    $('#results .inner').append(`
                        <a href="${edit_link}" target="_blank">
                            ${title} - ${type} - ${language.toUpperCase()} - ${message ? message : 'OK'}
                        </a>
                    `);

                    if (items[items.length-1] === item){
                        button.innerHTML = buttonText;
                        button.disabled = false;
                        $('.loader').remove();
                        alert('Dados indexados com sucesso!');
                    }
                })
            });
        });
    });
})(jQuery);
</script>

<style>
#index-data .wrap:first-child {
    display: flex;
    align-items: center;
}

.button__indexData {
    margin-right: 0.5rem !important;
}

#results {
    padding: 14px;
    line-height: 2;
    border-radius: 5px;
    background-color: #fff;
}

.inner {
    overflow: auto;
    max-height: 60vh;
}

.inner a {
    display: block;
}

.inner::-webkit-scrollbar {
    width: 10px;
}

.inner::-webkit-scrollbar-track {
    border-radius: 5px;
    background-color: #e5e5e5;
}

.inner::-webkit-scrollbar-thumb {
    background-color: #2271b1;
    border-radius: 5px;
}

.inner::-webkit-scrollbar-thumb:hover {
    background-color: #135e96;
}
</style>
