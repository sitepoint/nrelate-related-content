=== nrelate Related Content ===
Contributors: nrelate, slipfire, sbruner
Tags: related posts, related content, related, feeds, feed, rss, page, pages, post, posts, thumbnails, nrelate
Tested up to: 3.1
Requires at least: 2.9
Stable tag: 0.47.1


The best way to display related content: Thumbnails or Text, on all your pages.

== Description ==
The best way to display related content from your site, and/or your blogroll.

nrelate is not just another related posts plugin. Our patent-pending technology continuously analyzes your website content and displays other related posts from your website.  This ultimately leads to higher page-views for your site, and a better user experience for your visitors.

Installing this plugin is as simple as activating it, and you can leave the rest to nrelate.  Once activated, the nrelate servers will immediately begin analyzing your website content and associating similar articles.  Of course, we provide an options page so you can fine tune the display.

There are four ways to display related content:<br>
1. Automatically display before or after each post.<br>
2. Use the [nrelate-related] shortcode in your post.<br>
3. Use our widget in any widget area in your theme<br>
4. Place the nrelate_related() function in your theme files.<br>

And two display styles:<br>
1. Thumbnails<br>
2. Text<br>
<a href="http://wordpress.org/extend/plugins/nrelate-related-content/screenshots/">Check out the screenshots.</a>

Advertising is also possible with the plugin. Ads come with the same display options as the related content and are a great way to earn a little extra income from your blog.

Because all of the processing and analyzing runs on our servers and not yours, nrelate doesn't cause any additional load on your hosting account (especially if you're using shared hosting).

<a href="http://www.nrelate.com" title="nrelate home page">You can learn more about nrelate here</a>.


== Installation ==

1. Activate the nrelate Related posts plugin
2. Head on over to the nrelate settings page and adjust your settings.
3. Sit back and relax... nrelate is analyzing your content and will display related content within two hours.

**AUTO PLACEMENT**<br>
nrelate can automatically place our related content at the "Top of your Post" or the "Bottom of your Post"... or both. Just check the appropriate box on our settings page.

**WIDGET**<br>
Drag our widget to any widget area in your theme, to automatically display related content.

**TEMPLATE TAG**<br>
If you don't want to have our plugin automatically show our related content, you can use the nrelate_related template tag to place it anywhere in your theme. For example, if you want our related content to show in the sidebar of your site, you may want to place the template tag in your sidebar.php file.
It's best practice to use code like this:<br>
<em>&lt;?php if (function_exists('nrelate_related')) nrelate_related(); ?&gt;</em><br>

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
The nrelate Related Content plugin analyzes your website content, and returns a list of posts that are related to the current story being viewed by your visitor.  If you like, you can also include posts from the websites in your blogroll.

= What makes nrelate different from all the other related content services? =
nrelate started because we believe we can do a better job than the other services out there.  Our patent-pending technology is continuously being improved, and the results are better than the competition.  We're sure you'll be happy with the results... but if you're not, removing nrelate from your website is as easy as deactivating the plugin.

= Does this plugin slow down my website? =
Absolutely not.  Since the nrelate servers are doing all the hard work, your website can focus on what it does best... show content. In fact, if you switch to nrelate from a local related content plugin like YARPP, you may actually see a speed improvement on your site.

= What are my display choices? =
You can show related content as cool image thumbnails (choose from six image sizes), or simple text with bullets. When choosing thumbnails we will look in your post to find the first image and use that. You can also choose a default image to show when your post contains none.  In the plugin options page you can enter your default image url. If your post has no image, and you have not set a default, we will show a random one from our image library.<br>
<a href="http://wordpress.org/extend/plugins/nrelate-related-content/screenshots/">Check out the screenshots.</a>

= Is advertising optional? =
Yes, you always have the option to display or not display ads.

= What ad display options do you offer? =
If you sign up for advertising, you will be able to display up to ten advertisements within the plugin. If you have selected the thumbnail view, then thumbnails will show up. If you have selected text links, then text ads will show up. You can show ads either at the front, end, or mixed within your content links.

