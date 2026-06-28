<?php

namespace WPNCEasyWP\Providers\Varnish;

use Exception;
use WPNCEasyWP\Traits\AdminMenuableTrait;
use WPNCEasyWP\Support\Cache;

class VarnishCache extends Cache
{
  use AdminMenuableTrait;

  /**
   * Action used to clear all
   *
   * @var string
   */
  protected $action = 'clear_varnish';

  /**
   * List of urls to purge.
   *
   * @var array
   */
  protected $urlsToPurge = [];

  /**
   * We'll purge only the posts with status.
   *
   * @var array
   */
  protected $allowedPostStatus = [
    'publish',
    'private',
    'trash',
  ];

  /**
   * We won't purge the posts with type.
   *
   * @var array
   */
  protected $invalidPostTypes = [
    'nav_menu_item',
    'revision',
  ];

  /**
   * Used to collect the feed link.
   *
   * @var array
   */
  protected $noArchivePostTypes = [
    'post',
    'page',
  ];

  /**
   * Determine the route for the rest API.
   *
   * @note This will need to be revisted if WP updates the version.
   * @todo Consider an array? 4.7-4.7.3 use v2, and then adapt from there?
   *
   * @var string
   */
  protected $restApiRoute = 'wp/v2';

  /**
   * Entry point.
   */
  public function __construct()
  {
    $this->collectFullPurgeActions();
    $this->collectPostIdPurgeActions();

    add_action($this->action, [$this, 'clearAll']);

    add_filter(
      'auto_core_update_email',
      function ($email) {
        $this->clearAll();

        return $email;
      }
    );

    // will purge when wordpress is loaded
    add_action(
      'wpnceasywp_varnish_purge_again',
      function () {
        $this->urlsToPurge = WPNCEasyWP()->options->get('varnish.last_purged_urls', []);
        $this->doPurge();
      }
    );

    $this->enableForClearAll();

    add_action('shutdown', [$this, 'doPurge']);
  }

  /**
   * Store in $this->urlsToPurge array the list of urls used for a full purge action.
   *
   */
  protected function collectFullPurgeActions()
  {
    $actions = $this->getFullPurgeActions();

    foreach ($actions as $a) {
      add_action($a, function () {
        foreach (self::collectMultipleReplicas() as $url) {
          $this->urlsToPurge[] = $url;
        }
      });
    }
  }

  /**
   * Return the list of actions for a full purge action.
   *
   * @return array
   */
  protected function getFullPurgeActions()
  {
    return [
      'switch_theme',                      // After a theme is changed
      'autoptimize_action_cachepurged,',   // Compat with https://wordpress.org/plugins/autoptimize/
      'upgrader_process_complete',
      'automatic_updates_complete',
      'delete_option_update_core',
      $this->action,
    ];
  }

  protected static function collectMultipleReplicas(): array
  {
    $svc = self::getServiceName();
    if ($svc) {
      $ips = gethostbynamel($svc);

      if ($ips) {
        return array_map(function ($ip) {
          return "http://{$ip}";
        }, $ips);
      }
    }

    return [home_url()];
  }

  // ---

  /**
   * Return the service name. We check also for the frontend case
   *
   * @return string
   */
  protected static function getServiceName()
  {
    $frontend_svc = getenv('SERVICE_NAME');

    if ($frontend_svc) {
      // 'wordpress-frontend.easywp.svc.cluster.local'
      return $frontend_svc;
    }

    $podname = getenv('HOSTNAME');

    if ($podname) {
      $regex = '/-([^-]+)-/m';
      $res   = preg_match_all($regex, $podname, $matches, PREG_SET_ORDER, 0);

      if ($res) {
        $id = $matches[0][1];
        return "svc-{$id}.default.svc.cluster.local";
      }
    }

    return null;
  }

  /**
   * Store in $this->urlsToPurge array the list of urls used for a post id purge action.
   *
   */
  protected function collectPostIdPurgeActions()
  {
    $actions = $this->getPostIdPurgeActions();

    foreach ($actions as $a) {
      add_action($a, [$this, 'collectUrlPostId'], 10, 2);
    }
  }

  /**
   * Return the list of actions for a post id purge action.
   *
   * @return array
   */
  protected function getPostIdPurgeActions()
  {
    return [
      'save_post',         // Save a post
      'deleted_post',      // Delete a post
      'trashed_post',      // Empty Trashed post
      'edit_post',         // Edit a post - includes leaving comments
      'delete_attachment', // Delete an attachment - includes re-uploading
    ];
  }

