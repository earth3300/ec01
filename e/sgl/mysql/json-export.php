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
  		$str .= sprintf( '"title": ""%s"', json_encode( $post->post_title );,
  		$str .= sprintf( '"link": "%s", $post->the_link() );
  		$str .= sprintf( '"pubDate": "%s"', mysql2date( 'D, d M Y H:i:s +0000', get_post_time( 'Y-m-d H:i:s', true ), false ); ?>",
  		$str .= sprintf( '"dc:creator": "%s"', get_the_author_meta( 'login' ) );
  		$str .= sprintf( '"guid": "%s"', the_guid()' );
  		$str .= sprintf( '"content:encoded": ""%s"',  json_encode('the_content_export', $post->post_content ));
  		$str .= sprintf( '"excerpt:encoded": ""%s"',  json_encode('the_excerpt_export', $post->post_excerpt ));
  		$str .= sprintf( '"wp:post_id": "%s"', $post->ID );
  		$str .= sprintf( '"wp:post_date": "%s"', $post->post_date );
  		$str .= sprintf( '"wp:post_name": "%s"', $post->post_name );
  		$str .= sprintf( '"wp:status": "%s"', $post->post_status );
  		$str .= sprintf( '"wp:post_parent": "%s"', $post->post_parent );
      $str .= sprintf( '"wp:post_child": "%s"', $post->post_parent );
  		$str .= sprintf( '"wp:post_type": "%s"', $post->post_type );,

      return $str;

} // End class
