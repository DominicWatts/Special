# Magento 2 Special Offers Page ##

Frontend controller to display products with special offers with layered navigation.

For products to display they must be on special offer and with the special from and to date range.  Plus any additional rules that would apply to a product collection on your store such as visibility or stock.

![Screenshot](https://i.snag.gy/3GZ6wr.jpg)

# Install instructions #

`composer require dominicwatts/special`

`php bin/magento setup:upgrade`

`php bin/magento setup:di:compile`

# Usage instructions #

Apply special offers to products

Go to `/specials`


