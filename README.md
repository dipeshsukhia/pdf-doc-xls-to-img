
laravel 7

pdf-to-image
doc to image

composer require spatie/pdf-to-image:2.0.1

composer require phpoffice/phpword
composer require dompdf/dompdf

Imagick and Ghostscript  extension

 sudo apt-get install ghostscript
 sudo apt-get install gcc php-devel php-pear
 sudo apt-get install imagick
 sudo apt-get install php-imagick


for this error edit the /etc/ImageMagick-6/policy.xml and changed the rights for the pdf line to "read"

<policy domain="coder" rights="read" pattern="PDF" />
