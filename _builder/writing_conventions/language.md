# Writing conventions in PHPBoost CMS

## Language variables
separate words by 'points (.)', to be more easily found in research and avoid underscores because of the php expressions
```
$lang['this.is.my.variable'] = 'This is my variable';
```

use tab indent for long text or several lines text
```
$lang['my.variable'] = '
    This is a long text <br />
    for a long variable.
';
```
### Modules
Use first mutualised variables if they exist (e.g. front : /lang/.../common.php, back : /lang/.../admin_common.php).  
Except for some kernel item variables, use the module folder name at the beginning of the variable.

```
// Page or place where its used (e.g Configuration, Form)
$lang['moduleFolderName.my.variable'] = 'My variable';
$lang['moduleFolderName.and'] = 'And';
$lang['moduleFolderName.other.stuff'] = 'Other stuff';
```
#### Main titles
```
// Module titles
$lang['module.title'] = 'My module';
$lang['items'] = 'items';
$lang['item'] = 'item';  
$lang['an.item'] = 'an item';  
$lang['the.item'] = 'item';  
$lang['my.items'] = 'My items';  

$lang['moduleFolderName.management'] = 'Items management';
$lang['moduleFolderName.add.item'] = 'Add an item';
$lang['moduleFolderName.edit.item'] = 'Edit an item';
$lang['moduleFolderName.feed.name'] = 'Last items';
$lang['moduleFolderName.pending.items'] = 'Pending items';

// Configuration
$lang[moduleFolderName.config.title] = 'Configuration : My module';
```
E.g in the News module
```
// Module titles
$lang['module.title'] = 'News';
$lang['items'] = 'news';
$lang['item'] = 'news';  
$lang['news.management'] = 'News management';
$lang['news.add.item'] = 'Add a news';
$lang['news.edit.item'] = 'Edit a news';
$lang['news.feed.name'] = 'Last news';
$lang['news.pending.items'] = 'Pending news';

// Configuration
$lang[news.config.title] = 'Configuration : Articles';

// Form
$lang[news.form.title] = 'News title';
```
the real aim is to get similar variables into all modules so for more, please take a look at the variables in the lang folder of the Articles module.

# Page structure
[link](./page.md)
