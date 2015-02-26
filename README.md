Langblog
========

Langblog is an audio/videoblog used for the teaching of oral production skills. It uses blogging technologies to create a forum that aids access to audio and video files, easy recording and uploading of audio and video files, and interaction among group members. Adapted from WordPress, audio and video posts are directly created online using Flash, with no software installation required.

# Install Apache, MySQL and wordpress and enable multisite
Follow the instructions http://codex.wordpress.org/Create_A_Network. 

# Insert your Kaltura profile data
Langblog requires a valid Kaltura account. In the file kaltura.cfg, which can be found in mu-plugins/blogtype/configuration, insert your Kaltura profile information.

Basically you have to add:
* kaltura_cms_user=YOUR KALTURA USERNAME
* kaltura_cms_password=YOUR KALTURA PASSWORD
* kaltura_secret=KALTURA USER SECRET YOU CAN GET IT FROM KMC INTERFACE (Settings->Integration Settings)
* kaltura_admin_secret=KALTURA ADMINISTRATOR SECRET YOU CAN GET IT FROM KMC INTERFACE (Settings->Integration Settings)

## Add source to your wordpress installation
Copy all files from wp-content/* to the folder wp-content/ in the wordpress distribution you have downloaded previously.


## Configuration of Learning Tools Interoperability (LTI)
* LTI can be found here: http://imsglobal.org/lti/
* Configuration:
 The configuration of the consumers requires authentication as superAdministrator and is found in Network Dashboard → Settings → LTI Consumer Keys. 
 Here you can manage the different consumer keys and associated secret codes, and if has custom username (usually is custom_username)
* The URL to link with the LTI provider from the LMS has the following structure: 
 http://<ip>/wordpress/index.php

## More information 
 https://github.com/UOC/Langblog/blob/master/doc/LANGblog-install.pdf?raw=true


Speak Apps Project has been funded with support from the Lifelong Learning Programme of the European Commission. This document reflects only the views of the authors, and the European Commission cannot be held responsible for any use which may be made of the information contained therein. 

![](http://www.speakapps.eu/wp-content/themes/speakapps/images/EU_flag.jpg) 
