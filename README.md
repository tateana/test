Installation
------------

1. clone repo

    git clone git://github.com/zendframework/ZendSkeletonApplication.git --recursive

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
	  `subject` varchar(50) NOT NULL,
	  `message` text NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
	
4. Configs in config/autoload.

4.1 Rename files db.local.php.dist.php to db.local.php and mail.local.dist.php to mail.local.php.
4.2. Configure the files above

5. If you use file mail transport, give write access to the "data" folder. 

6. Install the intl extension. http://php.net/manual/ru/intl.setup.php
