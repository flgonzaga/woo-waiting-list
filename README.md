# WOO Waiting List
This plugin allows you to enter a waiting list for a product in your WooCommerce.  
You can show the option when the product is "out of stock" or "free" using a custom condition directly on the product page of your theme (in this option you need programming knowledge)  

## Requirements and legal informations
Contributors: Fabio Gonzaga  
Tags: woocommerce, waiting list  
Requires at least: Wordpress 4.9.4, WooCommerce 3.3.4
Tested up to: Wordpress 4.9.4, WooCommerce 3.3.4
Requires PHP: 5.6 or higher 
License: GPLv3 or later License  
URI: http://www.gnu.org/licenses/gpl-3.0.html  

## Instalation
Download and upload the folder woo-waiting-list to wp-content/plugins 

## Usage 
Add this shortcode in your product page:  
echo do_shortcode('[woo_waiting_list product_id=' . $product->get_id() . ']');

## Languages
Available in English, Portuguese (Brazil)

## Screenshots
![Alt text](/assets/images/screenshot-01.png?raw=true "Frontend")
![Alt text](/assets/images/screenshot-02.png?raw=true "Settings")
![Alt text](/assets/images/screenshot-03.png?raw=true "List")

## Other informations
Contributions are welcome.  
Future features:
- Allow show the option when a postmeta have a specific value (started)  
