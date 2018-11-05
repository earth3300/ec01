<?php
/**
 * MySQL to JSON Exporter
 *
 * @link: https://gist.github.com/panks/5502750
 *
 * File: json-export.php
 * Created: 2018-11-05
 * Updated: 2018-11-05
 * Time: 11:14 EST
 */

/** No direct access (NDA). */
defined('NDA') || exit('NDA');

/**
 * MySQL Read
 *
 * No Delete, Insert or Update Methods.
*/
class JSONExporter extends MySQLReader
{
  /**
   * Get ths Posts.
   *
   */
  private function getPosts()
  {
  	$reader = new MySQLReader();

  	// get all posts
  	try {
  		$articles = $reader->get('posts');
  		print_r($articles);
  		echo $reader->num_rows(); // number of rows returned
  	} catch(Exception $e) {
  		echo 'Caught exception: ', $e->getMessage();
  	}
  }

  /**
   * Export to JSON
   *
   */
  private function exportJSON( $arr )
  {
  	// get all posts
  	try {
  		$articles = $reader->get('posts');
  		print_r($articles);
  		echo $reader->num_rows(); // number of rows returned
  	} catch(Exception $e) {
  		echo 'Caught exception: ', $e->getMessage();
  	}
  }
} // End class.

class ExportArticlesJSON extend MySQLReader
{

  /**
   * Generates the WXR export file for download
   *
   * @since 2.1.0
   *
   * @param array $args Filters defining what should be included in the export
   */
  function export( $args = array() )
  {

    /** @var array */
  	$defaults = array(
      'content' => 'all',
      'author' => false,
      'category' => false,
  		'start_date' => false,
      'end_date' => false,
      'status' => false,
  	);

  	header( 'Content-Description: File Transfer' );
  	header( 'Content-Disposition: attachment; filename=' . $filename );
  	header( 'Content-Type: application/json; charset=utf-8';

  	// Get the posts


  	function filterPostmeta( $return_me, $meta_key ) {
  		if ( '_edit_lock' == $meta_key )
  			$return_me = true;
  		return $return_me;
  	}

  "channel": {
      "title": "<?php bloginfo_rss( 'name' ); ?>",
      "link": "<?php bloginfo_rss( 'url' ); ?>",
      "description": "<?php bloginfo_rss( 'description' ); ?>",
      "pubDate": "<?php echo date( 'D, d M Y H:i:s +0000' ); ?>",
      "language": "<?php bloginfo_rss( 'language' ); ?>",
      "siteUrl": "<?php echo wxr_site_url(); ?>",

       "item": [

  	// fetch 20 posts at a time rather than loading the entire table into memory
  	while ( $next_posts = array_splice( $post_ids, 0, 20 ) ) {
  	$where = 'WHERE ID IN (' . join( ',', $next_posts ) . ')';
  	$posts = $wpdb->get_results( "SELECT * FROM {$wpdb->posts} $where" );

  	// Begin Loop
  	foreach ( $posts as $post ) {
  		setup_postdata( $post );
  		$is_sticky = is_sticky( $post->ID ) ? 1 : 0;
      {
  		"title": <?php echo json_encode(apply_filters( 'the_title_rss', $post->post_title )); ?>,
  		"link": "<?php the_permalink_rss() ?>",
  		"pubDate": "<?php echo mysql2date( 'D, d M Y H:i:s +0000', get_post_time( 'Y-m-d H:i:s', true ), false ); ?>",
  		"dc:creator": "<?php echo get_the_author_meta( 'login' ); ?>",
  		"guid": "<?php the_guid(); ?>",
  		"content:encoded": <?php echo  json_encode(apply_filters( 'the_content_export', $post->post_content )); ?>,
  		"excerpt:encoded": <?php echo  json_encode(apply_filters( 'the_excerpt_export', $post->post_excerpt )); ?>,
  		"wp:post_id": "<?php echo $post->ID; ?>",
  		"wp:post_date": "<?php echo $post->post_date; ?>",
  		"wp:post_name": "<?php echo $post->post_name; ?>",
  		"wp:status": "<?php echo $post->post_status; ?>",
  		"wp:post_parent": "<?php echo $post->post_parent; ?>",
      "wp:post_child": "<?php echo $post->post_parent; ?>",
  		"wp:post_type": "<?php echo $post->post_type; ?>",


} // End class
