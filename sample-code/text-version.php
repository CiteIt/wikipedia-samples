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

  <!-- Sample Code: Inline Style -->
  <style>

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
		width: 55%;
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

<h3 class="breadcrumb"><a href="https://pages.citeit.net/code/">Code</a> &gt; <a href="https://pages.citeit.net/sample-code/">Sample Code</a> : Text Version</h3>


	<form action="https://api.citeit.net/v0.4/url/text-version" method="GET" target="_blank">
	  <input class="url"
	  	type="url"
		name="url"
	  	onfocus="if (this.value=='https://') this.value = 'https://'"
	  	value="<?php print($_POST['url'] ? $_POST['url']: 'https://' ); ?>"
	  >
	  <select name="line_separator">
		<option value="">wrap lines</option>
		<option value="\n">break new lines</option>
	  </select>	

	  <input class="submit" type="submit" value="create text-version" />
	</form>

	<h3>Create a text-version of a webpage</h3>

    <ul>
        <li>Submit the URL of a webpage you would like to convert from <b>html to text</b></li>
    </ul>

	<br /><br />

	<h3>Links:</h3>
	<ul>
		<li><a href="https://demo.citeit.net/">Create demo post</a></li>
	</ul>


	<h3>What this does:</h3>

    <p>
       This will <b>convert the html</b> of a webpage to a <b>text</b>-only version.
	</p>
<ul>
  <li>It will also generate a transcript for <b>YouTube videos</b> if you enter the URL of a video that contains a transcript.</li>
  <li>I've also added <b>PDF support</b> to the native Python version of the service but 
	  I have not yet gotten PDF support configured as part of the <b>Docker image</b> (which this website uses).
  </li>
</ul>

	<!---------------------- Display API Methods ----------------------->
	<h3>API Examples</h3>
	<ul>
		<li>Submit a URL for Indexing: (POST-preferred)
			<ul>
			<li><a rel="nofollow" href="https://api.citeit.net/v0.4/url/?url=https://pages.citeit.net/sample-code/examples.html">https://api.citeit.net/v0.4/url/?url=https://pages.citeit.net/sample-code/examples.html</a>
			</li>
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

	<!---------------------- End: Display API Methods ----------------------->


	<div id="list_citations">
	<div id="footer">
		<p><b>What:</b> <a href="https://pages.citeit.net/">CiteIt.net</a> is a citation tool that 
			<b>enables web authors to demonstrate the context</b> of their citations.
		</p>
		<p><b>Who:</b> CiteIt.net allows journalists, academics and web authors who want to set a higher standard of discourse.</p>
		<p><b>How:</b> CiteIt.net is an <a href="https://pages.citeit.net/code/">open source program</a> which 
			can be added to a website with a WordPress plugin or a bit of custom code.
		</p>
	</div>

</div><!--wrap-->

	<div id="navigation">
		<a href="https://pages.citeit.net/sample-code/">New Submission</a> | <a href="examples.html">Examples</a> | <b>Text-Version</b> | <a href="https://meta.wikimedia.org/wiki/User:Timlangeman/sandbox">Wikipedia Proposal</a> | <a href="https://github.com/CiteIt/citeit-sample-code">GitHub Download</a>
	</div>


</body>
</html>
