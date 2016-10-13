# Prestashop payment module for Swish

This module will enable the Swedish payment solution Swish on your Prestashop system.

The module is a variant of the banktransfer module, i.e. the user selects the payment method, makes his payment using and Swish and finally you (the shop admin), need to manually update the order status to "Payment accepted" before shipping. A new order state "Awaiting Swish payment" is also created.

The module will also send a mail to the customer with details for the Swish payment.

Languages supported: English, Swedish


##Installation instructions:

1. Download the latest zip-file here: http://github.com/martin67/swish/archive/master.zip
2. Install the zip-file (Modules / Add a new module)
3. Configure the required data. *Swish Number* should be a number, either you personal 070-nnnnnnn, or the Swish Company (Swish Företag) variant, 1234567.
4. The *Swish Owner* field should be the same as is displayed by Swish once you've authenticated in the App. This field will be preented to the customer, so that he/she can see that transaction goes to the right place.
5. Select which countries you want to enable it for, under Modules / Payment
6. You need to fix the Swedish translation for the order state name (this is also the subject of the mail that goes out). I couldn't find a way to do it in code... This is done under Orders / Statuses.

##Compatibility
I've tested it with Prestashop 1.6. I think it should work for 1.5 as well.