  public function clearAll()
  {
    $urls = self::collectMultipleReplicas();

    foreach ($urls as $url) {
      $this->purge($url);
    }
  }

  /**
   * Description
   *
   * @param $url
   * @return string
   */
  public function purge($url)
  {
    try {
      $parsedUrl = parse_url($url);

      // bail early if there's no host since some plugins are weird
      if (!isset($parsedUrl['host'])) {
        throw new Exception(__('Invalid host in purge URL', 'wp-nc-easywp'));
      }

      // get the schema
      $schema = WPNCEasyWP()->options->get('varnish.schema', 'http://');

      // get default purge method
      $x_purge_method = WPNCEasyWP()->options->get('varnish.default_purge_method', 'default');

      // default regexp
      $regex = '';

      if (isset($parsedUrl['query']) && ($parsedUrl['query'] == 'vhp-regex')) {
        $regex          = '.*';
        $x_purge_method = 'regex';
      }

      // varnish ip
      $varnishIp = WPNCEasyWP()->options->get('varnish.ip', '127.0.0.1');

      // path
      $path = $parsedUrl['path'] ?? '';

      // setting host
      $hostHeader = $parsedUrl['host'];
      $podname    = getenv('HOSTNAME');
      $host       = $hostHeader;
      if (empty($podname)) {
        $host = $varnishIp ?? $hostHeader;
      }

      if (isset($parsedUrl['port'])) {
        $hostHeader = "{$host}:{$parsedUrl['port']}";
      }

      $headers = [
        'host' => $hostHeader,
        'X-Purge-Method' => $x_purge_method,
      ];

      // final url
      // [CU-8693bvzdn]-fix: Varnish cache is not cleared ...
      // always call Varnish on localhost,
      // otherwise wp_remote_request() will call directly domain name on http endpoint, with enforced redirection by ingress controller to https one,
      // and for that WP installation needs to be able to validate site's cert issuer with baked in ca-bundle (wp-includes/certificates/ca-bundle.crt),
      // which is not always possible (e.g. customer is using self-sign cert, or server cert doesn't contain a full chain).

      $urlToPurge = "{$schema}{$varnishIp}{$path}{$regex}";

      // make easier remote debugging of any potential future Clear Cache issues
      $response = wp_remote_request($urlToPurge, ['method' => 'PURGE', 'headers' => $headers]);
      $body = wp_remote_retrieve_body($response);

      return $urlToPurge;
    } catch (Exception $e) {
      logger()->error($e);
    }
  }

  public function doPurge()
  {
    if (empty($this->urlsToPurge)) {
      return;
    }

    // save some useful info in the options
    $urls = [];

    foreach ($this->urlsToPurge as $url) {
      $urls[] = $this->purge($url);
    }
  }

  /**
   * Method used to clear all.
   *
   * @param bool $silent Set to true to hidden the admin notice. Default false.
   */
  public function doActionMenu($silent = false)
  {
    $this->clearAll();

    if (!$silent) {
      add_action('admin_notices', function () {
        echo '<div id="message" class="notice notice-success fade is-dismissible">';
        echo '<p>';
        _e('Varnish cache emptied!', 'wp-nc-easywp');
        echo '</p>';
        echo '</div>';
      });
    }
  }

  public static function info()
  {
    $result = [
      'svc' => self::getServiceName() ?: 'unavaiable',
      'HOSTNAME' => getenv('HOSTNAME') ?: "not set",
      'ips' => implode(',', self::collectMultipleReplicas()),
    ];

    return $result;
  }

  // ---

