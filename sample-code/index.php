<?php

$JSON_FOLDER = "CiteIt.net_json/";

function get_json_from_webservice($submitted_url){

	// TODO: CHANGE TO HTTPS
  	$CITEIT_BASE_URL = 'http://api.citeit.net/v0.4/url/?&format=list&url=';
	$DOMAIN_FILTER_DISABLED = True; 
	$DOMAIN_FILTER = "citeit.net";  // do not save unless from this domain	
	$parse = parse_url($submitted_url);
	$submitted_url_domain = $parse['host'];
  	$data = array();


	// Is this a valid url?  If so save JSON results to file.
	if (filter_var($submitted_url, FILTER_VALIDATE_URL)) { 			

		// Only allow requests for this domain
		if (($submitted_url_domain == $DOMAIN_FILTER) | $DOMAIN_FILTER_DISABLED){

			$webservice_url = $CITEIT_BASE_URL . $submitted_url;
			
			// Call Webservice and return json
			$data = json_decode(file_get_contents($webservice_url), true);

			foreach($data as $quote_num=>$quote){
				$public_url = sha_to_url($quote['sha256']);	
				print("<p><a href='CiteIt.net_json/" . $quote['sha256'] . ".json'>" . $quote['sha256'] . "</a> : " . $quote['citing_quote'] . "</p>");

				$filename = "CiteIt.net_json/" . $quote['sha256'] . ".json";
				$json = json_encode($quote);
				file_put_contents($filename, $json);
			}
		}
		
	} else {
	    print("<p class='error'>$url is not a valid URL</p>");
	}
	return data;
}

function sha_to_url($sha256){
  // Construct a link to the JSON snippet on the main CiteIt site	
  return 'https://read.citeit.net/quote/sha256/0.4/' . substr($sha256, 0, 2) . "/" . $sha256 . '.json';

}

function print_json_files($path){
  if (isset($_POST['url'])){

	$files = array_diff(
				scandir($path),
				array('.', '..') // remove dots from array
			);
	print("<h3>All Local JSON Files:</h3>");
	print("<ul>");
	foreach($files as $file){
		print("<li><a href='" . $path . $file . ".json'>" . $file . ".json</a></li>");
	}
	print("</ul>");
  }
}


?>
<!DOCTYPE html>
<html lang="en-US" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
<head >
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>CiteIt Examples: Call CiteIt.net Webservice from Php</title>

  <!-- CiteIt Javascript Dependencies
      - jQuery: manipulate Dom: 
      - query api.CiteIt.net
      - download JSON to hidden citeit_container div
      - add arrows and popup links to q tags and blockquotes

  --- 1) jQuery -->
  <script src="https://code.jquery.com/jquery-1.12.4.min.js"   
	integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="   
	crossorigin="anonymous">
  </script>

  <!-- 2) jQuery Migrate: Used to migrate jQuery: https://github.com/jquery/jquery-migrate -->
  <script src="https://code.jquery.com/jquery-migrate-1.4.1.min.js" 
	integrity="sha256-SOuLUArmo4YXtXONKz+uxIGSKneCJG4x0nVcA0pFzV0=" 
	crossorigin="anonymous">
  </script>

  <!-- 3) Generate q-tag Popup -->
  <script   
	src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"   
	integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="   
	crossorigin="anonymous">
  </script> 

  <!-- 4) Calculate JSON Hash values using sha256 library --->
  <script src='lib/forge-sha256/build/forge-sha256.min.js' defer></script>

  <!-- 5) jsVideoUrlParser: Detect Domain: Determine if an Embed code can be use: YouTube, Vimeo, Soundcloud -->
  <script src='lib/jsVideoUrlParser/dist/jsVideoUrlParser.min.js' defer></script>

  <!--CSS Styles: CiteIt & JQuery Popup Window -->
  <link rel='stylesheet' id='wp-bigfoot-public-css'  href='https://pages.citeit.net/wp-content/plugins/CiteIt.net/lib/jquery-ui-1.12.1/jquery-ui.min.css' type='text/css' media='all' />
  <link rel='stylesheet' id='wp-bigfoot-public-css'  href='css/quote-context-style.css' type='text/css' media='all' />
  <link rel='stylesheet' id='wp-bigfoot-public-css'  href='css/sample.css' type='text/css' media='all' />

  <!-- 6) Main CiteIt Javascript Code: Download JSON & Create Popup windows and Expanding Arrows  -->
  <script src='js/versions/0.4/CiteIt-quote-context.js' defer></script>


  <link rel='stylesheet' id='minimum-google-fonts-css'  href='//fonts.googleapis.com/css?family=Roboto%3A300%2C400%7CRoboto+Slab%3A300%2C400&#038;ver=3.0.1' type='text/css' media='all' />


