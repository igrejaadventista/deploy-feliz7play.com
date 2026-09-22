<?php

declare(strict_types=1);

namespace Metricool\Controllers;

if (!defined('ABSPATH')) {
    exit;
}

use Metricool\Support\Helpers\Event;
use Metricool\Traits\HasViews;
use Metricool\Traits\HasNonces;
use Metricool\Support\Helpers\MetricoolUrl;
use Metricool\Traits\HasAllowlistControl;
use Metricool\Interfaces\ControllerInterface;
use Metricool\Support\Helpers\Storages\RequestStorage;
use Metricool\Support\Helpers\Storages\EnvironmentConfig;

class SharePostController implements ControllerInterface
{
    use HasAllowlistControl;
    use HasViews;
    use HasNonces;

    private const DEFAULT_POST_TYPES = ['post', 'page'];
    private const POST_COLUMN_KEY = 'metricool';
    private const SHARE_POST_ACTION = 'share_post';

    private EnvironmentConfig $env;
    private RequestStorage $request;

    public function __construct(EnvironmentConfig $env, RequestStorage $request)
    {
        $this->env = $env;
        $this->request = $request;
    }

    public function register(): void
    {
        if ($this->userCanManage() === false) {
            return;
        }

        add_action('init', [$this, 'registerSharePostColumn']);
        add_action('admin_init', [$this, 'processShareAction']);
        add_action('add_meta_boxes', [$this, 'addShareMetaBox']);
        add_filter('default_hidden_columns', [$this, 'filterDefaultHiddenColumns'], 10, 2);
    }

    /**
     * Registers the Metricool share post column on all public post types. Due
     * to {@see filterDefaultHiddenColumns} the column will be hidden by default
     * on all post types except the ones defined in {@see DEFAULT_POST_TYPES}.
     */
    public function registerSharePostColumn(): void
    {
        foreach ($this->getAllPublicPostTypes() as $postType) {
            add_filter("manage_{$postType}_posts_columns", [$this, 'insertPostTableColumn']);
            add_action("manage_{$postType}_posts_custom_column", [$this, 'insertPostTableColumnContent'], 10, 2);
        }
    }

    /**
     * Retrieves all public and viewable post types.
     */
    private function getAllPublicPostTypes(): array
    {
        $postTypes = get_post_types(['public' => true]);
        return array_filter($postTypes, 'is_post_type_viewable');
    }

    /**
     * Method is used for hiding the custom column by default on all post type
     * list table screens except the configured default post types. Users can
     * still enable the column manually through Screen Options.
     */
    public function filterDefaultHiddenColumns(array $hiddenColumns, \WP_Screen $currentScreen): array
    {
        // Only act on post type list tables like edit-post, edit-page, etc.
        if (strpos($currentScreen->id, 'edit-') !== 0) {
            return $hiddenColumns;
        }

        if (empty($currentScreen->post_type)) {
            return $hiddenColumns;
        }

        // Keep column visible if post type is in the default list.
        if (in_array($currentScreen->post_type, self::DEFAULT_POST_TYPES, true)) {
            return $hiddenColumns;
        }

        // Hide the POST_COLUMN_KEY column by default if not already hidden.
        if (!in_array(self::POST_COLUMN_KEY, $hiddenColumns, true)) {
            $hiddenColumns[] = self::POST_COLUMN_KEY;
        }

        return $hiddenColumns;
    }

    /**
     * Handles known actions from the {@see Storage} based on the
     * metricool_action key.
     */
    public function processShareAction(): void
    {
        $pageIsDashboardPage = ($this->request->getString('global.page') === 'metricool');
        $actionIsShareAction = ($this->request->getString('global.metricool_action') === self::SHARE_POST_ACTION);

        if (!$pageIsDashboardPage || !$actionIsShareAction) {
            return; // abort
        }

        // Validate nonce or wp_die on empty
        $nonce = $this->request->getString('global._metricool_action_nonce');
        if (empty($nonce) || !$this->verifyNonce($nonce, 'metricool_action')) {
            wp_die(esc_html__('Invalid nonce.', 'metricool'));
        }

        $this->handleSharePostAction();
    }

    /**
     * Sets the metricool column header to the post tables
     */
    public function insertPostTableColumn(array $columns): array
    {
        $columns[self::POST_COLUMN_KEY] = $this->view('admin/share-post/column-header', [
            'label' => __('Metricool', 'metricool')
        ]);
        return $columns;
    }

    /**
     * Sets the content of the metricool share post column
     */
    public function insertPostTableColumnContent(string $columnName, int $postId): void
    {
        if (($columnName !== self::POST_COLUMN_KEY) || (get_post_status($postId) !== 'publish')) {
            return;
        }

        $this->render('admin/share-post/button', $this->getArgumentsToSharePost($postId));
    }

    /**
     * Redirects to the Metricool create post screen with the content and media
     */
    protected function handleSharePostAction(): void
    {
        $postId = $this->request->getInt('global.metricool_post_id');
        $postExists = (get_post_status($postId) !== false);

        if ($postExists === false) {
            return; // abort
        }

        $content = get_the_title($postId) . ' - ' . get_permalink($postId);
        $mediaUrl = get_the_post_thumbnail_url($postId, 'large');
        $metricoolCreatePostUrl = MetricoolUrl::createPostUrl($content, $mediaUrl);

        // In Metricool the user can "schedule" the post they share from WP.
        // That's where the naming mismatch comes from. Share <-> Schedule
        Event::dispatch(Event::POST_SCHEDULED);

        // Redirect to the deeplink
        header('Location: ' . $metricoolCreatePostUrl);
        exit();
    }

    /**
     * Adds the Metricool meta box to the post edit screen. Works for both
     * Gutenberg and classic editor. Also adds the meta box for public
     * post-types for which the column is not shown by default.
     */
    public function addShareMetaBox(): void
    {
        foreach ($this->getAllPublicPostTypes() as $postType) {
            add_meta_box(
                'metricool-share-post',
                __('Metricool', 'metricool'),
                [$this, 'renderShareMetaBox'],
                $postType,
                'side',
            );
        }
    }

    /**
     * Renders the content of the Metricool meta box on the post edit screen.
     */
    public function renderShareMetaBox(\WP_Post $post): void
    {
        $arguments = [
            'published' => (strtolower(get_post_status($post->ID)) === 'publish'),
            'unpublishedText' => __('Sharing is only possible for published posts.', 'metricool'),
        ];

        $this->render('admin/share-post/meta-box', $this->getArgumentsToSharePost($post->ID, $arguments));
    }

    /**
     * Prepares the arguments needed to render the share post button used in:
     * {@see insertPostTableColumnContent} and the meta box via:
     * {@see renderShareMetaBox}
     *
     * @param ?array $arguments Additional args to merge, overrides defaults
     */
    private function getArgumentsToSharePost(int $postID, ?array $arguments = []): array
    {
        $actionableUrl = add_query_arg([
            'metricool_action' => self::SHARE_POST_ACTION,
            'metricool_post_id' => $postID,
            '_metricool_action_nonce' => wp_create_nonce('metricool_action'),
        ], $this->env->getUrl('plugin.dashboard_url'));

        $defaultArguments = [
            'actionableUrl' => $actionableUrl,
            'label' => __('Share', 'metricool')
        ];

        // Merge passed arguments with priority over default arguments
        return array_merge($defaultArguments, $arguments);
    }
}
