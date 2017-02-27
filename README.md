# Twilio Anonymous SMS Application

## Description
This is a proof of concept for writing an anonymous SMS application that facilitates anonymous communications between customers and business owners. The idea of this application is to make use of [Twilio Cookies](https://support.twilio.com/hc/en-us/articles/223136287-How-do-Twilio-cookies-work-), combined with message body parsing to get around using a database. This application will allow for customers to send an SMS message to a Twilio phone number, which is then forwarded onto the business owner's mobile number. The business owner will see the customer's mobile number and their message. The business owner can then choose to respond to the customer through their Twilio number, which will mask the business owner's personal mobile number.

## Requirements
* PHP 7
* Composer
* A web server. (Heroku was used in this build)
* A Twilio phone number.

## Instructions for Installation
1. Have an active [Twilio](https://www.twilio.com/try-twilio) account and U.S. or Canada phone number capable of receiving SMS. _Not a trial account._
2. Create a new Heroku app. This application was built using [Heroku](https://heroku.com), but you could adjust the code to run in any environment. 
3. After you've created your Heroku app, grab your Heroku app's URL and put that in your [Twilio number's configuration](https://www.twilio.com/console/phone-numbers/incoming) under "A Message Comes In" and set it to HTTP POST, like in the following example.
![number_config](https://cloud.githubusercontent.com/assets/786896/23295248/633a34e0-fa24-11e6-9d55-eb1d517f0418.png)
4. This next step is optional depending on how you would like to handle config/environment variables. For this example I created four environment variables on my Heroku app, They are
    * `ACCOUNT_SID` - This shouls equal your Live Twilio Account SID.
    * `AUTH_TOKEN` - This should equal your Live Twilio AuthToken.
    * `OWNER_CELL` - This should equal the cell phone number of the business owner, or person trying to keep their phone number anonymous.
    * `TWILIO_NUMBER` - This should equal your Twilio phone numbr you just configured with the Heroku URL.
    ![env-vars](https://cloud.githubusercontent.com/assets/786896/23337262/6d7cb328-fb9c-11e6-8d58-1b18a887727b.png)

>**Note:**
This application was only designed to work with U.S./Canada numbers. With a little update to the regex you could get this working for other countries.

## How It Wroks
When a message is sent to your Twilio number, the message will be forwarded to the mobile number you declaired as an environment variable above. This will probably be your mobile number.

The first message to you will look like this:

>From: +15554441212

>Message: The message from your customer.

>Instructions: Include the full number above in the body of your reply to start a conversation with this person.

When you reply to this message you are replying to your Twilio number, so you **MUST** include the customer's phone number in the body of your message like this:

> +15554441212 Hi customer! Thanks for contacting me!

The number will be removed from you message and the customer will only see the message of:

>Hi customer! Thanks for contacting me!

After your initial message that included the customer's phone number, you will no longer have to include the phone number. The application has set a Twilio cookie which will remain active for four hours since your last message. Four hours is the limit before a Twilio cookie expires. After that time you will be prompted by the application to provide a new phone number as it will not know who you are trying to contact.

Whenever you send a message with a phone number in the body of the message, you are now in communication with that phone number through your Twilio number. The person receiving messages from you will see messages coming from your Twilio phone number, not your mobile number. The application will also confirm that you are now in communications with that specific number with this message:

>You are now in an SMS conversaton with +15554441212.
You no longer have to include their number in the body of your messages.

If the Twilio cookie has expired and you send a message without phone number in the message body, you will be prompted by the appliction to provide a phone number like this:

>I do not know who to send your message to. Please specify a phone number in your message body using this format +1XXXYYYZZZZ.

Again, once you've provided a number in your message, you will be in commuincation with that number through your Twilio number for a maximum of four hours from your last message, before you need to enter a new number.
