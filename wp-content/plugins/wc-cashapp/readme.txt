=== Checkout with Cash App on WooCommerce ===
Contributors: theafricanboss
Donate Link: https://gurastores.com/get-cash
Tags: cashapp, cash app, finance, payments, money, transfer, receive, send, money transfer, usa, mobile money, cash, momo, woocommerce, ecommerce, stores, market, subscription
Stable tag: 5.3.1
Requires PHP: 5.0
Requires at least: 5.0
Tested up to: 6.3
WC requires at least: 5.0.0
WC tested up to: 8.0.1
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

The #1 finance app in the App Store now available on WordPress. Receive Cash App payments on your website with WooCommerce + Cash App

== Description ==

Checkout with Cash App on WooCommerce is also known as [WooCash, officially listed on Square App Marketplace](https://square.theafricanboss.com/woocash).

**Introducing the full end-to-end Cash App Pay API integration since v4.0**
**Introducing automated order status updates for PRO users: on-hold to processing when you receive the order amount**

= More information =

For more details about this woocommerce extension, **please visit [The African Boss](https://theafricanboss.com/cashapp)**
See available screenshots or the store example of [Gura Stores](https://gurastores.com/test/) for visual details.

= PRO or customized version =

Please reach out to theafricanboss@gmail.com for a custom version of this plugin.
Visit [The African Boss](https://theafricanboss.com/cashapp) for more details

= Demo =

An example of the plugin in use is the following store:

[Gura Stores](https://gurastores.com/test/)

For Square merchants, this plugin integrates the full end-to-end Cash App Pay API integration.

For all other Cash App users, This plugin displays a Cash App link that customers can use to send payments.

See the screenshots or the store example of [Gura Stores](https://gurastores.com/test/) for visual details.

== Screenshots ==

1. Checkout page view for customers using the Cash App Link payment method enabled.
2. Checkout page view for customers using the Cash App Pay payment method enabled.
3. Plugin settings for the Cash App Link payment method and this information will be displayed to your customers
4. Plugin settings for the Cash App Pay payment method
5. Thank you page after placing the order via the Cash App Link payment method

== Installation ==

= From Dashboard ( WordPress admin ) =

- Go to Plugins -> Add New
- Search for ‘Checkout with Cash App’
- Click on Install Now
- Activate the plugin through the “Plugins” menu in WordPress.

= Using cPanel or FTP =

- Download ‘Checkout with Cash App’ from [The African Boss](https://theafricanboss.com/cashapp)
- Unzip wc-cashapp.zip’ file and
- Upload wc-cashapp folder to the “/wp-content/plugins/” directory.
- Activate the plugin through the “Plugins” menu in WordPress.

= After Plugin Activation =

Find and click Cash App in your admin dashboard left sidebar to access Cash App settings

**or**

Go to ‘Woocommerce > Settings > Payments’ screen to configure the plugin

Also _you can visit_ the [plugin page](https://theafricanboss.com/cashapp) for further setup instructions.

== Frequently Asked Questions ==

= Cash App Link Vs. Cash App Pay =

The major difference between the two payment methods is that Cash App Link is a link to your Cash App $cashtag (e.g cash.app/cashtag) while Cash App Pay uses the Square API integration that connects to your Square account.
Notice that Cash App Pay requires a Square merchant account while Cash App Link requires a Cash App account.

Check out the full side-by-side comparison table at [The African Boss](https://square.theafricanboss.com)

= Does this plugin integrate the Cash App Pay integration? =

Yes, Checkout with Cash App on Woocommerce integrates the Cash App Pay end-to-end API payment integration since v4.0.

Also there is an extra payment method added that provides a quick and easy way to display to your customers your CashTag and to link them to it.
It only displays your cashtag to the customer and redirects them to it so that the off site Cash App transaction can take place.
Please check screenshots for more details on what is reported.

= Do I need a Cash App account or a Square merchant account to use this plugin? =

Yes, you need either a Cash App account or a Square merchant account. This plugin comes with 2 payment methods for Cash App. One requires a Square merchant account and the other requires a Cash App account.

= What is emailreceipts.io and do I need to integrate it? =

emailreceipts.io is a service that allows you to track Cash App link payments and update order statuses automatically.
The integration also helps send automated emails to your customers when their order status changes.
If you do not want to use emailreceipts.io, you will have to manually update the order status in your Woocommerce admin dashboard.

= Support =

**Premium Support**

Users with a valid Checkout with Cash App on Woocommerce PRO license receive Priority Support, directly from the plugin developer! [Find out more!](https://theafricanboss.com/cashapp)

**Community Support for users of the Free version**

For support questions, bug reports, or feature requests, please use the [WordPress Support Forums](https://wordpress.org/support/plugin/wc-cashapp). Please search through the forums first, and only [create a new topic](https://wordpress.org/support/plugin/wc-cashapp#new-post) if you don't find an existing answer. Thank you!

= Languages and Localization =

Also compatible with Translation plugins (like Loco, WPML, etc) meaning you can translate the Checkout, Thank you page and Email notices

= SMS for Woocommerce compatibility =

Also using our SMS for Woocommerce plugin, you can send personalized bulk email and SMS notifications for orders still on-hold with order information and more

== Usage ==

After activating the plugin, add your Cash App information such as your $cashtag, Cash App name, Cash App email, Cash App phone number in the plugin settings to start receiving payments instantly.

**Unlock more great features for you and your customers and priority support with a PRO license. [Upgrade](https://theafricanboss.com/cashapp)**

== Upgrade Notice ==

= 5.3 =
This update is a security, stability, maintenance, and compatibility release. Updating is highly recommended.

== Changelog ==

= 5.3-5.3.1 Aug 15, 2023 =
- Using wp_remote for the locations/customers/payments/refunds API instead of the Square SDK
- Updated WC_Cashapp_Update_Order: verify signature, find order
- Updated WC_Cashapp_Update_Order: update order: update_order, post_title, post_content, response_code
- empty SQ_Access_Token when Cash App Pay is disabled
- updated locations_api and SQ_Locations
- unset Empty Keys in paymentRequest
- catch js error_message
- Updated Freemius to v2.5.10
- Updated Woocommerce and Wordpress compatibility

= 5.2-5.2.1 Jun 1, 2023 =
- Using Square Payments API & Refunds API & Customer API for Cash App Pay
- Display default plugin settings and rearranged settings values
- Updated display_cashapp,display_cashapp_logo_button settings for the redesigned and enhanced checkout and thank you page designs
- Better square_connect_url, cashapp payment_url and qrcode
- Now processing payments and refunds with the Square API
- Updated the Square paymentRequest with more details (BuyerEmailAddress, shipping_address, StatementDescriptionIdentifier, CustomerId)
- Fixed setStatementDescriptionIdentifier with parse_url domain
- Added saving additional order meta_data (sq_payment_token,sqp_idempotency,sqp_id,sqp_status,sqp_order_id,sqp_receipt,sqr_id,sqr_status,sqr_order_id)
- Added the Square payment receipt and transactions URL on the thank you page, email, and order note
- Fixed Square merchant account locations_errors & better locations error handling
- Disconnect Square access on site when access is disconnected via Square
- Better emailreceipts.io onboarding with accountid,accountname,accountemail before connecting
- Removed no longer applicable settings
- Integrated debug logs and added them to validate_fields,process_payment,...
- Enable Square debug and woo logs
- Removed the NEW badge on older menu items
- // SQ_Merchant_Id
- Removed the cloudmailin integration
- Commented out wp_safe_redirect on Revoke screen
- Updated Freemius, Woocommerce and Wordpress compatibility

= 5.1-5.1.1 May 1, 2023 =
- Improved Cash App Pay plugin settings and created a better Square connect experience
- Cash App Pay locations can now be selected in the plugin settings
- Improved plugin settings and their descriptions
- Improved Cash App Pay Square Connect
- Improved WC_Cashapp_Square class methods, html, and error displays
- Improved Cash App Pay menu for handling Square Connect
- Improved find_order function for emailreceipts.io
- Improved Cash App Pay Square Connect refresh/revoke actions and cron jobs
- wp_clear_scheduled_hook to prevent refreshing Square Connect token while disabled
- Improved receipt post type post_content
- Introduced checkout designs in PRO
- Fixed thank you page CSS for design 1-2
- Added do_not_checkout in case of missing plugin settings
- copy.js enqueued after 'jquery', 'wc_cashapp_qrcode'
- Removed square_url target_blank
- Updated copy.js and qrcode.js
- str_contains to str_pos for php compatibility
- wpautop & wptexturize the input
- echo , vs echo .
- Updated Freemius, Woocommerce and Wordpress compatibility

= 5.0-5.0.1 Mar 15, 2023 =
- Refactored code across plugin for better performance
- Better Cash App Pay integration
- Better variables and functions
- Better enqueued scripts
- Better thank you page customizations
- Better checkout page customizations
- Better email notification
- Better order note
- Updated Freemius, Woocommerce and Wordpress compatibility

= 4.3 Jan 25, 2023 =
- Updated Square paymentRequest with lineItems & shippingContact
- Square connect with authentication
- process order from on-hold automatically
- Better Cash App automated order status updates processing
- Better process Cash App receipts HTTP response statuses for emailreceipts.io
- Enable/Disable order note
- Edit order note in PRO with shortcodes
- Dashboard Admin menu file update
- Square Checkout assets and variables
- Deactivate checkout and assets when relevant info is missing
- Better assets and links
- Disable checkout assets on non-checkout pages
- Enable/disable admin menu and app fee
- Updated Freemius, Woocommerce and Wordpress compatibility

= 4.2-4.2.2 Nov 1-15, 2022 =
- When no order id is submitted, search through the 5 most recent Cash App orders on-hold and match by amount or customer cashtag
- Complete virtual orders instead of just processing
- Updated cashAppPay.initializeCashAppPay
- Updated square.js
- Better update-order HTTP response statuses
- Better note signature and new support for Enable/Disable note
- Side-by-side comparison table of Cash App Link vs Cash App Pay
- Better enqueued scripts and assets, .payment_method_cashapp
- Updated Cash App Pay wording on checkout from 'Use Cash App or your phone camera to scan the QR code to pay with Cash App' to 'Click the button below and follow the instructions to pay with Cash App'
- Better handling when Woocommerce is missing
- More details on emailreceipts.io for onboarding free users
- New checkout page design for PRO users
- Show new design to free users
- Updated Freemius, Woocommerce and Wordpress compatibility

= 4.1.1 Aug 30, 2022 =
- Updated cashAppPay.ontokenization
- Better Cash App automated processing
- No longer require $cashtag to process Cash App receipts
- When no order id was submitted in the Cash App note, check recent order for a match of amount and payment method

= 4.1 Aug 25, 2022 =
- Updated cashAppPay.ontokenization error handling
- Better loadCashAppPay error handling
- Catch Tokenization errors
- Better Cash App automated processing
- No longer require $cashtag to process Cash App receipts
- When no order id was submitted in the Cash App note, check recent order for a match of amount and payment method
- Added origin to Square requests
- Added thumbnailUrl to store profile connected to emailreceipts.io
- Updated Woocommerce and Wordpress compatibility

= 4.0 Mar 15 - Aug 15, 2022 =
- Integrated the offical Square Cash App Pay as a second payment option
- Integrated connection to square.theafricanboss.com
- Automated order status updates for PRO users
- Integrated emailreceipts.io to track Cash App receipts and update order statuses automatically
- Redesigned how information is displayed to the customer on the checkout and thank you pages
- Added capturing cashtag as order meta data
- Added Cash App Pay payment method vs. Cash App payment method
- Reduce order stock inventory option
- New logo with Square logo and QR code in one
- Tutorial on how to update order statuses automatically for PRO users
- Pending -> Canceled order time limit article on the help page
- Bulk email and SMS notifications for orders still on-hold using SMS for Woocommerce plugin
- SMS for Woocommerce compatible
- Internalization of the plugin checkout, thankyou and email
- Updated help links
- Moved to Freemius for automatic updates
- Updated Woocommerce and Wordpress compatibility

= 3.4 December 5, 2021 =
- Updated from woocommerce_before_thankyou to woocommerce_thankyou_payment-method-id for compatibility with thank you page customizer plugins
- move menu order to below woocommerce menu - position 56
- Removed version date from main file
- Fixed error bug that disallowed upgrade/downgrade due to global constants structure in free MOMO<PAYMENT>PRO_ while in paid, MOMO<PAYMENT>_PRO_
- Fixed admin_url functions with issues
- Added ! $sent_to_admin / $sent_to_admin = false to email instructions
- Replaced woocommerce_email_before_order_table hook by woocommerce_email_order_details
- Updated Woocommerce and Wordpress compatibility

= 3.3.1 September 9, 2021 =
- Updated width and height attributes for logo-qr

= 3.3 September 7, 2021 =
- Added .logo-qr class that overwrites theme CSS for the button and QR code
- Changed the wording and removed "shipping and delivery" to include digital woocommerce sellers
- Removed version date

= 3.2.1 August 30, 2021 =
- Fixed order order_id occurences

= 3.2 August 30, 2021 =

- Added test button to settings page to see what customers see when they click the button or run the QR code
- Sharing payment methods with free versions to keep data across
- Fixed 'if functions for on-hold and check payment methods' placement
- Improved deactivate free plugins when PRO activated
Smooth upgrade from free to PRO
- PRO invitation admin notice when using free plugin
- Fixed bootstrap CSS enqueued on menu pages
- Added .momo-*** class to checkout CSS to apply custom CSS to payment icons and QR codes
Removed content from assets/css/checkout that was forcing 35px size on some themes
Added important height to force 100px in size of QR code and buttons on checkout and thank you page
Added setup plugin link to wp_die when upgrading from free to PRO plugin
- Better settings links on plugins page
- Better installation instructions
- renamed PRO versions to [payment_name PRO]
- Added free and paid recommended menus in sidebar with colors
- Fixed menu buttons in PRO plugin
- Updated WP and WC compatibility

= 3.1 June 1, 2021 =

- Only send email with cashapp link when order is on hold
- Cash App checkout icon resized
- Plugin name changed from MOMO Cash App to 'Checkout with Cash App on WooCommerce'
- Send specific notices while awaiting payment with $order->get_status() &&  $order->get_payment_method()
- Updated WP and WC compatibility

= 3.0 Mar 1, 2021 =

- Refactored entire plugin structure (Going from a single file to multiple folders like admin, functions, notifications, pages, languages, and more)
- Now you can choose to show Cash App information after customers place order in PRO (Feature requested by a user - Orion Technologies)
- Added 3 New PRO functionalities and fields in plugin settings (display_cashapp, display_cashapp_logo_button, toggleSupport)
- Added new pages (tutorials, help, recommended plugins, etc)
- Updated the function to deactivate plugin when Woocommerce is not found
- Updated the function to deactivate plugin when PRO is active
- Better notices and warnings for different actions especially involving PRO
- Better use of global variables with define() function
- Deactivate plugin very early on when needed
- Updated compatibility with WP v5.7
- Updated compatibility with WC v5.0

= 2.2 Jan 14, 2021 =

- Added QR code
- Updated compatibility with WP v5.6

= 2.1 Dec 1, 2020 =

- Fixed cashapp icon styles on some themes where it was floating
- Fixed thank you page and email total amount
- Removed email from author
- Calling file locations and directories according to WP docs
- Added Go PRO button
- Added Get featured button
- Cleaned up the extra fields in the cashapp settings
- Now requiring PHP version starting at 5.0

= 2.0 Oct 1, 2020 =

- Fixed cashapp icon link that was not displaying icon properly
- Fixed cashapp link total amount
- Added new fields in cashapp settings: checkout instructions, store instructions, and thank you notice
- Added new links for PRO version
- Added review and upgrade buttons to admin dashboard
- Now requiring PHP version starting at 5.0

= 1.0 Aug 1, 2020 =

- Added cashapp icon on checkout with link
- Added fields in admin dashboard for cashapp plugin

<?php code();?>
