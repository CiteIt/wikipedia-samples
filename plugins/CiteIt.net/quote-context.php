<?php
/**
 * @package CiteIt.net
 * @version 0.4.8
 */
/*
Plugin Name: CiteIt.net Quote-Context
Plugin URI: http://www.CiteIt.net

Description: Expands "blockquotes" with surrounding text by : selecting all "blockquote" tags that have a "cite" attribute, downloading the cited url, locating the citation, saving the "before" and "after" text into a json file, and adding the retrieved text to the dom.  Submits Quotations from Published posts to the CiteIt.net web service.
Author: Tim Langeman
Version: 0.4.8
Author URI: http://www.openpolitics.com/tim
*/

$plugin_version_num = "0.4.8";
$webservice_version_num = "0.4";



function citeit_quote_context_header() {
   # Add javascript depencencies to html header
   wp_enqueue_script('jquery');
   wp_enqueue_script('sha256', plugins_url('lib/forge-sha256/build/forge-sha256.min.js', __FILE__) );
   wp_enqueue_script('quote-context', plugins_url('/js/versions/0.4/CiteIt-quote-context.js', __FILE__) );
   wp_enqueue_script('jsVideoUrlParser', plugins_url('lib/jsVideoUrlParser/dist/jsVideoUrlParser.min.js', __FILE__) );
}

function citeit_quote_context_hack(){
  echo "
	<script type='text/javascript'></script>";
}

function citeit_quote_context_footer() {
  # Adds style sheets, ui javascript, hiddend div id="citeit_container"
  # Add call to .quoteContext() custom jQuery function

	wp_enqueue_style('citeit_quote_context_css', plugins_url('css/quote-context-style.css', __FILE__) );

	echo "<div id='citeit_container'><!-- citeit quote-context.js injects data returned from lookup in this hidden div --></div>
    <script type='text/javascript'>
	    // Call plugin on all blockquotes:
        	jQuery('q, blockquote').quoteContext();
    </script>";

	wp_enqueue_script('jquery-ui', '//code.jquery.com/ui/1.11.4/jquery-ui.min.js');
	wp_enqueue_style('jquery-ui-css', '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css');
}

add_action( 'wp_enqueue_scripts', 'citeit_quote_context_header' );
add_action( 'wp_head', 'citeit_quote_context_hack');
add_action( 'wp_footer', 'citeit_quote_context_footer');

/*********** Modify Publish Action: Submit to Citeit.net ******/

function post_to_citeit($post_url){
  // Post $url to $webservice_url using curl

  $webservice_url = "http://api.citeit.net/v0.4/url/";
  $post_fields = 'url=' . $post_url;
  $curl_user_agent = "CiteIt.net WordPress v" . $plugin_version_num . " (http://www.CiteIt.net)";

  $ch = curl_init( $webservice_url );
  curl_setopt($ch,CURLOPT_USERAGENT, $curl_user_agent);
  curl_setopt( $ch, CURLOPT_POST, 1);
  curl_setopt( $ch, CURLOPT_POSTFIELDS, $post_fields);
  curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt( $ch, CURLOPT_HEADER, 0);
  curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

  $response = curl_exec( $ch );
  return $response;
}

function count_quotations($html){
  /* Count the number of quotations using the 'cite' tag
  * (Used to determine if URL should be submitted to CiteIt.net)
  *
  * Credit: http://htmlparsing.com/php.html
  */
  $quotations_count = 0;

  # Parse the HTML
  # The @ before the method call suppresses any warnings that
  # loadHTML might throw because of invalid HTML in the page.
  $dom = new DOMDocument();
  @$dom->loadHTML($html);
  $xpath = new DOMXpath($dom);

  # Iterate over all the <blockquote> and <q> tags
  $quotations = $xpath->query('//blockquote | //q');

  foreach($quotations as $quote) {
    $cite_url = $quote->getAttribute('cite');
      // If URL in valid form:
      if (!filter_var($cite_url, FILTER_VALIDATE_URL) === false) {
        $quotations_count = $quotations_count + 1;
  	  }
  }
  return $quotations_count;
}

function citeit_hook($post_id) {
  /* Determine whether to submit post URL to CiteIt.net,
   * Depending upon whether a quote uses the 'cite' tag with
   * a URL of valid format
   * Optionally email a notification
   */
  $quotations_count = 0;
  $post_url = get_permalink($post_id);
  $subject = 'A CiteIt has been published';
  $post_content = get_post_field('post_content', $post_id);

  $message = "A CiteIt citation has been updated on your website:\n\n";
  $message .= $post_url . "\n";
  $message .= $content;

  $send_notification = false;
  $email_address = 'your-address@example.com';

  $quotations_count = count_quotations($post_content);
  if ($quotations_count > 0){

    # Make Sure the Post URL is of Valid Form
    if (!filter_var($post_url, FILTER_VALIDATE_URL) === false) {
       post_to_citeit($post_url);
       if ($send_notification){
         wp_mail($email_address, $subject, $message );
       }
    } else {
       echo("Post URL <$url> is not a valid URL");
    }
  }
}

