<?php

declare(strict_types=1);

namespace Metricool\Controllers;

if (!defined('ABSPATH')) {
    exit;
}

use Metricool\Support\Helpers\Storages\LanguagesConfig;
use Metricool\Traits\HasViews;
use Metricool\Traits\HasUserAccess;
use Metricool\Services\DashboardService;
use Metricool\Traits\HasAllowlistControl;
use Metricool\Http\Metricool\MetricoolApi;
use Metricool\Interfaces\ControllerInterface;
use Metricool\Services\MetricoolAccountService;
use Metricool\Support\Helpers\Storages\EnvironmentConfig;

class DashboardController implements ControllerInterface
{
    use HasViews;
    use HasUserAccess;
    use HasAllowlistControl;

    private EnvironmentConfig $env;
    private MetricoolApi $metricool;
    private DashboardService $service;
    private MetricoolAccountService $account;
    private LanguagesConfig $languages;

    public function __construct(EnvironmentConfig $env, MetricoolApi $metricool, DashboardService $service, MetricoolAccountService $account, LanguagesConfig $languages)
    {
        $this->env = $env;
        $this->metricool = $metricool;
        $this->service = $service;
        $this->account = $account;
        $this->languages = $languages;
    }

    public function register(): void
    {
        // Show forced login screen when coming from legacy
        add_action('metricool_plugin_legacy_upgrade', [$this->service, 'enableForcedLogin']);
        add_action('metricool_onboarding_completed', [$this->service, 'disableForcedLogin']);

        // Redirect on the activation hook, but do it after anything else.
        add_action('metricool_activation', [$this, 'maybeRedirectToDashboard'], 9999);

        add_action('admin_menu', [$this, 'addDashboardPage']);
    }

    /**
     * Redirect to metricool dashboard page on activation, but only if the user
     * manually activated the plugin via the plugins overview. React will handle
     * redirect to onboarding if needed.
     *
     * @param string $pageSource The page where the activation was triggered,
     * usually 'plugins.php' but can be other pages as well.
     */
    public function maybeRedirectToDashboard(string $pageSource = ''): void
    {
        if ($pageSource !== 'plugins.php') {
            return;
        }

        wp_safe_redirect($this->env->getUrl('plugin.dashboard_url'));
        exit;
    }

    /**
     * Add the dashboard page to the admin menu of WordPress. Also triggers the
     * action to enqueue scripts and styles
     * @uses apply_filters metricool_menu_position
     */
    public function addDashboardPage(): void
    {
        /**
         * Filter: metricool_menu_position
         * Can be used to change the position of the menu item in the admin menu.
         * @param int $menuPosition
         * @return int Default 59 to be positioned after "wp-menu-separator" and
         * before "Appearance".
         */
        $menuPosition = apply_filters('metricool_menu_position', 59);

        $pageHookSuffix = add_menu_page(
            esc_html__('Metricool', 'metricool'),
            esc_html__('Metricool', 'metricool'),
            'metricool_manage',
            'metricool',
            [$this, 'renderReactApp'],
            $this->getMenuItemIconSVG(),
            $menuPosition,
        );

        add_action("admin_print_scripts-$pageHookSuffix", [$this, 'enqueueReactScripts']);
    }

    /**
     * Render the React app in the WordPress admin
     */
    public function renderReactApp(): void
    {
        $this->render('admin/dashboard', [], 'html');
    }

