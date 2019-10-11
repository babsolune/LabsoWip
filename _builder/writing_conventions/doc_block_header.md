# Writing conventions in PHPBoost CMS
## File header doc block
All files
```
/**
 * @copyright   &copy; 2005-2019 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Firstname LASTNAME [nickname@email.com]
 * @version     Last PHPBoost version - last update: YYYY MM DD (date of last modifications)
 * @since       PHPBoost version -  YYYY MM DD (when the file was created)
 * @contributor Firstname LASTNAME [nickname@email.com] (if someone other than the autor has modified the file)
*/
```
Kernel/framework files only
```
 * @package     Folder of the file
 * @subpackage  Path/to/subfolder
 * @category    Type of file : kernel - module - ...
```
External plugins
```
 * Description of the plugin -  version number
 * @copyright   &copy; 2005-2019 PHPBoost - plugin creation date - author name
 * @link        Link to the Github page of the plugin
 * @doc         Link to the website of the plugin
 *
 * @patch       What have been change for the original file to adapt to PHPBoost
```

## All PHPBoost versions
```
2005 Novembre   PHPBoost 1.3.1  
2006 Janvier    PHPBoost 1.4.0  
     Mars       PHPBoost 1.4.1  
     Juin       PHPBoost 1.5.0  
     Septembre  PHPBoost 1.6.0  
2007 Novembre   PHPBoost 2.0  
2009 Juillet    PHPBoost 3.0  
2013 Janvier    PHPBoost 4.0  
2014 Juillet    PHPBoost 4.1  
2016 Janvier    PHPBoost 5.0  
2017 Juillet    PHPBoost 5.1  
2019 Janvier    PHPBoost 5.2  
```

# Config files
## Modules
config.ini  
```
author                 = "Nickname"
author_mail            = "nickname@email.com"
author_website         = "https://www.phpboost.com"
creation_date          = "YYYY/MM/DD"
last_update            = "YYYY/MM/DD"
version                = "5.3.0" // version of the module
compatibility          = "5.2" // compatible version of PHPBoost
admin_menu             = "modules" // modules - tools - content
home_page              = "index.php"
admin_main_page        = "index.php?url=/admin"
contribution_interface = "index.php?url=/add"
php_version            = "7.3"
enabled_features       = "comments, notation, newcontent, idcard"
repository             = "https://dl.phpboost.com/unofficial_modules.xml"
rewrite_rules[]        = "RewriteRule ^...."
```
/lang/country/desc.ini  
```
name          = "Module name"
desc          = "Module description"
documentation = "https://www.link/to/page/documentation.ext"
```

## Themes
```
author            = "Nickname"
author_mail       = "nickname@email.com"
author_link       = "https://www.phpboost.com"
creation_date     = "YYYY/MM/DD"
last_update       = "YYYY/MM/DD"
version           = "5.2.0" // version of the template
compatibility     = "5.2" // compatible version of PHPBoost
html_version      = "5.0 Strict"
css_version       = "3.0"
require_copyright = "0"
columns_disabled  = "right" // right - left
variable_width    = "0"
width             = "1024px"
pictures          = "theme/images/theme.jpg,theme/images/admin.jpg"
```
/lang/country/desc.ini  
```
name       = "Template name"
desc       = "Template description"
main_color = "Color 1, color 2, ..."
```
