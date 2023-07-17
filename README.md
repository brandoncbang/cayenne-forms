<p align="center"><img src="https://raw.githubusercontent.com/brandoncbang/cayenne-forms/master/resources/svg/logos/wordmark.svg" width="400" alt="Cayenne Forms Logo" /></p>

# About Cayenne Forms
Cayenne Forms is a self-hosted web app that lets you keep your websites simple, while maintaining control of your data.

## Features
- **Self-hosted:** Keep ownership over your websites' data.
- **Invite only:** You have full control over who has access to the dashboard.
- **Unlimited form endpoints:** The amount of forms & entries is only limited by your server's storage space.
- **User-friendly:** Enjoy a modern dashboard to manage your forms & entries.
- **Admin CLI:** Perform admin actions using Artisan-based console commands.

## Requirements
- **PHP** 8.2+
- **MySQL** 8.0+
- **Node.js** 18.16+

## Setup

### Invite your first user
```shell
php artisan cayenne:invite johndoe@example.com
```

An email will be sent to `johndoe@example.com` with an invite link.

### Create an account
On the invite page, fill out the credentials for the new account and click "Create account".

### Create your first form
On the forms list page, click on "+ New form".

On the form creation page, fill out the new form's name (required).

If you want, you can also set a URL to send users to when after submitting an entry ("Success URL"), and a field to
detect bots and mark as trashed if filled out ("Honeypot field"). These are both optional.

### Point to your form endpoint
In order to receive entries, you'll need to add an HTML form to your website, with an `action` attribute set to the form
endpoint you just created. Luckily, Cayenne Forms generates some code you can copy & paste right into your website.

Click on "Edit", then go to the "Form Endpoint" > "Embed Code" section at the top, and click "Copy". Now the code is
copied to the contents of your clipboard.

Add the form code to your site.
[You'll need to add some fields to submit to the endpoint.](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/form)

Now any data your users send using your website's form will be available in your dashboard!
