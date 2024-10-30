=== ChimpPress ===
Contributors: jealousdesigns
Donate link: http://jealousdesigns.co.uk
Tags: email, mail chimp, campaign, manage, send, mailout, subscribers, drag and drop
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: 0.8.9

ChimpPress is a new way to manage your MailChimp campaigns right from your WordPress Dashboard.

== Description ==

*** THIS IS A BETA RELEASE ***

ChimpPress provides an interface for creating and managing your MailChimp campaigns without having to leave your WordPress dashboard.

It also allows you to utilise MailChimps campaign sending and management tools to send automatic mailouts when ever a post/page/CPT is published.

Current features of ChimpPress

* Create bespoke HTML emails using the intuitive drag and drop editor.
* Add padding and colours to specific cells in your email or to the entire campaign.
* Select from a number of layout templates and then add multiple rows and columns as needed.
* Import/Export as well as quick import of existing campaigns.
* Resize images within the editor by dragging them to the desired size.
* Receive in-depth stats about your campaign.
* Set up a campaign as a "Template" and specify when it should be used to send out notifications when a post/page/CPT is published.
* Manage your subscribers.

All you need is a MailChimp account and an API key and you can start creating bespoke html emails!

== Installation ==

1. Upload the folder `ChimpPress` to the `/wp-content/plugins/` directory keeping the file structure.
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add your API key by in ChimpPress -> Settings.
4. Add your first campaign by clicking on Add Campaign!
5. When setting up a template for an auto mailout you can use '%title%', '%content%' and '%link' in your campaign and these will automatically be replaced with the relevant data for the post/page/CPT being published.

== Frequently Asked Questions ==

= How do I get an API key? =

You can grab your API key by visiting your MailChimp account here https://us2.admin.mailchimp.com/account/api/

= Can I use this to send out an email to my subscribers every time I publish a post/page/CPT? =

You surely can! Simply select the option "Template" in the "Campaign Type" section.

= Can I still receive statistics from campaigns set up as a template? =

Yup! Each time a campaign is sent using a template it's statistics will be added to the template stats section.

= How do I add dynamic data to a template such as title, content and permalink? =

Add any of the following tags to a content widget in your template and it will be replaced with the relevant data.

%title%

%content%

%link%

== Screenshots ==

1. Manage your campaigns
2. Add options and layout preferences
3. More layout options
4. Choose from some basic templates
5. Add some content
6. Add an image
7. Added the image!
8. Apply some colour
9. Preview it!
10. Send it!
11. Get some stats about your campaign
12. The email!

== Changelog ==

= 0.1 =
* First release

= 0.2 =
Fixed some security issues and added a feedback page

= 0.3 =
Fixed 404 error on js files with some WP installations

= 0.5 =
*Fixed error causing editors to show in emails

= 0.6 =
*Added functionality that closes editing windows when opening another

= 0.8.3 =
*Bug fixed and extra alignment options

= 0.8.6 =
*Fixed bug that caused automated emails to loose their line breaks

= 0.8.8 =

*Removed dependancy on highslide charts plugin due to license incompatability and now using flot

= 0.8.9 =

*Fixed bug where subscriber details were blank

== Upgrade Notice ==

= 0.1 =

First release so no update

= 0.2 =

Fixed some security issues and added a feedback page

= 0.3 =

Fixed 404 error on js files with some WP installations

= 0.5 =

Fixed error causing editors to show in emails

= 0.6 =

Added functionality that closes editing windows when opening another

= 0.8.3 =

Bug fixed and extra alignment options

= 0.8.4 =

Fixed bug relating to Outlook 2007 - Outlook 2013 messing up with empty table rows.

= 0.8.5 =

Fixed bug relating to Outlook 2007 - Outlook 2013 that caused resized images to show their original size

= 0.8.6 =

Fixed bug that caused automated emails to loose their line breaks

= 0.8.7 =

Removing old files no longer used by chimppress

= 0.8.8 =

Removing highslide charts plugin due to license incompatablility. Now using flot.

= 0.8.9 =

Fixed bug where subscriber details were blank