  /**
   * Collect the url for a post id.
   *
   * @param \WPNCEasyWP\Http\Varnish\int $postId
   */
  public function collectUrlPostId($postId)
  {
    try {

      // gathering post information
      $postTypeObject       = get_post_type_object($postId);
      $restUrl              = get_rest_url();
      $postStatus           = get_post_status($postId);
      $isValidPostStatus    = in_array(get_post_status($postId), $this->allowedPostStatus);
      $postType             = get_post_type($postId);
      $isInvalidPostType    = in_array($postType, $this->invalidPostTypes);
      $isNoArchivedPostType = in_array($postType, $this->noArchivePostTypes);
      $postPermalink        = get_permalink($postId);

      // main check
      if (!($postPermalink && $isValidPostStatus && !$isInvalidPostType)) {
        return;
      }

      // first of all we load the permalink
      $purgeUrls = [$postPermalink];

      // JSON API Permalink for the post based on type
      // We only want to do this if the rest_base exists
      // But we apparently have to force it for posts and pages (seriously?)

      if (isset($postTypeObject->rest_base)) {
        $purgeUrls[] = "{$restUrl}{$this->restApiRoute}/{$postTypeObject->rest_base}/{$postId}/";
      } elseif (in_array($postType, ['post', 'page'])) {
        $purgeUrls[] = "{$restUrl}{$this->restApiRoute}/{$postType}/{$postId}/";
      }

      // Add in AMP permalink if Automattic's AMP is installed
      if (function_exists('amp_get_permalink')) {
        $purgeUrls[] = amp_get_permalink($postId);
      }

      // Regular AMP url for posts
      $purgeUrls[] = "{$postPermalink}amp/";

      // Also clean URL for trashed post.
      if ($postStatus == 'trash') {
        $trashPermalink = str_replace('__trashed', '', $postPermalink);
        $purgeUrls[]    = $trashPermalink;
        $purgeUrls[]    = "{$trashPermalink}feed/";
      }

      // category purge based on Donnacha's work in WP Super Cache
      $categories = get_the_category($postId);
      if ($categories) {
        foreach ($categories as $cat) {
          $purgeUrls[] = get_category_link($cat->term_id);
          $purgeUrls[] = "{$restUrl}{$this->restApiRoute}/categories/{$cat->term_id}/";
        }
      }

      // tag purge based on Donnacha's work in WP Super Cache
      $tags = get_the_tags($postId);
      if ($tags) {
        $tag_base = get_option('tag_base');
        $tag_base = empty($tag_base) ? '/tag/' : $tag_base;
        foreach ($tags as $tag) {
          $purgeUrls[] = get_tag_link($tag->term_id);
          $purgeUrls[] = "{$restUrl}{$this->restApiRoute}{$tag_base}{$tag->term_id}/";
        }
      }

      // Custom Taxonomies
      // Only show if the taxonomy is public
      $taxonomies = get_post_taxonomies($postId);
      if ($taxonomies) {
        foreach ($taxonomies as $taxonomy) {
          $features = (array) get_taxonomy($taxonomy);
          if ($features['public']) {
            $terms = wp_get_post_terms($postId, $taxonomy);
            foreach ($terms as $term) {
              $purgeUrls[] = get_term_link($term);
              $purgeUrls[] = "{$restUrl}{$this->restApiRoute}/{$term->taxonomy}/{$term->slug}/";
            }
          }
        }
      }

      // author URLs
      $author_id   = get_post_field('post_author', $postId);
      $purgeUrls[] = get_author_posts_url($author_id);
      $purgeUrls[] = get_author_feed_link($author_id);
      $purgeUrls[] = "{$restUrl}{$this->restApiRoute}/users/{$author_id}/";

      // archives and their feeds
      if (!$isNoArchivedPostType) {
        $purgeUrls[] = get_post_type_archive_link($postType);
        $purgeUrls[] = get_post_type_archive_feed_link($postType);
        // TODO Need to add in JSON?
      }

      // feeds
      array_push(
        $purgeUrls,
        get_bloginfo_rss('rdf_url'),
        get_bloginfo_rss('rss_url'),
        get_bloginfo_rss('rss2_url'),
        get_bloginfo_rss('atom_url'),
        get_bloginfo_rss('comments_rss2_url'),
        get_post_comments_feed_link($postId)
      );

      // home Pages and (if used) posts page
      array_push(
        $purgeUrls,
        get_rest_url(),
        home_url() . '/'
      );

      if (get_option('show_on_front') == 'page') {
        $page_for_posts = get_option('page_for_posts');
        // Ensure we have a page_for_posts setting to avoid empty URL
        if (!empty($page_for_posts)) {
          array_push($purgeUrls, get_permalink($page_for_posts));
        }
      }

      $this->urlsToPurge = self::sanitizeForMultipleReplicas($purgeUrls);
    } catch (Exception $e) {
      WPNCEasyWP()->options->set('varnish.error', [__METHOD__, $e]);
    }
  }

  protected static function sanitizeForMultipleReplicas($urls)
  {
    $svc = self::getServiceName();
    if ($svc) {
      $ips     = self::collectMultipleReplicas();
      $newUrls = [];
      $home    = home_url();
      foreach ($ips as $ip) {
        foreach ($urls as $url) {
          $newUrls[] = str_replace($home, $ip, $url);
        }
      }

      return $newUrls;
    }

    return $urls;
  }
}
