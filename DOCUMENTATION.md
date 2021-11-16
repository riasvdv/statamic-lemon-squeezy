# Lemon Squeezy Statamic addon

![Connection](https://github.com/riasvdv/statamic-lemon-squeezy/raw/main/docs/lemon-squeezy-connection.png)

The [Lemon Squeezy Statamic](https://statamic.com/marketplace) addon makes it easy to start selling digital products on your Statamic website without having to take on all of the bloat and maintenance headaches of a traditional e-commerce plugin. Getting started is simple:

1. Log in to your Statamic control panel and navigate to Addons.
2. Type “Lemon Squeezy” into the Search and hit Enter.
3. Locate the Lemon Squeezy addon in the list of search results and click on the addon to open the details.
4. Click the “Install” button.

Once the addon has been installed and activated, a new "Lemon Squeezy" item will appear in the side navigation. On this screen, you can use the "Connect to Lemon Squeezy" button to begin the connection process. When prompted, click "Authorize" to connect your Lemon Squeezy account with your Statamic site.

## Adding Checkout Buttons

![Webhooks](https://github.com/riasvdv/statamic-lemon-squeezy/raw/main/docs/lemon-squeezy-fieldtype.png)

After [adding a product](https://docs.lemonsqueezy.com/article/65-adding-a-product) on Lemon Squeezy, it can easily be added to any Statamic entry by using the built-in Lemon Squeezy fieldtype. To add a checkout button using the Statamic:

1. Add the `lemon_squeezy` fieldtype to your blueprint.
2. Edit the content of the entry
3. Select the product you would like to sell from the dropdown
4. Add some button text (e.g. "Buy now")
5. Toggle "Use checkout overlay" if you would like to use the checkout overlay (opens in a modal instead of redirecting to the checkout)

## Webhooks

![Webhooks](https://github.com/riasvdv/statamic-lemon-squeezy/raw/main/docs/lemon-squeezy-connection.png)

This addon can also receive and validate webhooks to dispatch Laravel events with the received data. More information on how to set these up is displayed in the Lemon Squeezy section of the side navigation.

Make sure to publish the config file using `php artisan vendor:publish --tag=lemon-squeezy-config` to set a `signing_secret`
