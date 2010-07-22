=== nrelate Related Content ===
Contributors: nrelate, slipfire, sbruner
Tags: related posts, related, feeds, feed, rss, page, pages, Post, posts, nrelate
Tested up to: 3.0
Requires at least: 2.9
Stable tag: 0.2


The easiest way to display related content from your site

== Description ==
The easiest way to display related content from your site

nrelate is not just another related posts plugin. Our patent-pending technology continously analyzes your website content and displays other related posts from your website.  This ultimately leads to higher page-views for your site, and a better user experience for your visitors.

Installing this plugin is as simple as activating it, and you can leave the rest to nrelate.  Once activated, the nrelate servers will immediately begin analyzing your website content and associating similar articles.  Of course, we provide an options page so you can fine tune the display.

There are three ways to display related content:<br>
1. Automatically display before or after each post.<br>
2. Use the [nrelate-related] shortcode in your post.<br>
3. Place the nrelate_related() function in your theme files.<br>

Because all of the processing and analyzing runs on our servers and not yours, nrelate doesn't cause any additional load on your hosting account.

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

= Can I show cool thumbnails? =
Absolutely!  You can show thumbnails or just text if you like.  If you choose the thumbnail option and the plugin does not find an image in your post, we will automatically choose a random image.  You can also choose a default image to show in the plugin options page by entering the url of the image.  It's best to set a 110px square image as the default, but if it's larger, nrelate will auto crop and scale it.

= Will it look like the rest of my site? =
Many of your website styles will automatically be used by the plugin so it will blend in nicely with your website.  We do need to set some of our own styles to make it work properly.

= I just activated the plugin and I don't see anything, what's up? =
Once you activate the plugin, the nrelate server will start analyzing your website.  Related content should show up within two hours.  

== Screenshots ==

1. Related thumbnails
2. Hovering on a related thumbnail
3. Text list


== Changelog ==

= 0.2 =
* Major update
* Rewrote 50% of plugin
* Better communication with nrelate server
* Default thumbnail
* Added advertising opportunities
* Preview button

= 0.1 =
* Initial release