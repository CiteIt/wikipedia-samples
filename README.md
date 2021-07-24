# wikipedia-samples

8 Sample Wikipedia articles used as a demo of CiteIt functionality:
  * Hamlet
  * Hillary Clinton
  * Inauguration of JFK
  * Manned Orbital Laboratory
  * Pride & Prejudice
  * Ruth Bader Ginsburg
  * Syphilis
  * Donald Trump

The head of the HTML articles includes references to the code CiteIt adds to the files:



  <!-- ############################### Begin: CiteIt Dependencies ##################################
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

  <!-- ############################### End: CiteIt.net Dependencies #######################################-->


These files are loaded from remote sources but the code files have also been included in this repository for convenience.


The citations themselves are marked up with <q cite="URL">quote text</q> syntax.

Here is a quote from the Ruth Bader Ginsburg article:
"<q cite="https://www.washingtonpost.com/wp-dyn/content/article/2007/08/23/AR2007082300903_pf.html">We won't settle for tokens</q>,"

Read more about Sample Code:
  * https://pages.citeit.net/sample-code/examples.html