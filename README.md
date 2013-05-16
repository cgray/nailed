\|=Nailed=\>
======

Transparent Caching Image Thumbnailer for PHP

What the hell does it do?
------

Answer: Resizes Images based on a sane naming scheme.

for example: 

Let us assume we have an image named `/images/test.jpg`

We want this image resized so that it will fit in a box 200px tall and 200px wide.

In our markup we just reference the image as `<img src='/images/test_200x200.jpg' />` 
and go out merry way. Or even better if our design requirement states that the image 
just needs to be 200px wide we could just reference it as `<img src='/images/test_200x0.jpg'/>`.

This works in these steps - 

  1. if file isn't found at the requested location request is rewritten to `resize.php`
  2. if the requested file does not matches the pattern of `/path/file_{width}x{height}.ext` a 404 is issued.
  3. we then check under the `NAILED_CACHE_PATH` directory for a file named `/path/file.ext/{width}x{height}.ext`, if we find a file there we check the modified time stamp against the original if original < cached we just down to step 5.
  4. we resize the image storing it in `/path/file.ext/{width}x{height}.ext` under the cache directory.
  5. send headers and return the file. 

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

`NAILED_CACHE_PATH` should be the path to the cache directory you created relative to the `resize.php` script. It's worth noting that multiple .htaccess files can reference the same cache directory.

`NAILED_CACHE_LIFETIME` is the time in seconds that the images should be cached for.

`RewriteRule` should be adapted to point at wherever you stuck the resize.php script.

What do I need to make this mother work
------

 * PHP 5.3+
 * ImageMakick Extension (GD Version coming soon)
 * Apache w/ mod_rewrite

TODO's
------

 * Move File name Mappings to Regex
 * Create a wrapper arround the image and support a GD and ImageMagick in the same interface.
 * Create Helper functions in common frameworks to support getting a resized image urls from parameters i.e. `echo $this->imageResize($imagePath, $width, $height);`
 * Create Plugin system so other photo manipulation tasks can be performed on the Image
   * Rotate (stateful or based on orientation in exif info)
   * Colorize (adjust colors)
   * Crop (crop an image)
  





