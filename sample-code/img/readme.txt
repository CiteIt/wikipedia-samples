=== CiteIt.net ===
Contributors: Tim Langeman
Requires at least: 4.0
Stable tag: 0.4.3
Tested up to: 5.4.2

This plugin looks up the context of quotes made using the "blockquote" and "q" tag.

== Description ==

This plugin allows writers to provide greater context to quotations they quote using a "cite" attributed url.  The plugin passes the quote on to the api.CiteIt.net web service, which calculates the text before and after each quote.  The API publishes the results to read.CiteIt.net as a JSON file. The contextual information in the JSON file is displayed through a jQuery dialog popup or though hidden text, which becomes visible when a reader clicks on the injected blue arrows.

This plugin was designed before WordPress 5 introduced the new Gutenberg graphical editor.
If users wish to use the graphical editor so that they don't have to edit html, they will have to use the old Classic Editor.
A new Gutenberg block plugin is desired.  For now, Gutenberg users will have to edit the html block "cite" attributes manually.