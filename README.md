Formicula Zikula Module
=======================

A template-driven form mailer for Zikula. 
Formicula is a highly configurable contact mailer solution for Zikula Application Framework. 
You can create any kind of contact form with any number contact fields by changing templates.
Forms can be controlled by permissions.
It also supports multiple, permission controlled recipients, configurable notification emails, a simple captcha for spam protection, hooks to the Captcha module, javascript form validation and file uploads.

4.x versions
------------
The 4.x version in the master branch needs Zikula 1.4.3+ to run with.
**Note the master branch is work in progress and not stable at the moment!**

3.x versions for Zikula 1.3.x
-----------------------------
3.0.2 is the version that contains the same functionality as 3.1.0, but is Not converted to HTML5 validation and jQuery. See https://github.com/zikula-modules/Formicula/issues/54 about HTML5 required and CkEditor < 4.2 - Scribite.

3.1.0 is a version that brings HTML5 form validation (with webshims lib polyfill fallback for
browsers not supporting this) and all jQuery javascript. 3.0.2 is the latest version with prototype
and prototype validation.

* More information on HTML5 form validation also in the Zikula Core ticket: https://github.com/zikula/core/issues/1181
* HTML5 constraint validation: http://www.html5rocks.com/en/tutorials/forms/constraintvalidation/
* Webshims polyfill lib for html5 functionality in browsers not supporting this: http://afarkas.github.io/webshim/demos/

3.1.2 is unreleased an contains some minor bugfixes.

2.x versions for Zikula 1.2.x
-----------------------------
The 2.2 branch contains the latest version for Zikula 1.2.x

Formicula 4.0 user manual
-------------------------     
Formicula supports an infinite number of contact forms with an infinite number 
of custom fields (ok, infinite as long as you have enough server memory).

Requirements
------------
Formicula 4.0 requires Zikula Core 1.4.3 or later. It *will not* run
with earlier versions - sorry. Use Formicula < 4.0 instead.
For creating captchas you need PHP compiled with the GD graphics library.

Installation
------------
1. Extract the files to your Zikula modules directory.
2. Initialize and activate the module in the admin section.
3. During installation, Formicula tries to create a directory formicula_cache containing a .htaccess file.
   If this fails (most probably when safemode is set to 'on' in your PHP environment or the zTemp
   directory is outside your web root), you will be notified and have to create them on your own:
   a) Create a directory formicula_cache in your temporary directory (most likely ztemp) and make it 
      writable for the web server (e.g. chmod 777)
   b) Create a .htacess in formicula_cache containing the following lines to allow access to the
      images created for the captcha:
      SetEnvIf Request_URI "\.gif$" object_is_gif=gif
      SetEnvIf Request_URI "\.png$" object_is_png=png
      SetEnvIf Request_URI "\.jpg$" object_is_jpg=jpg
      Order deny,allow
      Deny from all
      Allow from env=object_is_gif
      Allow from env=object_is_png
      Allow from env=object_is_jpg
4. Create some contact names/topics with respective email adresses in the Formicula admin setion.
   Upon installation, Formicula creates a default contact with the admins mail address.
5. Add a basic permission rule, e.g.
   Unregistered (group)  |  ZikulaFormiculaModule::.*  |  .*  |  comment
   This enables unregistered users to use all existing forms. In a default installation
   the users group has a generic comment permission to do the same.
6. Add a link to your main menu with: {Formicula} (using [Formicula] will not work!)
6. Add a link to your main menu with: {Formicula:user:main}
   This uses form 0. To call a specific form id use
   index.php?name=Formicula&type=user&func=main&form=<formid>
   e.g. index.php?name=Formicula&type=user&func=main&form=5 to use form #5
In the distribution package you will find sample forms 0, 1, 2 and 3
0 is a normal contact form and form #1 and beyond are more extensive forms.

Configuration
-------------
Show xxx: quick configuration enable/disable some fields in the userform. These fields may be
(read: surely will be) removed in future versions.
Send confirmation email to user: Tick this to send an confirmation mail to the user  	
directory for uploaded files: Uploaded files get stored here. Make sure this directory is secured with a
.htaccess file otherwise someone can upload malicious files and execute them!
Delete file after sending: does what it says when ticked.
Activate spamcheck: shows a little captcha in the form

Contacts
--------
Each contact consists of several information:

Contact name or Topic: The name that is shown to the user in the form, e.g. Webmaster 	
Email: The email to send the data to	
Public: tick this to make the contact available	for use.
Sender name: senders name as used in the users confirmation mail	
Sender email: senders email address as used in the users confirmation mail, e.g noreply@example.com	
Subject: enter a static subject or use these placeholders:
    * %s = sitename
    * %l = slogan
    * %u = site url
    * %c = contact name
    * %n<num> = user defined field name <num>
    * %d<num> = user defined field data <num>
 
Using own contacts
------------------
It is possible to pass own contacts to formicula instead using the formicula database. To do this by a link 
you have to call the addSessionOwncontacts function in the User-API. This function will return you
an integer which you should append with the index 'owncontacts' to the URL pointing to formicula.
If you want to embed your form you can pass the owncontacts directly to the form by adding
the owncontacts array to your arguments array.

