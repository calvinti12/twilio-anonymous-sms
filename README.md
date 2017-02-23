# Twilio Anonymous SMS Application

## Description
This is a proof of concept for writing an anonymous SMS system for communications between customers and business owners. The idea of this application is to make use of [Twilio Cookies](https://support.twilio.com/hc/en-us/articles/223136287-How-do-Twilio-cookies-work-), combined with message body parsing to get around using a database. This application will allow for customers to send an SMS message to a Twilio phone number, which is then forwarded onto the business owner's mobile number. The business owner will see the customer's mobile number and their message. The business own can then choose to respond to the customer through their Twilio number, which will mask the business owner's person mobile number.

## Instructions

1. Have an active [Twilio](https://www.twilio.com/try-twilio) account and U.S. or Canada phone number capable of receiving SMS. _Not a trial account._
2. Create a new Heroku app. This application was built using [Heroku](https://heroku.com), but you could adjusted the code to run in any environment. 
3. After you've created your Heroku app, grab your Heroku app's URL and put that in your Twilio number's configuration under "A Message Comes In" and set it to HTTP POST.
4. 

>**Note:**
This application was only designed to work with U.S./Canada numbers.