</head>
<body>

<div class="wrap">
	<div class="title-area">
	<h1 class="site-title" itemprop="headline">
		<a href="https://www.citeit.net/" title="CiteIt.net">
		<div class="logo">	
			<div class="quote_arrows">▲</div>
			<span class="custom-title">CiteIt.net</span>
			<div class="quote_arrows">▼</div>
		</div>
		</a>
	</h1>
	</div>
</div>

<div class="site-tagline">
	<h2>a higher standard of citation</h2>
</div>

<ul id="top_navigation_menu" class="menu genesis-nav-menu menu-primary"><li id="menu-item-425" class="menu-item menu-item-type-post_type menu-item-object-page  page_item page-item-18 current_page_item menu-item-425"><a href="https://www.citeit.net/" aria-current="page"><span >Home</span></a></li>
<li id="menu-item-1072" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-1072"><a href="https://demo.citeit.net"><span >demo</span></a></li>

<li id="menu-item-428" class="menu-item menu-item-type-post_type menu-item-object-page current-menu-item page_item page-item-8 current_page_item menu-item-428"><a href="https://www.citeit.net/code/" aria-current="page"><span >code</span></a></li>

<li id="menu-item-983" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-983"><a href="https://www.citeit.net/code/?tab=volunteer"><span >Volunteer</span></a></li>
<li id="menu-item-994" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-994"><a href="https://pages.citeit.net/"><span >Blog</span></a></li>
<li id="menu-item-427" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-427"><a href="https://www.citeit.net/about/"><span >about</span></a></li>
</ul>

<div class="wrap">

<h3 class="breadcrumb"><a href="https://pages.citeit.net/code/">Code</a> &gt; <a href="https://pages.citeit.net/sample-code/">Sample Code</a> &gt; Web Service Submission</h3>


	<form action="" method="POST">
	  <input class="url"
	  	type="url"
		name="url"
	  	onfocus="if (this.value=='https://') this.value = 'https://'"
	  	value="<?php print($_POST['url'] ? $_POST['url']: 'https://' ); ?>"
	  >
	  <input class="submit" type="submit" value="index page" />
	</form>


        <h2>Index My Web Citations:</h2>

	<h3>How to add context to one of your webpages with Citet.net</h3>

    <ol>
        <li>Mark up your page by <b>adding a blockquote</b> or q tag with a <b>"cite" tag</b>: 
			<ul><li>&lt;blockquote cite='http://avalon.law.yale.edu/19th_century/jeffauto.asp'&gt;sample quote&lt;blockquote&gt;</li></ul>

	    </li>
        <li>Add the CiteIt.net <b>javascript</b> to your page template (see <a href="https://github.com/CiteIt/citeit-sample-code/blob/master/examples.html">example code</a>)</li>
        <li>Submit the URL of your page to be indexed (above).  This will <b>create one JSON file for each quote</b> on the page. (<a href="https://read.citeit.net/quote/sha256/0.4/d5/d588c1c9c4acfcd254acc4033b7888e98f21e426214b21f0e07673664e328e39.json">sample JSON file</a>)</li>
        <li><b>Reload</b> your page to pull in the newly created JSON file/s.</li>
    </ol>

	<h3>What this does</h3>
	<ol>
    <li>
       This will retreive the <b>500 characters of context</b>
	   before and after your quotation and store it in a JSON snippet.
	</li>

    <li>When you <b>reload</b> your page, the javascript will <b>pull in the context</b> from the
    newly-created JSON snippet.</li>

	<li>This <i>index.php</i> script makes a <i>POST</i> request to the <a href="https://api.citeit.net/">api.citeit.net web service</a> and <b>saves a copy</b> of the JSON to a local folder: <b><a href="CiteIt.net_json/">CiteIt.net_json</a></b> 
		<ul><li>(you need to set <b>write permissions</b> for this folder)</li></ul>

	</ol>


	<?php
	if (isset($_POST['url'])){
	  print("<h3>Results:</h3>");
	  $json = get_json_from_webservice($_POST['url']);

	}
	?>

	<div id="list_citations">
	<?php
		// print_json_files($JSON_FOLDER);
	?>
	</div>

