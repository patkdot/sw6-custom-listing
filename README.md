# Custom Listing Plugin
### for Shopware 6.5
This plugin adds a badge to each product the user has bought on the listing pages.
This is done via session, since the listing page is cached.
Subscribers are listening to login (token recreation) and order events. Cancelled orders are filtered out and session attribute is cleared on every login.

## Prequisites

- Running Shopware (docker) environment with phpunit and phpstan

## Testing
First enter your docker container and execute the following command:\
``
vendor/bin/phpunit --configuration="custom/plugins/CustomListing"
``
## QS
First enter your docker container and execute the following command:\
``
vendor/bin/phpstan analyse --configuration="custom/plugins/CustomListing/phpstan.neon" --level=5 custom/plugins/CustomListing/src
``
