/**
 * Created by Dylan on 16/08/2015.
 */


// FAVICON STUFF (which doesn't work unfortunately)
/*

 jQuery.fn.favicons = function (conf) {
 var config = jQuery.extend({
 insert:        'appendTo',
 defaultIco: 'favicon.png'
 }, conf);

 return this.each(function () {
 jQuery('a[href^="http://"]', this).each(function () {
 var link        = jQuery(this);
 var faviconURL    = link.attr('href').replace(/^(http:\/\/[^\/]+).*$/, '$1') + '/favicon.ico';
 var faviconIMG    = jQuery('<img src="' + config.defaultIco + '" alt="" />')[config.insert](link);
 var extImg        = new Image();

 extImg.src = faviconURL;

 if (extImg.complete) {
 alert("complete");
 faviconIMG.attr('src', faviconURL);
 }
 else {
 extImg.onload = function () {
 faviconIMG.attr('src', faviconURL);
 };
 }
 });
 });
 };

 function getIcons()
 {
 console.log("Getting icons <3");
 jQuery('#oldlinks').favicons({insert: 'insertBefore'});

 }
 */
/*