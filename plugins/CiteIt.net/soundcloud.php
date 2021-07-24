<?php
/*
 * Credit: https://stackoverflow.com/questions/20870270/how-to-get-soundcloud-embed-code-by-soundcloud-com-url
 * by: Henning Outspoken: https://stackoverflow.com/users/4357673/henning-outspoken
 * URL: www.CiteIt.net/wp-content/plugins/CiteIt/soundcloud.php
 */

//Get the SoundCloud URL
$url= $_GET["url"];
if (!(isset($_GET["url"]))){
  $url="https://soundcloud.com/the-bugle/bugle-4121#t=5:0s";
}

//Get the JSON data of song details with embed code from SoundCloud oEmbed
$getValues=file_get_contents('http://soundcloud.com/oembed?format=js&url='.$url.'&iframe=true');
//Clean the Json to decode
$decodeiFrame=substr($getValues, 1, -2);
//json decode to convert it as an array
$jsonObj = json_decode($decodeiFrame);

//Change the height of the embed player if you want else uncomment below line
// echo $jsonObj->html;
//Print the embed player to the page
echo str_replace('height="400"', 'height="140"', $jsonObj->html);
?>