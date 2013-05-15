Nailed
======

Transparent Caching Image Thumbnailer for PHP

What the hell does it do?
------

Answer: Resizes Images based on a sane naming scheme.

for example: 

Let us assume we have an image named `/images/test.jpg`

We want this image resized so that it will fit in a box 200px tall and 200px wide.

In our markup we just reference the image as `<img src='/images/test_200x200.jpg' />` 
and go out merry way. 

Cool, so how do I wire it up?
------

First of download and put the `resize.php` file in a sane place in the web root.

Create a directory to contain the cached images. This should be writable by they webserver (duh).

Drop an apache rewrite file like:
    
    SetEnv NAILED_CACHE_PATH .cache
    SetEnv NAILED_CACHE_LIFETIME 2400
    
    RewriteEngine On
  
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ /_imageUtils/resize.php?path=%{REQUEST_URI} [NC,L]
    
Into any directory that you want the behavior to work on.

Update the .htaccess to reflect your setup.

`NAILED_CACHE_PATH` should be the path to the cache directory you created relative to the `resize.php` script.

`NAILED_CACHE_LIFETIME` is the time in seconds that the images should be cached for.

`RewriteRule` should be adapted to point at wherever you stuck the resize.php script.






