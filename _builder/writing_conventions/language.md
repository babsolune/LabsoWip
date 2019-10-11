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
Use first mutualised variables if they exist (front : /lang/.../common.php, back : /lang/.../admin_common.php)
```
// Page or place where its used (e.g Configuration, Form)
$lang['moduleFolderName.my.variable'] = 'My variable';
$lang['moduleFolderName.and'] = 'And';
$lang['moduleFolderName.other.stuff'] = 'Other stuff';
```
#### Main titles
```
// Titles
$lang['moduleFolderName.module.title'] = 'My module';
$lang['moduleFolderName.items'] = 'items';
$lang['moduleFolderName.item'] = 'item';  
$lang['moduleFolderName.management'] = 'Items management';
$lang['moduleFolderName.add.item'] = 'Add an item';
$lang['moduleFolderName.edit.item'] = 'Edit an item';
$lang['moduleFolderName.feed.name'] = 'Last items';
$lang['moduleFolderName.pending.items'] = 'Pending items';

// Configuration
$lang[moduleFolderName.module.config.title] = 'Configuration : My module';
```
E.g in the Articles module
```
// Titles
$lang['articles.module.title'] = 'Articles';
$lang['articles.items'] = 'articles';
$lang['articles.item'] = 'article';  
$lang['articles.management'] = 'Articles management';
$lang['articles.add.item'] = 'Add an article';
$lang['articles.edit.item'] = 'Edit an article';
$lang['articles.feed.name'] = 'Last articles';
$lang['articles.pending.items'] = 'Pending articles';

// Configuration
$lang[articles.module.config.title] = 'Configuration : Articles';
```
the real aim is to get similar variables into all modules so for more, please take a look at the variables in the lang folder of the Articles module.

# Page structure
[link](./page.md)
