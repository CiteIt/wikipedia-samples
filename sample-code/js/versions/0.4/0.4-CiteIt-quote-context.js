/*
 * Quote-Context JS Library
 * https://github.com/CiteIt/citeit-jquery
 *
 * Copyright 2015-2020, Tim Langeman
 * http://www.openpolitics.com/tim
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 *
 * This is a jQuery function that locates all "blockquote" and "q" tags
 * within an html document and calls the CiteIt.net web service to
 * locate contextual info about the requested quote.
 *
 * The CiteIt.net web service returns a json dictionary and this script
 * injects the returned contextual data into hidden html elements to be
 * displayed when the user hovers over or clicks on the cited quote.
 *
 * Demo: http://www.CiteIt.net
 *
 * Dependencies:
 *  - jQuery: https://jquery.com/
 *  - Sha256: https://github.com/brillout/forge-sha256/
 *  - jsVideoUrlParser: https://www.npmjs.com/package/js-video-url-parser
 *
*/
var popup_library = "jQuery";

// div in footer than holds injected json data, requires css class to hide
var hidden_container = "citeit_container";
//var jQuery.curCSS = "jQuery.css";
var version_num = "0.4";

// Remove anchor from URL
var current_page_url = window.location.href.split("#")[0];

jQuery.fn.quoteContext = function() {
  // Add "before" and "after" sections to quote excerpts
  // Designed to work for "blockquote" and "q" tags

  //Setup hidden div to store all quote metadata
  jQuery(this).each(function(){
    // Loop through all the submitted tags (blockquote or q tags) to
    // see if any have a "cite" attribute
    if( jQuery(this).attr("cite") ){
      var blockcite = jQuery(this);
      var cited_url = blockcite.attr("cite");
      var citing_url = current_page_url;
      var citing_quote = blockcite.text();
      var embed_icon = "";
      var embed_html = "";

      // Test the URL to see if it is one of the Supported Media Providers:
      var media_providers = ["youtube","vimeo","soundcloud"];
      var url_provider = "";
      console.log("cited_url: " + cited_url);

      var url_parsed = urlParser.parse(cited_url);
      if (!(typeof url_parsed === 'undefined')) {
        if (url_parsed.hasOwnProperty("provider")){
          url_provider = url_parsed.provider;
        }
      }
      if (url_provider == "youtube"){
	// Generate YouTube Embed URL
	var embed_url = urlParser.create({
	   videoInfo: {
             provider: url_provider,
             id: url_parsed.id,
             mediaType: 'video'
	   },
           format: 'long',
           params: {
             start: url_parsed.params.start
	   }
	});

        // Create Embed iframe 
        embed_icon = "<br /><span class='view_on_youtube'>" +
                     "Expand: Show Video Clip</span>";
        embed_html = "<iframe class='youtube' src='" + embed_url +
                         "' width='560' height='315' " +
                         "frameborder='0' allowfullscreen='allowfullscreen'>" +
                     "</iframe>";
      }
      else if (url_provider == "vimeo") {
        // Create Canonical Embed URL:
        embed_url = "https://player.vimeo.com/video/" + url_parsed.id;
        embed_icon = "<br ><span class='view_on_youtube'>" +
                         "Expand: Show Video Clip</span>";
        embed_html = "<iframe class='youtube' src='" + embed_url +
                         "' width='640' height='360' " +
                         "frameborder='0' allowfullscreen='allowfullscreen'>" +
                     "</iframe>";
      }
      else if (url_provider == "soundcloud") {
        // Webservice Query: Get Embed Code
        $.getJSON('http://soundcloud.com/oembed?callback=?',
        { format: 'js', 
          url: cited_url, 
          iframe: true
        },
        function(data) {
           var embed_html = data.html;
        });

        embed_icon = "<br ><span class='view_on_youtube'>" +
                         "Expand: Show SoundCloud Clip</span>";
      }

      // If they have a cite tag, check to see if its hash is already saved
      if (cited_url.length > 3){
        var tag_type = jQuery(this)[0].tagName.toLowerCase();
        var hash_key = quote_hash_key(citing_quote, citing_url, cited_url);
	console.log(hash_key);
	var hash_value = forge_sha256(hash_key);
	console.log(hash_value);      
        var shard = hash_value.substring(0,2);
        var read_base = "https://read.citeit.net/quote/";
        var read_url = read_base.concat("sha256/", version_num, "/",
                       shard, "/", hash_value, ".json");
        var json = null;

        //See if a json summary of this quote was already created
        // and uploaded to the content delivery network
          jQuery.ajax({
              type: "GET",
          url: read_url,
              dataType: "json",
              success: function(json) {
                  add_quote_to_dom(tag_type, json );
            console.log("CiteIt Found: " + read_url);
            console.log("       Quote: " + citing_quote);
              },
              error: function() {
            console.log("CiteIt Missed: " + read_url);
            console.log("       Quote: " + citing_quote);
              }
          });


	// Add Hidden div with context to DOM
        function add_quote_to_dom(tag_type, json ) {
          if ( tag_type == "q"){
            var q_id = "hidden_" + json.sha256;

            //Add content to a hidden div, so that the popup can later grab it
            jQuery("#" + hidden_container).append(
              "<div id='" + q_id + "' class='highslide-maincontent'>.. " +
                json.cited_context_before + " " + " <strong>" +
                json.citing_quote + "</strong> " +
                json.cited_context_after + ".. </p>" +
                "<p><a href='" + json.cited_url +
                "' target='_blank'>Read more</a> | " +
                "<a href='javascript:close_popup(" +
                q_id + ");'>Close</a> </p></div>");

            //Style quote as a link that calls the popup expander:
            blockcite.wrapInner("<a href='" + blockcite.attr("cite") + "' " +
              "onclick='return expand_popup(this ,\"" + q_id +"\")' " +
             " />");
          }
          else if (tag_type == "blockquote"){

            //Fill "before" and "after" divs and then quickly hide them
            blockcite.before("<div id='quote_before_" + json.sha256 +
              "' class='quote_context'>" +
              "<blockquote class='quote_context'>" +
                  embed_html + "</a><br />.. " +
                  json.cited_context_before +
              " .. </blockquote></div>");

            blockcite.after("<div id='quote_after_" + json.sha256 +
              "' class='quote_context'>" +
              "<blockquote class='quote_context'>" +
                json.cited_context_after + " ..</blockquote></div>" +
              "<div class='citeit_source'>" +
              "<span class='citeit_source_label'>source: </span> " +
                "<a class='citeit_source_domain' href='" + json.cited_url +
                "'>" + extractDomain( json.cited_url ) +
              "</a></div>"
            );

            var context_before = jQuery("#quote_before_" + json.sha256);
            var context_after = jQuery("#quote_after_" + json.sha256);

            context_before.hide();
            context_after.hide();

            //Display arrows if content is found
            if (json.cited_context_before.length > 0){

	      context_before.before("<div class='quote_arrows context_up' "+
              "id='context_up_" + json.sha256 + "'> " +
              "<a href=\"javascript:toggle_quote('before', 'quote_before_" +
                json.sha256 + "');\">&#9650;</a> " +
                "<a href=\"javascript:toggle_quote('before', 'quote_before_" +
                json.sha256 + "');\">" +
              embed_icon + "</a></div>");
            }

            if (json.cited_context_after.length > 0){
             context_after.after("<div class='quote_arrows context_down' "+
              "id='context_down_" + json.sha256 +"'> " +
              "<a href=\"javascript:toggle_quote('after', 'quote_after_" +
              json.sha256 +"');\">&#9660;</a></div>"
            );
            }

          } // elseif (tag_type == 'blockquote')
        } // end: function add_quote_to_dom


      } // if url.length is not blank
    }  // if "this" has a "cite" attribute
  });     //   jQuery(this).each(function() { : blockquote, or q tag

};