The owncontacts array should contain the following values (per item) in both cases:
    name the contact full name (required)
    sname the contact secure name wich will be send to the submitter (optional)
    email the contact email (required)
    semail the contact email wich will be send to the submiter (optional)
    ssubject the subject of the confirmation mail (optional) 
 
Templates
---------
Everything you want to do with the form is handled in the templates.
Store them in Resources/views/Form/
The '#' at the beginning of the template designates the number of form.

The templates for the forms are named as follows:

The form with the input fields:  #_userform.html
The page with the confirmation after submitting the data:  #_userconfirm.html
The page with error messages after submitting data:  #_usererror.html
The user's confirmation mail in text format:  #_usermail.txt
The user's confirmation mail in html format:  #_usermail.html
The admin mail in text format:  #_adminmail.txt
The admin mail in html format:  #_adminmail.html
This is the email that the admin gets after the user sends submits the userform.

Note: the email format can be chosen by using a dropdown like
			
<select name="userformat">
<option value="html">{gt text='Html'}</option>
<option value="plain">{gt text='Plain text'}</option>
</select>

or if you want to force the type of email, you can use a hidden field in the form
			
<input type="hidden" name="userformat" value="html" /> 

If you do not want to send a confirmation email to the user for a certain form, set
userformat to none:
 
<input type="hidden" name="userformat" value="none" /> 

This overrides the setting in the configuration.

Standard Fields
---------------
The standard fields to be used in forma are under userdata

userdata[uname]: the users name (mandatory)
userdata[uemail]: the users name (mandatory)
userdata[url]: the users homepage (optional)
userdata[location], userdata[phone], userdata[company]: these are obvious... (optional)
userdata[comment]: normally a textarea for entering free text. Since 0.6 this is not longer 
a mandatory field! HTML is stripped from the comments to avoid spam. In addition, the
comment is send to Zikula's internal censor function. If the result differs from the 
original comment, the submission is also treated as spam and not sent. This results
in an error message. 
captcha: mandatory if spamcheck feature is enabled, see 0_userform.html for an example

Custom Fields
-------------
You can add as many custom fields to your form as you want. 
This makes it easy to create e.g. an online job application form if needed.

This makes it necessary to send certain information from the form page to the module.
The custom fields are numbered in old versions and an associative array in newer versions.

These are:
  
* custom[fieldname][name] (hidden field) name of the custom field
  can be used in the confirmation email
  
* custom[fieldname][mandatory] (hidden field) set to 1 if this is a mandatory field.
  Formicula checks this and shows an errormessage when this field is not filled

* custom[fieldname][data] (any kind of input field) the data with fieldname
  the payload

Example:

<label class="mandatory" for="foobar">{gt text='foobar'}</label><br />
<input type="hidden" name="custom[foobar][name]" value="{gt text='foobar'}" />
<input type="hidden" name="custom[foobar][mandatory]" value="1" />
<input type="text" required name="custom[foobar][data]" id="foobar" size="35" maxLength="80" value="{$custom.foobar.data}" />

Permissions
-----------
To use a form you need the right to COMMENT.

Unregistered   |   ZikulaFormiculaModule::  |  .*   |  comment
all unregistered users are allowed to use all forms and write to all contacts

Unregistered   |   ZikulaFormiculaModule::  |  0::  |  comment
all unregistered users are allowed to use form 0 with all existing contacts.
	
Users   |   ZikulaFormiculaModule::   |  1:(2|3):  |  comment
all members of the group Users are allowed to use form 1 and write to contact 2 and 3.
    
Use external information in the forms
-------------------------------------
You can use external information in the form by sending them in the url used to call
the form. These data are send in an associative array addinfo where the key is the name.

With

index.php?name=Formicula&type=user&func=main&form=2&addinfo[tid]=4&addinfo[pid]=17

you send two different information to the form 2 which can used in the userform template (in
this example 2_userform.html) with

{$addinfo.tid}  => 4
{$addinfo.pid}  => 17

As you can see you can use this to e.g. load data from Pagesetter in the userform template.

Client side input validation
----------------------------
The supplied templates use a html5 input validation with webshims lib polyfill fallback. 

Captcha
-------
The simplecaptcha plugins adds an image with a very basic math equation (3 number from 1 to 2 
and either +, - or *) to the form if the spam check is enabled. If the user enters a wrong
value, Formicula redirects back to the page where it came from.

Formicula tries to create images in this order:
gif
jpg
png
If all these image types are not supported by your server, captchas are deactivated, in this
case Formicula turns the configuration option "Activate spamcheck" off.
Please refer to http://php.net/manual/en/ref.image.php for more information.

Formicula also supports ztemp-directories located outside the websites root directory. In this case
(when the temp directory points to an absolute path) the image is delivered by a internal 
function and not linked directly.
Contact GitHub API Training Shop Blog About
© 2017 GitHub, Inc. Terms Privacy Security Status Help
