# CodeIgniter-Twiggy
## Installation & Requirements

### Requirements
 * PHP `5.6+`
 * CodeIgniter `3.0+`
 * twig `^1.0`|`^2.0`

### Installation
The installation is a piece of cake!
All you need to do is to download a GitHub release and place the files in your CodeIgniter application folder.
If you use a blank CodeIgniter application you can overwrite the existing files (autoload.php, Welcome.php).
If you want to add Twiggy to an existing application, then add twiggy in the autoload library array. Like this: 
```php
 $autoload['libraries'] = array('twiggy');
```
After you installed the Twiggy library you need to install twig, here are 3 way to do it.

#### 1. Composer (recommend)
  1. Navigate to your `application` folder
    * `$ cd application`
  2. Install composer
    * __(PHP5.6+)__ run `composer require "twig/twig:^1.0"`
    * __(PHP7.0+)__ run `composer require "twig/twig:^2.0"`
  3. Activate Composer auto-loading for CodeIgniter in `application/config/config.php`
    * change `$config['composer_autoload']` to `TRUE`

#### 2. Non-Composer
  1. Download Twig from [Twig's GihHub Releases](https://github.com/twigphp/Twig/tags)
    * __(PHP5.6+)__ Twig v1.0.0+
    * __(PHP7.0+)__ Twig v2.0.0+
  2. Create a new folder `vendor` in `application`.
  3. Extract the downloaded .tgz/.zip file.
  4. Copy the 'Twig' folder from the 'Twig-X.XX.X/lib/' folder into the `vendor` folder.
  5. Activate `load_twig_engine` in `application/config/twiggy.php`
    * change `$config['load_twig_engine']` to `TRUE`