function toggle_quote(section, id){
  jQuery("#" + id).fadeToggle();
}

function expand_popup(tag, hidden_popup_id){

  // Configure jQuery Popup Library
  jQuery.curCSS = jQuery.css;

  // Setup Initial Dialog box
  jQuery("#" + hidden_popup_id).dialog({
    autoOpen: false,
    closeOnEscape: true,
    closeText: "hide",
    draggableType: true,
    resizable: true,
    width: 70%,
    modal: false,
    title: "powered by CiteIt.net",
    hide: { effect: "size", duration: 400 },
    show: { effect: "scale", duration: 400 },
    });

  // Add centering and other settings
  jQuery("#" + hidden_popup_id).dialog("option",
    "position", { at: "center center", of: tag}
    ).dialog("option", "hide", { effect: "size", duration: 400 }
    ).dialog("option", "show", { effect: "scale", duration: 400 }
    ).dialog( {"title" : "powered by CiteIt.net"}
    ).dialog("open"
    ).blur(
  );

  // Close popup when you click outside of it
  jQuery(document).mouseup(function(e) {
    var popupbox = jQuery(".ui-widget-overlay");
    if (popupbox.has(e.target).length === 0){
      // Uncomment line below to close popup when you click outside it
      //$("#" + hidden_popup_id).dialog("close");
    }
  });

  return false; // Don't follow link
}

