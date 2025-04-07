<div class="wrap">
    <h1>Deepl</h1>

    <div class="nav-tab-wrapper">
        <a href="#config" class="nav-tab nav-tab-active">Configuração</a>
    </div>

    <div id="tabs">
        <div id="config" class="tabs-panel is-active">
            <?php
            if (self::$deepl_client) {
                try {
                    $usage = self::$deepl_client->getUsage();

                    if ($usage->anyLimitReached()) {
                        echo '
                        <div class="notice notice-error is-dismissible">
                            <p>Translation limit exceeded.</p>
                        </div>
                        ';

                    }
                } catch (\Exception $e) {
                    echo '
                    <div class="notice notice-error is-dismissible">
                        <p>Deep error: ' . $e->getMessage() . '</p>
                    </div>
                    ';
                }
            }
            ?>
            <form method="POST">
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for="deepl_auth_key">Caracteres traduzidos</label>
                            </th>
                            <td>
                                <?php
                                if (isset($usage->character)) {
                                    echo $usage->character->count . ' de ' . $usage->character->limit;
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="deepl_auth_key">AuthKey</label>
                            </th>
                            <td>
                                <input type="text" id="deepl_auth_key" name="deepl_auth_key" class="regular-text" value="<?php echo esc_attr(get_option('deepl_auth_key')); ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                Tradução automática
                            </th>
                            <td>
                                <input name="deepl_auto_translate" type="checkbox" id="deepl_auto_translate" <?php echo get_option('deepl_auto_translate') ? 'checked' : ''; ?>>
                                <label for="deepl_auto_translate">
                                    Permitir tradução automática do conteúdo dos vídeos e termos das taxonomias quando criados ou atualizados
                                </label>
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
