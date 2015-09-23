Task. Create a contact us page

Using PHP, HMTL and JavaScript write a “contact us” page 

Requirements:

1. On the page a form with the following fields must be created:
 Name* - string, 3-50 chars
 Email* - string, valid email
 Subject* - string, 3-50 chars.
 Message

The fields marked with * are mandatory.
Add a submit button. 

2. When the submit button is clicked, the following should happen:
a. An email with all above defined fields should be sent to a predefined email address. In case you don’t have access to an SMTP server, mock it. 
b. all relevant information is logged to a database
c. the user will be informed about the success/error of the action

3. Feel free to create and use your own stylesheet and layout
4. Demonstrate MVC, OOP, Security Concepts as needed.
The time effort of this assignment should not be more than 4 hours. The main purpose of the assignment is to create a basis for discussion of high-level concepts.


Installation
------------

1. Clone repo

    	git clone https://github.com/tateana/test.git --recursive

2. Apache Setup

	    <VirtualHost *:80>
	        ServerName test
	        DocumentRoot /path/to/test/public
	        <Directory /path/to/test/public>
	            DirectoryIndex index.php
	            AllowOverride All
	            Order allow,deny
	            Allow from all
	        </Directory>
	    </VirtualHost>
    
3. DB Schema
 
	 	CREATE TABLE IF NOT EXISTS `messages` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(50) NOT NULL,
		  `email` varchar(50) NOT NULL,
		  `subject` varchar(100) NOT NULL,
		  `message` text NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
	
4. Configs in config/autoload.

	4.1 Rename files db.local.php.dist.php to db.local.php and mail.local.dist.php to mail.local.php
	
	4.2. Configure the files above

5. If you use file mail transport, give permissions to write to the "data" folder. 

6. Install the intl extension. http://php.net/manual/ru/intl.setup.php