function close_popup(hidden_popup_id){
  // assumes jQuery library
  jQuery(hidden_popup_id).dialog("close");
}

function trim_regex(str){
//Source: http://stackoverflow.com/questions/10032024/how-to-remove-leading-and-trailing-white-spaces-from-a-given-html-string
// Credit:  KhanSharp:  (used for backward compatibility.
// trim() was introduced in javascript 1.8.1)
  return str.replace(/^[ ]+|[ ]+$/g,"");
}

function url_without_protocol(url){
  // Remove http(s):// and trailing slash
  // Before: https://www.example.com/blog/first-post/
  // After:  www.example.com/blog/first-post

  var url_without_trailing_slash = url.replace(/\/$/, "");
  var url_without_protocol = url_without_trailing_slash.replace(/^https?\:\/\//i, "");

  return url_without_protocol;
}

function escape_url(str){
  // This is a list of Unicode character points that should be filtered out from the quote hash
  // This list should match the webservice settings: 
  // * https://github.com/CiteIt/citeit-webservice/blob/master/app/settings-default.py
  //   - URL_ESCAPE_CODE_POINTS

  var replace_chars = new Set([
    10, 20, 160
  ]);

  // str = trim_regex(str);   // remove whitespace at beginning and end
  return normalize_text(str, replace_chars);
}

function escape_quote(str){
  // This is a list of Unicode character points that should be filtered out from the quote hash
  // This list should match the webservice settings: 
  // * https://github.com/CiteIt/citeit-webservice/blob/master/app/settings-default.py
  //   - TEXT_ESCAPE_CODE_POINTS

  var replace_chars = new Set([ 
    2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18 
    , 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 39 
    , 96, 160, 173, 699, 700, 701, 702, 703, 712, 713, 714, 715, 716 
    , 717, 718, 719, 732, 733, 750, 757, 8211, 8212, 8213, 8216, 8217 
    , 8219, 8220, 8221, 8226, 8203, 8204, 8205, 65279, 8232, 8233, 133 
    , 5760, 6158, 8192, 8193, 8194, 8195, 8196, 8197, 8198, 8199, 8200 
    , 8201, 8202, 8239, 8287, 8288, 12288 
  ]);

  return normalize_text(str, replace_chars);
}

function normalize_text(str, escape_code_points){
  /* This javascript function performs the same functionality as the
     python method: citeit_quote_context.text_convert.escape()

     It removes an array of symbols from the input str
  */
 
  var str_return = '';                //default: empty string
  var input_code_point = -1;
  var str_array = stringToArray(str); // convert string to array

  for (idx in str_array){
    // Get Unicode Code Point of Current Character
    chr = str_array[idx];
    chr_code = chr.codePointAt(0);
    input_code_point = chr.codePointAt(0); 

    // Only Include this character if it is not in the supplied set of escape_code_points
    if(!(escape_code_points.has(input_code_point))){
      str_return += chr;  // Add this character
    }
  }

  return str_return;
}

function quote_hash_key(citing_quote, citing_url, cited_url){
  var quote_hash =  escape_quote(citing_quote) + "|" +
 		    url_without_protocol(escape_url(citing_url)) + "|" +
		    url_without_protocol(escape_url(cited_url));
	
  return quote_hash;
}

function quote_hash(citing_quote, citing_url, cited_url){
  var url_quote_text = quote_hash_key(citing_quote, citing_url, cited_url);
  var quote_hash = forge_sha256(url_quote_text);  // sha256 library:https://github.com/brillout/forge-sha256 
  return quote_hash;
}

function extractDomain(url) {
    var domain;
    //find & remove protocol (http, ftp, etc.) and get domain
    if (url.indexOf("://") > -1) {
        domain = url.split("/")[2];
    }
    else {
        domain = url.split("/")[0];
    }

    //find & remove port number
    domain = domain.split(":")[0];
    return domain;
}

function stringToArray(s) {
// Credit: https://medium.com/@giltayar/iterating-over-emoji-characters-the-es6-way-f06e4589516
// convert string to Array
  const retVal = [];
	  
  for (const ch of s) {
    retVal.push(ch);
		    }
  return retVal;
}