= Does nrelate offer a revenue share on ads? =
Yes, its your blog, you should be making money on it!

= Where do I sign up for ads? =
After installing the plugin, you can <a href="http://nrelate.com/partners/content-publishers/sign-up-for-advertising/">sign up for advertising here.</a>

= Will it look like the rest of my site? =
Many of your website styles will automatically be used by the plugin so it will blend in nicely with your website.  We do need to set some of our own styles to make it work properly. However, you can makes changes to our styles by including your own CSS in your stylesheet.

= I just activated the plugin and I don't see anything, what's up? =
Once you activate the plugin, the nrelate server will start analyzing your website.  Related content should show up within two hours.

= Can I use your plugin with WordPress Multisite? =
Absolutely. You must activate our plugin on each individual website in your Multi-site install. You cannot use "Network Activate".

= Does nrelate work with caching plugins like WP-Super-Cache and W3-Total-Cache? =
Caching plugins create static html files from each of your pages... like a moment-in-time snapshot. If a static page was already created before you installed the nrelate plugin then that page will not contain the necessary nrelate code.  There are two ways to fix this:<br>
1. Delete your cache: Both WP-Super-Cache and W3-Total-Cache have a button that allows you to delete your cache. Once deleted our code will show up, while your caching plugins rebuild their static files.<br>
2. Wait until your cache expires: Both plugins expire your static pages after a designated time. Once that page expires, our code will show up.<br>

= Does plugin support external images, e.g. uploaded on Flickr? =
Absolutely! If you have images in your post, nrelate will find them and auto-create thumbnails.

= Does nrelate work with WordPress "Post Thumbnails"? =
Yes, our plugin automatically detects if you are using post thumbnails.

= Does nrelate work if I use custom fields for my images? =
Yes. Just go to our settings page, and fill in the name of the custom field you use.

= How does the nrelate plugin get my website content? =
Our plugin creates an additional nrelate specific RSS feed.  We use this feed so that we don't run into issues if your regular RSS feed is set to "Summary" or if you use a service like Feedburner.

= What is in the nrelate specific RSS feed and how is it used? =
The nrelate specific RSS feed is exactly the same content that is in your RSS feed if you set it to full feed.  Since we had some users that had their feed to just show a summary and others that used Feedburner, we set this up.  The nrelate specific feed can only be accessed by using a random key that is generated upon install.  To make sure this feed is not used for other purposes, we hired WordPress lead developer and security expert, Mark Jaquith, to build it for us.

= My website is not in English, will nrelate work? =
Our plugin will work on websites in the following languages: Dutch, English, French, German, Indonesian, Italian, Polish, Portuguese, Russian, Spanish, Swedish and Turkish.  If you do not see your language on the list or you think that we could improve the relevancy of our plugin in your language, please <a href="http://nrelate.com/forum/">contact us</a> and we will work with you to configure the plugin accordingly.

== Screenshots ==

1. Related thumbnails
2. Hovering on a related thumbnail
3. Text list
4. Advertising mixed into related content
5. Hovering on an advertisement


== Changelog ==

= 0.47.1 =
* Bug fixes.

= 0.47.0 =
* Now you can show related content on your Home Page, Single Post, Search and any Archive page!
* Updated some of our Javascript to work in WordPress 3.2.
* Bug fixes.

= 0.46.1 =
* Fixed upgrade function to keep old settings.
* Reindex button is disabled, while we reindex.
* WordPress version 2.9 added to system check.
* PHP 5 added to system check.

= 0.46.0 =
* New Style gallery!
* No CSS option - create your own style!
* Support for Thesis post image.
* WP 2.9 support: Style gallery tabs.
* WP 2.9 support: Exclude categories fix.

= 0.45.1 =
* Fixed small bug with nrelate specific feed.
* Added support for more image types.
* New debug parameter.

= 0.45.0 =
* Allow excluding of categories.
* Re-index button.
* Support for Press75 custom thumbnails.
* We now attempt to find any custom field images even if none are set.
* Image panel height is now determined by row.
* Fixed contextual_help function to work in WP 2.9.
* Added some style to the settings page.

= 0.44.1 =
* Fix for WordPress pre-3.0

