=== mailchimp food-cook subscribe ===
Contributors: (kengimel)
Tags: newsletter, food-cook, recipe plugin, mailchimp
Requires at least: 3.0.1
Tested up to: 4.2.2
Stable tag: 1
License: MIT License
License URI: http://opensource.org/licenses/MIT

This is a plugin to allow easy setup and customization of a website's newsletter subscription widget and modal popup. It is best used with food and cook recipe theme made with woo themes.

== Description ==
This plugin provides a modal popup to convert visitors to your food-cook website into subscribers and in effect to take action like product discount or coupon etc.

This plugin is an extract from [globalfoodbook.com](http://globalfoodbook.com).

This plugin will work only on (woothemes enabled) websites that have setup mailchimp connect url, See food-cook docs and [this](http://kb.mailchimp.com/lists/managing-subscribers/find-your-list-id)

For more details. This plugin is built to help other [food-cook](http://themeforest.net/item/food-cook-multipurpose-food-recipe-wp-theme/4915630) site owners (from the support group) who require this utility.

It is implemented to allow easy setup and customization of a website's newsletter subscription widget and modal popup. It is best used with food and cook recipe theme made with woo themes.


== Installation ==

1. Upload /mailchimp-woothemes-subscribe to the /wp-content/plugins directory
2. Activate the plugin through the Plugins menu in WordPress

== Frequently Asked Questions ==

= Is the button text changeable? =

 Yes.

= Is the button color changeable? =

 Yes.

= Can I add an Image? =

 Yes. only the urls can be accepted.

== Screenshots ==

== Changelog ==

= 1.0 =
* Initial Release

== Upgrade Notice ==

= 1 =
* Initial Release

== Arbitrary section ==

= Contributing =

1. Fork it ( https://github.com/[my-github-username]/mailchimp-foodcook-subscribe )
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Add some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create a new Pull Request

= Pushing plugin to wordpress svn repo =

1. Clone this repo

          `git clone git@github.com:globalfoodbook/mailchimp-foodcook-subscribe.git`

2. cd path/to/mailchimp-foodcook-subscribe
3. vim .git/config
4. Add the code below:

          [svn-remote "svn"]
                  url = http://plugins.svn.wordpress.org/[plugin_name]/trunk
                  fetch = :refs/remotes/git-svn

5. Then merge the master into the new branch:

          `git svn fetch svn`
          `git checkout -b svn git-svn`
          `git merge master`
          `git svn dcommit`

6. Then rebase that branch to the master, and you can dcommit from the master to svn

          `git checkout master`
          `git rebase svn`
          `git branch -d svn`
          `git svn dcommit`
