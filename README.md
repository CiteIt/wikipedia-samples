# Wikipedia-samples

## 8 Sample Wikipedia articles used as a demo of CiteIt functionality:
  * Hamlet
  * Hillary Clinton
  * Inauguration of JFK
  * Manned Orbital Laboratory
  * Pride & Prejudice
  * Ruth Bader Ginsburg
  * Syphilis
  * Donald Trump

CiteIt-style contextual citations can be added to a website by adding 
  * javascript code libraries and css style sheets to the head
  * javascript code to the footer to call the custom jQuery function

### Header
```
  <!-- ############################### Header Begin: CiteIt Dependencies ##################################
      - jQuery: manipulate Dom: 
      - query api.CiteIt.net
      - download JSON to hidden citeit_container div
      - add arrows and popup links to q tags and blockquotes

  ------ 1) jQuery -->
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
  <script src='https://pages.citeit.net/wp-content/plugins/CiteIt.net/lib/forge-sha256/build/forge-sha256.min.js' defer></script>


  <!-- 5) jsVideoUrlParser: Detect Domain: Determine if an Embed code can be use: YouTube, Vimeo, Soundcloud -->
  <script src='https://pages.citeit.net/wp-content/plugins/CiteIt.net/lib/jsVideoUrlParser/dist/jsVideoUrlParser.min.js' defer></script>

  <!--CSS Styles: CiteIt & JQuery Popup Window -->
  <link rel='stylesheet' href='https://pages.citeit.net/wp-content/plugins/Ci 	teIt.net/lib/jquery-ui-1.12.1/jquery-ui.min.css' type='text/css' media='all' />
  <link rel='stylesheet' href='https://pages.citeit.net/sample-code/css/quote-context-style.css' type='text/css' media='all' />

  <link rel='stylesheet' href='/css/citeit-wikipedia.css' type='text/css' media='all' />

  <!-- 6) Main CiteIt Javascript Code: Download JSON & Create Popup windows and Expanding Arrows  -->
  <script src='https://pages.citeit.net/wp-content/plugins/CiteIt.net/js/versions/0.4/0.4.9-CiteIt-quote-context.js'> </script>

  <!-- ############################### Header End: CiteIt.net Dependencies #######################################-->
```

### Footer

```
<script>
  // Call CiteIt.net plugin on all q-tags and blockquotes:
  jQuery(document).ready(function(){

      jQuery('q, blockquote').quoteContext();

  });
</script>
```

These files are loaded from remote sources but copies of the code files have also been included in this repository for convenience.



## Quote Markup Syntax

### HTML
The citations themselves are marked up with **<q cite="URL">quote text</q>** syntax.

### Examples
Here is a quote from the Ruth Bader Ginsburg article:
"<q cite="https://www.washingtonpost.com/wp-dyn/content/article/2007/08/23/AR2007082300903_pf.html">We won't settle for tokens</q>,"


### Wiki Markup
You can read more about proposed wiki syntax:
https://meta.wikimedia.org/wiki/User:Timlangeman/sandbox#Proposed_Syntax_for_MediaWiki_contextual_citations.  

I'm interested in hearing your feedback.

### Read more about Sample Code:
  * https://pages.citeit.net/sample-code/examples.html
  

## Generating Contextual JSON files
To view the contextual citations:
  * upload the articles to a website you control.
  * generate the contextual JSON files by instructing the webservice to index your website.
  

### Webservice: Online 
Right now the [https://pages.citeit.net/sample-code/|webservice] is alpha-status code and not optimized for performace.
Start by calling the service for short articles like JFK, RGB, or Pride and Prejudice so that the request doesn't take too long.

### Webservice: Run Locally for many citations
(The Donald Trump page is too long for running on the public webservice but can be run if you setup the Python code on your own computer).