= 0.44.0 =
* New ad options
* Reset all standard WordPress RSS filters in nrelate specific feed.
* Add Debug information in dropdown help menu.

= 0.42.7 =
* Optimized plugin code.
* Fix for Thesis theme.
* Fixed bug with partners output in Chrome.
* Link to nrelate Facebook page in admin (so you can "like" us).

= 0.42.6 =
* Removed our related content from single attachment pages.
* Filter out Javascript from the nrelate custom feed.
* Fixed Communication setting bug.
* Auto move ad validate option from related settings to admin settings.
* Optimized code to only load admin specific functions when is_admin.
* Updated Javascript from height to innerHeight.
* Include link to css stylesheet for reference.

= 0.42.5 =
* Fixes issue where themes use get_the_excerpt to create a meta description, and our plugin filters it.
* Leverage jquery for optimized loading.

= 0.42.4 =
* Convert all named entities into numbered entities in nrelate custom feed.
* System check for curl.
* Help videos have been included on settings page.

= 0.42.3 =
* Moved key generation to plugin activation hook.
* Updated the_content filter code.

= 0.42.2 =
* Fixed issue so plugins now snap to admin dashboard properly.
* Fixed layout thumbnails on settings page so they float (better for smaller screens).
* Custom Field for Images is now an Admin setting.
* Added additional dashboard messages.
* Rewrote dashboard code.

= 0.42.1 =
* Fix for inclusion of IE6 CSS.
* Changed the way we load javascript by attaching to the header.

= 0.42.0 =
* Internal build. Never released as WordPress plugin.

= 0.41.3 =
* Show full content in nrelate unique RSS feed even if "more" tag is present.
* Plugin should now be fully translatable.

= 0.41.2 =
* Internal build. Never released as WordPress plugin.

= 0.41.1 =
* Removed more filters in nrelate specific feed.

= 0.41.0 =
* Allow for multiple thumbnail sizes.
* Support for Post Thumbnails.
* Support for images in custom fields.
* Added related content widget.
* Fixed formatting for push notifications from server to plugin dashboard.
* Add settings links on the main WordPress plugin page, so users can find plugins settings easier.

= 0.40.4 =
* Removed more filters in nrelate specific feed.

= 0.40.3 =
* Fixed issue where seo plugins inadvertently manipulated the RSS content title in the nrelate specific feed.
* Fixed broken link on related settings page.

= 0.40.2 =
* Fixed issue where other plugins inadvertently manipulated RSS content in the nrelate specific feed.

= 0.40.1 =
* New dashboard notice for default thumbnail.
* Better instructions for Layout Options on admin page.

= 0.40.0 =
* Fix for feedburner and when feed is set to summary.
* Create custom rss feed for nrelate only.
* Update dashboard messages.

= 0.3 =
* Send plugin version to nrelate server for debugging purposes.

= 0.2.2 =
* Ping service enabled to notify nrelate of new content.
* Menu settings explained better.
* Improved communication with nrelate server.
* nrelate server can now push message to nrelate plugin dashboard.
* Preview now uses current websites recent posts.
* "nrelate needs help" message has been removed.

= 0.21 =
* nrelate_related() theme function works without echo.

= 0.2 =
* Major update.
* Rewrote 50% of plugin.
* Better communication with nrelate server.
* Default thumbnail.
* Added advertising opportunities.
* Preview button.

= 0.1 =
* Initial release.

== Upgrade Notice ==

= 0.47.0 =
You can now show related content on ALL PAGES!

= 0.46.0 =
NEW STYLE GALLERY - choose from additional styles.

= 0.42.5 =
MAJOR SPEED ENHANCEMENT

= 0.41.0 =
MAJOR FEATURE ENHANCEMENT: choose from multiple thumbnail sizes, Post thumbnails and custom field images are now supported.

= 0.40.2 =
IMPORTANT UPGRADE: This version addresses issues where certain plugins inadvertently manipulated RSS content in the nrelate specific feed.

= 0.40.0 =
IMPORTANT UPGRADE: This version will allow nrelate to index your data better than ever.