    /**
     * Returns the SVG icon for the menu item in the admin menu.
     * @return string Base64 encoded SVG image
     */
    public function getMenuItemIconSVG(): string
    {
        $svg = '<svg width="20" height="20" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M44 0C47.3137 1.28851e-07 50 2.68629 50 6V44C50 47.3137 47.3137 50 44 50H6C2.68629 50 0 47.3137 0 44V6C1.28855e-07 2.68629 2.68629 0 6 0H44ZM34.7744 17.001C32.8644 16.9787 30.9998 17.5888 29.4648 18.7383L23.3008 23.0029C22.4171 19.344 19.1215 17.001 15.3154 17.001C10.7691 17.001 7 20.2068 7 24.9854C7.00016 29.7938 10.769 33 15.3154 33C17.2729 33.0189 19.1774 32.3565 20.71 31.125L26.7002 26.9805C27.5862 30.6571 30.8925 33 34.7744 33C39.2309 32.9999 42.9998 29.7937 43 24.9854C43 20.2068 39.2314 17.001 34.7744 17.001ZM15.3154 21.0527C17.1699 21.0528 18.9346 22.6264 18.9346 24.9854C18.9344 27.3743 17.1698 28.9169 15.3154 28.917C13.3715 28.917 11.6067 27.3742 11.6064 24.9854C11.6064 22.6263 13.3714 21.0527 15.3154 21.0527ZM34.7744 21.0527C36.629 21.0528 38.3936 22.6264 38.3936 24.9854C38.3933 27.3743 36.6289 28.9169 34.7744 28.917C32.8305 28.917 31.0656 27.3742 31.0654 24.9854C31.0654 22.6263 32.8303 21.0527 34.7744 21.0527Z" fill="#a7aaad"/>
        </svg>';

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /**
     * Enqueue the Tailwind CSS for the dashboard in the header
     */
    public function enqueueDashboardStyles(): void
    {
        $chunkTranslation = $this->getReactChunkTranslations();
        if (empty($chunkTranslation)) {
            return;
        }

        wp_enqueue_style(
            'metricool-tailwind',
            $this->env->getUrl('plugin.assets_url') . '/css/tailwind.generated.css', // todo - feel free to place this in a different location
            [],
            ($chunkTranslation['version'] ?? '')
        );
    }

    /**
     * Enqueue the React scripts and styles for the dashboard:
     * All files to enqueue are listed in manifest.json, generated by Vite,
     * and available in react/build/assets
     *
     * Load translations for the React app
     */
    public function enqueueReactScripts(): void
    {
        $manifest = $this->env->getString('plugin.react_path') . '/build/.vite/manifest.json';
        $json_translations = [];
        if (file_exists($manifest)) {
            $manifest_contents = file_get_contents($manifest);
            $decoded_manifest = json_decode($manifest_contents, true);
            foreach ($decoded_manifest as $key => $value) {
                if (substr($value['file'], -3) === '.js') {
                    wp_register_script(
                        $key,
                        $this->env->getUrl('plugin.react_url') . '/build/' . $value['file'],
                        [],
                        null,
                        false,
                    );
                    $translation_data = load_script_textdomain($key, 'metricool');
                    if (!empty($translation_data)) {
                        $json_translations[] = $translation_data;
                    }

                    if (!empty($value['isEntry']) && $value['isEntry'] === true) {
                        wp_enqueue_script(
                            'metricool-main-script',
                            $this->env->getUrl('plugin.react_url') . '/build/' . $value['file'],
                            [],
                            null,
                            false,
                        );
                    }
                }
                if (!empty($value['css'])) {
                    wp_enqueue_style(
                        'metricool-tailwind',
                        $this->env->getUrl('plugin.react_url') . '/build/' . $value['css'][0],
                        [],
                        null,
                    );
                }
            }
        }

        add_filter('script_loader_tag', [$this, 'loadMainScriptsAsModule'], 10, 2);

        wp_localize_script(
            'metricool-main-script',
            'metricool',
            ['values' => $this->localizedReactSettings(['json_translations' => $json_translations])],
        );
    }

    /**
     * WordPress doesn't allow for translation of chunks resulting of code
     * splitting. Several workarounds have popped up in JetPack and Woocommerce.
     * Below is mainly based on the Woocommerce solution, which seems to be the
     * simplest approach. Simplicity is king here.
     * @see https://wordpress.com/blog/2022/01/06/wordpress-plugin-i18n-webpack-and-composer/
     */
    private function getReactChunkTranslations(): array
    {
        $cacheName = 'metricool-react-chunk-translations';
        if ($cache = wp_cache_get($cacheName, 'metricool')) {
            return $cache;
        }

        // get all files from the settings/build folder
        $buildDirPath = $this->env->getString('plugin.react_path') . '/build';
        $filenames = scandir($buildDirPath);

        $jsFileName = '';
        $assetFilename = '';
        $jsonTranslations = [];

        // filter the filenames to get the JavaScript and asset filenames
        foreach ($filenames as $filename) {
            if (strpos($filename, 'index.') === 0) {
                if (substr($filename, -3) === '.js') {
                    $jsFileName = $filename;
                } elseif (substr($filename, -10) === '.asset.php') {
                    $assetFilename = $filename;
                }
            }

            if (strpos($filename, '.js') === false) {
                continue;
            }

            // remove extension from $filename
            $chunkHandle = str_replace('.js', '', $filename);
            // temporarily register the script, so we can get a translations object.
            $chunkSource = $this->env->getUrl('plugin.react_url') . '/build/' . $filename;
            wp_register_script($chunkHandle, $chunkSource, [], $this->env->getString('plugin.version'), true);

            //as there is no pro version of this plugin, no need to declare a path
            $localeData = load_script_textdomain($chunkHandle, 'metricool');
            if (!empty($localeData)) {
                $jsonTranslations[] = $localeData;
            }

            wp_deregister_script($chunkHandle);
        }

        if (empty($jsFileName)) {
            return [];
        }

        $assetFileData = require $buildDirPath . '/' . $assetFilename;
        $chunkTranslations = [
            'json_translations' => $jsonTranslations,
            'js_file_name' => $jsFileName,
            'dependencies' => $assetFileData['dependencies'] ?? [],
            'version' => $assetFileData['version'] ?? '',
        ];

        wp_cache_set($cacheName, $chunkTranslations, 'metricool');
        return $chunkTranslations;
    }

    /**
     * Build the localization array for the React script with the translations
     * @uses apply_filters metricool_localize_dashboard_script
     */
    private function localizedReactSettings(array $chunkTranslation): array
    {
        $settings = [
            'nonce' => wp_create_nonce('metricool_nonce'),
            'x_wp_nonce' => wp_create_nonce('wp_rest'),
            'ajax_url' => admin_url('admin-ajax.php'),
            'rest_url' => get_rest_url(),
            'rest_namespace' => $this->env->getString('http.namespace'),
            'rest_version' => $this->env->getString('http.version'),
            'api_url' => trailingslashit(
                get_rest_url(null, $this->env->getString('http.namespace') . '/' . $this->env->getString('http.version'))
            ),
            'dashboard_url' => $this->env->getString('plugin.dashboard_url'),
            'site_url' => site_url(),
            'assets_url' => $this->env->getUrl('plugin.assets_url'),
            'json_translations' => ($chunkTranslation['json_translations'] ?? []),
            'trusted_urls' => $this->env->get('frontend.trusted_urls'),
            'onboarding' => [
                'state' => $this->service->state(),
                'mode' => $this->service->mode(),
            ],
            'support' => $this->env->getUrl('metricool.support'),
            'metricool_base_url' => $this->env->getUrl('metricool.base_url'),
            'metricool_help_url' => $this->env->getUrl('metricool.help_url'),
            'locale' => str_replace("_", "-", get_user_locale()),
            'supported_languages' => $this->languages->all(),
            'google_recaptcha_url' => $this->getGoogleRecaptchaUrl(),
            'google_recaptcha_key' => $this->env->getString('metricool.google_recaptcha_key'),
        ];

        if ($this->metricool->hasAuthentication()) {
            $settings['account'] = $this->account->fetch();
        }

        return apply_filters('metricool_localize_dashboard_script', $settings);
    }

    /**
     * Get the Google reCAPTCHA URL with the key from the environment config.
     */
    private function getGoogleRecaptchaUrl(): string
    {
        return 'https://www.google.com/recaptcha/enterprise.js?render=' . $this->env->getString('metricool.google_recaptcha_key');
    }

    public function loadMainScriptsAsModule(string $tag, string $handle): string
    {
        if (str_contains($handle, 'metricool-main-script') === false) {
            return $tag;
        }

        return str_replace('<script ', '<script type="module" ', $tag);
    }
}
