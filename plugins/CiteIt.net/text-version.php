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

	<form action="http://api.citeit.net/v0.4/url/text-version" method="GET" target="_blank">
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
		<li><a href="https://demo.citeit.net/wp-admin/post-new.php">Create demo post</a></li>
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
			<li><a rel="nofollow" href="http://api.citeit.net/v0.4/url/?url=https://www.citeit.net/sample-code/examples.html">http://api.citeit.net/v0.4/url/?url=https://www.citeit.net/sample-code/examples.html</a>
			</li>
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


	<div id="list_citations">
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
		<a href="https://www.citeit.net/sample-code/">New Submission</a> | <a href="examples.html">Examples</a> | <b>Text-Version</b> | <a href="https://github.com/CiteIt/citeit-sample-code">GitHub Download</a>
	</div>


</body>
</html>