add_action( 'publish_post', 'citeit_hook', 10, 2 );

/******************** Add Permalinks to Citaton Tags ************************/

function add_permalink($citation_tag, $content){
  // Embed a permalink in each blockquote and q tag
  // This allows citations to be attributed to their canonical_url even if 
  // they are displayed on the 'homepage' or some other non-canonical location

  // Credit: https://stackoverflow.com/questions/5037592/how-to-add-rel-nofollow-to-links-with-preg-replace
  // Alex: https://stackoverflow.com/users/31671/alex

  $permalink_attr_name = 'data-citeit-citing-url';

  $dom = new DOMDocument('1.0', 'UTF-8');
  $dom->preserveWhitespace = FALSE;
  $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));

  $citations = $dom->getElementsByTagName($citation_tag);
  foreach($citations as $citation) {

        $permalink = get_the_permalink();
        $oldRelAtt = $citation->attributes->getNamedItem($permalink_attr_name);

		// Check if attribute is already set
        if ($oldRelAtt == NULL) {
            $newRel = $permalink;
        } else {
            $oldRel = $oldRelAtt->nodeValue;
            $oldRel = explode(' ', $oldRel);
            if (in_array($permalink, $oldRel)) {
                continue;
            }
            $oldRel[] = $permalink;
            $newRel = implode($oldRel,  ' ');
        }

		// Set attribute value
        $customAtt = $dom->createAttribute($permalink_attr_name);
        $noFollowNode = $dom->createTextNode($newRel);
        $customAtt->appendChild($noFollowNode);
        $citation->appendChild($customAtt);
  }

  return $dom->saveHTML();
}

function add_permalink_to_citations($content) {
	// It would be nice if it were possible to get more than one tag at a time: (blockquote, q):  $dom->getElementsByTagName

	$content = add_permalink('blockquote', $content);
	$content = add_permalink('q', $content);

	return $content;

}
add_filter( 'the_content', 'add_permalink_to_citations' );


/***************** Add TinyMCE Admin Buttons ********************/
# Tiny MCE: add Custom CiteIt button to editor
# Credit: AJ Clarke: http://www.wpexplorer.com/wordpress-tinymce-tweaks/

# Button 1: Declare script for new button: blockquote
function citeit_add_tinymce_blockquote_plugin( $plugin_array ) {
	$plugin_array['citeit_blockquote'] = plugins_url('/CiteIt.net/js/tinymce-blockquote.js');
	return $plugin_array;
}

function citeit_register_tinymce_blockquote( $buttons ) {
	array_push( $buttons, 'citeit_blockquote' );
	return $buttons;
}

# Button 2: Declare script for new button: q
function citeit_add_tinymce_q_plugin( $plugin_array ) {
	$plugin_array['citeit_q'] = plugins_url('/CiteIt.net/js/tinymce-q.js');
	return $plugin_array;
}

function citeit_register_tinymce_q( $buttons ) {
	array_push( $buttons, 'citeit_q' );
	return $buttons;
}

# Hooks your functions into the correct filters
function citeit_add_mce_quotation_buttons() {
	# check user permissions
	if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
		return;
	}
	# check if WYSIWYG is enabled
	if ( 'true' == get_user_option( 'rich_editing' ) ) {
		add_filter( 'mce_external_plugins', 'citeit_add_tinymce_blockquote_plugin' );
		add_filter( 'mce_buttons', 'citeit_register_tinymce_blockquote' );

		add_filter( 'mce_external_plugins', 'citeit_add_tinymce_q_plugin' );
		add_filter( 'mce_buttons', 'citeit_register_tinymce_q' );
	}
}

add_action('admin_head', 'citeit_add_mce_quotation_buttons');

function defer_js_async($tag){
	function js_async_attr($tag){
	   # Add async to all remaining scripts
	   return str_replace( ' src', ' async="async" src', $tag );
	}

	$scripts_to_async = array('jquery.js', 'forge-sha256.min.js', 'CiteIt-quote-context.js');

	# async scripts
	foreach($scripts_to_async as $async_script){
		if(true == strpos($tag, $async_script ) )
		return str_replace( ' src', ' async="async" src', $tag );
	}
	return $tag;
}
add_filter( 'jquery', 'js_async_attr', 10 );

?>