<!---------------------- Begin: Main Content ----------------------->

	<h3>API Examples</h3>
	<ul>
		<li>Submit a URL for Indexing: (POST-preferred)
			<ul>
			<li>summary: <a rel="nofollow" href="https://api.citeit.net/v0.4/url/?url=https://pages.citeit.net/sample-code/examples.html">https://api.citeit.net/v0.4/url/?url=https://pages.citeit.net/sample-code/examples.html</a>
			</li>
			<li>list: <a href="https://api.citeit.net/v0.4/url/?url=https://pages.citeit.net/sample-code/examples.html&format=list">https//api.citeit.net/v0.4/url/?url=https://pages.citeit.net/sample-code/examples.html&amp;format=list</a></li>
			</ul>
		</li>

		<li>Text version of URL: (html only, PDF version desired)
			<ul>
			<li><a rel="nofollow" href="https://api.citeit.net/v0.4/url/text-version?url=https://pages.citeit.net/sample-code/examples.html">https://api.citeit.net/v0.4/url/text-version?url=https://pages.citeit.net/sample-code/examples.html</a>
			</li>
			</ul>
		</li>

		<li>Quote Hashkeys of URL:
			<ul>
			<li><a rel="nofollow" href="https://api.citeit.net/v0.4/url/hashkeys?url=https://pages.citeit.net/sample-code/examples.html">https://api.citeit.net/v0.4/url/hashkeys?url=https://pages.citeit.net/sample-code/examples.html</a>
			</li>
			</ul>
		</li>

		<li>Canonical URL: get preferred url
			<ul>
			<li><a rel="nofollow" href="https://api.citeit.net/v0.4/url/canonical-url?url=https://pages.citeit.net/sample-code/examples.html%3Fsearch=test-querystring">https://api.citeit.net/v0.4/url/canonical-url?url=https://pages.citeit.net/sample-code/examples.html?search=test-querystring</a>
			</li>
			</ul>
		</li>
	</ul>

  </div><!--wrap-->
<!---------------------- End: Main Content ----------------------->

	<div id="footer">
		<p><b>What:</b> <a href="https://pages.citeit.net/">CiteIt.net</a> is a citation tool that 
			<b>enables web authors to demonstrate the context</b> of their citations.
		</p>
		<p><b>Who:</b> CiteIt.net allows journalists, academics and web authors who want to set a higher standard of discourse.</p>
		<p><b>How:</b> CiteIt.net is an <a href="https://pages.citeit.net/code/">open source program</a> which 
			can be added to a website with a WordPress plugin or a bit of custom code.
		</p>
	</div>


	<div id="navigation">
		<b>New Submission</b> | <a href="examples.html">Examples</a> | <a href="https://pages.citeit.net/sample-code/text-version.php">Text-Version</a> | <a href="https://meta.wikimedia.org/wiki/User:Timlangeman/sandbox">Wikipedia Proposal</a> | <a href="https://github.com/CiteIt/citeit-sample-code">GitHub Download</a>
	</div>


</body>
</html>
