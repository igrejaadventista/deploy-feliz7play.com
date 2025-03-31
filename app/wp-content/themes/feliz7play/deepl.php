<div class="wrap">
    <h1>Deepl</h1>

    <div class="nav-tab-wrapper">
        <a href="#config" class="nav-tab nav-tab-active">Configuração</a>
    </div>

    <div id="tabs">
        <div id="config" class="tabs-panel is-active">
            <form method="POST">
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for="deepl_auth_key">AuthKey</label>
                            </th>
                            <td>
                                <input type="text" id="deepl_auth_key" name="deepl_auth_key" class="regular-text" value="<?php echo esc_attr(get_option('deepl_auth_key')); ?>" required>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p class="submit">
                    <input class="button button-primary" type="submit" value="Salvar configurações">
                </p>
            </form>
        </div>
    </div>
</div>
