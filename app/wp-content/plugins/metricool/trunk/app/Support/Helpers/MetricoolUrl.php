<?php

declare(strict_types=1);

namespace Metricool\Support\Helpers;

use Metricool\Bootstrap\App;
use Metricool\Support\Helpers\Storages\EnvironmentConfig;

class MetricoolUrl
{
    /**
     * Add required query args to a Metricool admin url
     * @param string $url External url to a Metricool admin page
     * @return string The url with the required query args
     */
    public static function adminUrl(string $url): string
    {
        $queryArgs = array_filter([
            'blogId' => get_option('metricool_blog_id', ''),
            'userId' => get_option('metricool_user_id', ''),
        ]);

        return add_query_arg($queryArgs, $url);
    }


    /**
     * Returns the Metricool create post URL for the given content and media
     * This is a deeplink to the create-post screen of the Metricool web app
     * @param string|false|null $mediaUrl Optional media URL to be included
     *                                    in the post
     */
    public static function createPostUrl(string $content, $mediaUrl = null): string
    {
        $queryArgs = [
            'blogId' => get_option('metricool_blog_id', ''),
            'userId' => get_option('metricool_user_id', ''),
            'post.content' => $content,
        ];

        if ($mediaUrl) {
            $queryArgs['post.media'] = $mediaUrl;
        }

        $env = App::getInstance()->get(EnvironmentConfig::class);
        return add_query_arg(array_filter($queryArgs), $env->getUrl('metricool.create_post_url'));
    }
}
