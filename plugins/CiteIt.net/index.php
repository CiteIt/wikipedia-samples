<?php

$JSON_FOLDER = "CiteIt.net_json/";

function get_json_from_webservice($submitted_url){

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
  <style>
	body {
		margin-top: 50px;
		margin-bottom: 70px;
		margin-right: 50px;
		margin-left: 50px;
		font-size: 125%;
	} 
	input {
		font-size: 125%;
	}
	input.submit {
		display: inline-block;
		padding: 15px 25px;
		font-size: 24px;
		cursor: pointer;
		text-align: center;
		text-decoration: none;
		outline: none;
		color: #fff;
		background-color: #444;
		border: none;
		border-radius: 15px;
		box-shadow: 0 9px #999;
	}
	input.submit:hover {
		background-color: #666
	}
	input.submit:active {
		background-color: #3e8e41;
		box-shadow: 0 5px #666;
		transform: translateY(4px);
	}
	input.url {
		width: 70%;
	}
	p.error {
		color: red;
		font-weight: 800;
	}
	div#list_citations {
		margin-top: 200px;
	}
	div#footer {
		margin-top: 70px;
		background-color: #ddd;
		border: 1px solid #bbb;
		padding: 30px 50px;
	}
	div#navigation {
		margin-top: 60px;
	}
	
  </style>

</head>
<body>

	<h1><a href="https://www.citeit.net/">CiteIt.net</a></h1>
	<h2>a higher standard of citation</h2>


	<form action="" method="POST">
	  <input class="url"
	  	type="url"
		name="url"
	  	onfocus="if (this.value=='https://') this.value = 'https://'"
	  	value="<?php print($_POST['url'] ? $_POST['url']: 'https://' ); ?>"
	  >
	  <input class="submit" type="submit" value="submit page" />
	</form>

	<h3>How to add context to one of your webpages with Citet.net</h3>

    <ol>
        <li>Mark up your page by adding a blockquote or q tag with a "cite" tag</li>
        <li>Add the CiteIt javascript to your page template (see <a href="https://github.com/CiteIt/citeit-examples">example code</a>)</li>
        <li>Submit the URL of your page to be indexed (above).</li>
        <li>Reload your page.</li>
    </ol>

	<h3>What this does</h3>

    <p>
       This will retreive the 500 characters of context
	   before and after your quotation and store it in a JSON snippet.
	</p>

    <p>When you reload your page, the javascript will pull in the context from the
    newly-created JSON snippet.</p>


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

	<!---------------------- Display API Methods ----------------------->
	<h3>API Examples</h3>
	<ul>
		<li>Submit a URL for Indexing: (POST-preferred)
			<ul>
			<li>summary: <a rel="nofollow" href="http://api.citeit.net/v0.4/url/?url=https://www.citeit.net/sample-code/examples.html">http://api.citeit.net/v0.4/url/?url=https://www.citeit.net/sample-code/examples.html</a>
			</li>
			<li>list: <a href="http://api.citeit.net/v0.4/url/?url=https://www.citeit.net/sample-code/examples.html&format=list">http://api.citeit.net/v0.4/url/?url=https://www.citeit.net/sample-code/examples.html&amp;format=list</a></li>
			</ul>
		</li>

		<li>Text version of URL: (html only, PDF version desired)
			<ul>
			<li><a rel="nofollow" href="http://api.citeit.net/v0.4/url/text-version?url=https://www.citeit.net/sample-code/examples.html">http://api.citeit.net/v0.4/url/text-version?url=https://www.citeit.net/sample-code/examples.html</a>
			</li>
			</ul>
		</li>

		<li>Quote Hashkeys of URL:
			<ul>
			<li><a rel="nofollow" href="http://api.citeit.net/v0.4/url/hashkeys?url=https://www.citeit.net/sample-code/examples.html">http://api.citeit.net/v0.4/url/hashkeys?url=https://www.citeit.net/sample-code/examples.html</a>
			</li>
			</ul>
		</li>

		<li>Canonical URL: get preferred url
			<ul>
			<li><a rel="nofollow" href="http://api.citeit.net/v0.4/url/canonical-url?url=https://www.citeit.net/sample-code/examples.html%3Fsearch=test-querystring">http://api.citeit.net/v0.4/url/canonical-url?url=https://www.citeit.net/sample-code/examples.html?search=test-querystring</a>
			</li>
			</ul>
		</li>
	</ul>

	<!---------------------- End: Display API Methods ----------------------->


	<div id="footer">
		<p><b>What:</b> <a href="https://www.citeit.net/">CiteIt.net</a> is a citation tool that 
			<b>enables web authors to demonstrate the context</b> of their citations.
		</p>
		<p><b>Who:</b> CiteIt.net allows journalists, academics and web authors who want to set a higher standard of discourse.</p>
		<p><b>How:</b> CiteIt.net is an <a href="https://www.citeit.net/code/">open source program</a> which 
			can be added to a website with a WordPress plugin or a bit of custom code.
		</p>
	</div>

	<div id="navigation">
		<b>New Submission</b> | <a href="examples.html">Examples</a> | <a href="https://www.citeit.net/sample-code/text-version.php">Text-Version</a> | <a href="https://github.com/CiteIt/citeit-sample-code">GitHub Download</a>
	</div>


</body>
</html>