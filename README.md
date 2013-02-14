Imdex
=====

Overview
--------

**Imdex** is an image index page, written in PHP. To see it in action, a usually up-to-date version 
can be found on my own website at http://s.horsedrowner.net/, showing screenshots I've taken with
my own screenshot tool, [Superscrot](https://github.com/horsedrowner/Superscrot).

Quick Start
-----------

1. 	Clone the repository into a temporary directory: 

		git clone git://github.com/horsedrowner/Imdex.git temp

2.	Move the files into the directory where your images are:

		mv temp/* images/
		mv temp/.git images/

3.	Set up the web server to serve `/index.php` as root (for example in nginx):

		location / {
			root	/var/www/images/screenshots;
			index	/index.php;
			error_page 404 /404.png;
		}

	or use `router.php` as routing script (might or might not work)
