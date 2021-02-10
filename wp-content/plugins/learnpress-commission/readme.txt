=== Commission addon for LearnPress ===
Contributors: thimpress, phonglq
Donate link:
Tags: lms, commission, fee, learnpress, elearning, e-learning, learning management system, education, course, courses, quiz, quizzes, questions, training, guru, sell courses
Requires at least: 3.8
Tested up to: 5.5.2
Stable tag: trunk
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

== Description ==

This plugin provide a Commission Management system for LearnPress.

== Feature ==

- Management percent commission for each courses.
- Management withdrawal
- Pay for withdraw request via PayPal API
- Pay for withdraw request via Offline method

== Installation ==

1. Download Commission Plugin to your desktop.
2. If downloaded as a zip archive, extract the Plugin folder to your desktop.
3. With your FTP program, upload the Plugin folder to the wp-content/plugins folder in your WordPress directory online.
4. Go to Plugins screen and find the newly uploaded Plugin in the list.
5. Click Activate to activate it.

== Configuration ==

Go to the "Setting Page" of LearnPress by click left menu "Settings/LearnPress". Click the "Commissions" tab.

= General Settings =
1. Enable Commission feature for LearnPress
	Mark "Enable commission feature" checkbox as checked to enable Commission feature.
2. Set default percent commission that author of courses can receive by fill the "Commission percent" field.
3. Set minimum percent commission that author of courses can receive by fill the "Min ($)" field.
4. Enable/disable Offline payment

= Management Commission for each source =
For configuration percent for each course, in Commission settings page click "Managing" link.

= Withdrawal Paypal Setting =
To pay for withdraw request of author via paypal API you need have a Rest API Paypal Applicaiton.
If you have not Rest API app, you can create nonce at https://developer.paypal.com/developer/applications/

== Changelog ==

= 3.0.5 =
+ Fix check isset variable

= 3.0.4 =
+ Removed Offline payment option in Settings.
+ Fixed tiny issue with Paypal response.

= 3.0.3 =
+ Fix bug of can not translate 'Your commission' text.

= 3.0.2 =
+ Updated language file.

= 3.0.1 =
+ Fixed bug caculate wrong total commission for instructor

= 3.0.0 =
+ Compatible with Learnpress 3.0.0

= 2.0.4 =
+ Update managing view

= 2.0.3 =
+ Fixed bug: all user can see tab Withdrawals
+ Add template for form withdrawals to paypal

= 2.0 =
+ Changed text domain to learnpress

= 1.0.0 =
+ The first release.
