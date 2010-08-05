=== nrelate Related Content ===
Contributors: nrelate, slipfire, sbruner
Tags: related posts, related, feeds, feed, rss, page, pages, Post, posts, nrelate
Tested up to: 3.1
Requires at least: 2.9
Stable tag: 0.3


The easiest way to display related content from your site

== Description ==
The easiest way to display related content from your site

nrelate is not just another related posts plugin. Our patent-pending technology continously analyzes your website content and displays other related posts from your website.  This ultimately leads to higher page-views for your site, and a better user experience for your visitors.

Installing this plugin is as simple as activating it, and you can leave the rest to nrelate.  Once activated, the nrelate servers will immediately begin analyzing your website content and associating similar articles.  Of course, we provide an options page so you can fine tune the display.

There are three ways to display related content:<br>
1. Automatically display before or after each post.<br>
2. Use the [nrelate-related] shortcode in your post.<br>
3. Place the nrelate_related() function in your theme files.<br>

And two display styles:<br>
1. Thumbnails<br>
2. Text<br>
<a href="http://wordpress.org/extend/plugins/nrelate-related-content/screenshots/">Check out the screenshots.</a>

Because all of the processing and analyzing runs on our servers and not yours, nrelate doesn't cause any additional load on your hosting account (especially if you're using shared hosting).

<a href="http://www.nrelate.com" title="nrelate home page">You can learn more about nrelate here</a>.


== Installation ==

1. Activate the nrelate Related posts plugin
2. Head on over to the nrelate settings page and adjust your settings.
3. Sit back and relax... nrelate is analyzing your content and will display related content shortly.

**TEMPLATE TAG**<br>
Use the nrelate_related template tag anywhere in your theme to show related content.
It's best practice to use code like this:<br>
<em>if (function_exists('nrelate_related')) nrelate_related();</em>

**SHORTCODE**<br>
You can also use the nrelate-related shortcode to manually place related content into your posts:<br>
1. Create or edit a Post.<br>
2. Wherever you want the related content to show up enter the shortcode: [nrelate-related]<br>

Shortcode Configuration Options:<br>
float = left, right or center<br>
width = any valid CSS value (100%, 50px, etc)<br>

Shortcode Defaults:<br>
float = left<br>
width = 100%<br>

Shortcode Examples:<br>
[nrelate-related] Will use defaults<br>
[nrelate-related float='right']<br>
[nrelate-related width='50%']<br>
[nrelate-related float='right' width='50%']<br>


== Frequently Asked Questions ==

= What does this plugin do? =
The nrelate Related Content plugin analyzes your website content, and returns a list of posts that are related to the current story being viewed by your visitor.

= What makes nrelate different from all the other related content services? =
nrelate started because we believe we can do a better job then the other services out there.  Our patent-pending technology is continously being improved, and the results are better then the competetion.  We're sure you'll be happy with the results... but if you're not, removing nrelate from your website is as easy as deactivating the plugin.

= Will it slow down my website? =
Absolutely not.  Since the nrelate servers are doing all the hard work, your website can focus on what it does best... show content.

= What are my display choices? =
You can show related content as cool image thumbnails, or simple text with bullets. When choosing thumbnails we will look in your post to find the first image and use that. You can also choose a default image to show when your post contains none.  In the plugin options page you can enter your default image url. It's best to set a 110px square image as the default, but if it's larger, nrelate will auto crop and scale it. If your post has no image, and you have not set a default, we will show a random one from our image library.<br>
<a href="http://wordpress.org/extend/plugins/nrelate-related-content/screenshots/">Check out the screenshots.</a>

= Will it look like the rest of my site? =
Many of your website styles will automatically be used by the plugin so it will blend in nicely with your website.  We do need to set some of our own styles to make it work properly. However, you can makes changes to our styles by including your own CSS in your stylesheet.

= I just activated the plugin and I don't see anything, what's up? =
Once you activate the plugin, the nrelate server will start analyzing your website.  Related content should show up within two hours.  

== Screenshots ==

1. Related thumbnails
2. Hovering on a related thumbnail
3. Text list


== Changelog ==

= 0.3 =
* Send plugin version to nrelate server for debugging purposes

= 0.2.2 =
* Ping service enabled to notify nrelate of new content
* Menu settings explained better
* Improved communication with nrelate server
* nrelate server can now push message to nrelate plugin dashboard
* Preview now uses current websites recent posts
* "nrelate needs help" message has been removed

= 0.21 =
* nrelate_related() theme function works without echo

= 0.2 =
* Major update
* Rewrote 50% of plugin
* Better communication with nrelate server
* Default thumbnail
* Added advertising opportunities
* Preview button

= 0.1 =
* Initial release
