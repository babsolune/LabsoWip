# Les changements à appliquer sur les fichiers tpl/js
- Effectuer une recherche sur `title="` en filtrant uniquement les fichiers tpl/js (!\*.php)
- si title n'apporte aucune plus value => suppression (meme def que alt ou texte frere)  
- si aria-label sur parent => suppression  
- sinon remplacé par aria-label  

## fichiers trouvés par la recherche sur PHPBoost 5.2
- ARTICLES
    - articles\templates\ArticlesDisplayArticlesController.tpl
    - articles\templates\ArticlesDisplaySeveralArticlesController.tpl

- BBCODE
    - BBCode\templates\bbcode_editor.tpl
    - BBCode\templates\js\bbcode.js

- BUGTRACKER
    - bugtracker\templates\AdminBugtrackerCategoriesListController.tpl
    - bugtracker\templates\AdminBugtrackerTypesListController.tpl
    - bugtracker\templates\AdminBugtrackerVersionsListController.tpl
    - bugtracker\templates\BugtrackerDetailController.tpl
    - bugtracker\templates\BugtrackerFilter.tpl
    - bugtracker\templates\BugtrackerListController.tpl
    - bugtracker\templates\BugtrackerRoadmapListController.tpl

- CALENDAR
    - calendar\templates\CalendarAjaxCalendarController.tpl
    - calendar\templates\CalendarAjaxEventsController.tpl
    - calendar\templates\CalendarDisplayEventController.tpl
    - calendar\templates\CalendarDisplaySeveralEventsController.tpl

- CONNECT
    - connect\templates\connect_mini.tpl

- CONTACT
    - contact\templates\AdminContactFieldsListController.tpl
    - contact\templates\ContactFormFieldObjectPossibleValues.tpl
    - contact\templates\ContactFormFieldRecipientsPossibleValues.tpl

- DATABASE
    - database\templates\admin_database_management.tpl
    - database\templates\admin_database_tools.tpl

- DOWNLOAD
    - download\templates\DownloadDisplayDownloadFileController.tpl
    - download\templates\DownloadDisplaySeveralDownloadFilesController.tpl
    - download\templates\DownloadModuleMiniMenu.tpl

- FAQ
    - faq\templates\FaqDisplaySeveralFaqQuestionsController.tpl
    - faq\templates\FaqModuleMiniMenu.tpl
    - faq\templates\FaqReorderCategoryQuestionsController.tpl

- FORUM
    - forum\templates\admin_ranks_add.tpl
    - forum\templates\admin_ranks.tpl
    - forum\templates\forum_alert.tpl
    - forum\templates\forum_bottom.tpl
    - forum\templates\forum_edit_msg.tpl
    - forum\templates\forum_forum.tpl
    - forum\templates\forum_generic_results.tpl
    - forum\templates\forum_index.tpl
    - forum\templates\forum_membermsg.tpl
    - forum\templates\forum_moderation_panel.tpl
    - forum\templates\forum_post.tpl
    - forum\templates\forum_top.tpl
    - forum\templates\forum_topic.tpl
    - forum\templates\forum_track.tpl

- GALLERY
    - gallery\templates\admin_gallery_add.tpl
    - gallery\templates\admin_gallery_config.tpl
    - gallery\templates\admin_gallery_management.tpl
    - gallery\templates\gallery_add.tpl
    - gallery\templates\gallery_mini.tpl
    - gallery\templates\gallery.tpl

- GOOGLEMAPS
    - GoogleMaps\templates\GoogleMapsFormFieldMultipleMarkers.tpl

- GUESTBOOK
    - guestbook\templates\GuestbookController.tpl

- INSTALL
    - install\templates\database.tpl
    - install\templates\main.tpl
    - install\templates\server-config.tpl
    - install\templates\welcome.tpl

- LANGSSWITCHER
    - LangsSwitcher\templates\langswitcher.tpl

- MEDIA
    - media\templates\media_action.tpl
    - media\templates\media.tpl
    - media\templates\moderation_media.tpl

- NEWS
    - news\templates\NewsDisplayNewsController.tpl
    - news\templates\NewsDisplaySeveralNewsController.tpl

- NEWSLETTER
    - newsletter\templates\NewsletterArchivesController.tpl
    - newsletter\templates\NewsletterHomeController.tpl
    - newsletter\templates\NewsletterSubscribersListController.tpl

- ONLINE
    - online\templates\OnlineHomeController.tpl

- PAGES
    - pages\templates\action.tpl
    - pages\templates\admin_pages.tpl
    - pages\templates\index.tpl
    - pages\templates\page.tpl

- POLL
    - poll\templates\admin_poll_add.tpl
    - poll\templates\admin_poll_config.tpl
    - poll\templates\admin_poll_management.tpl
    - poll\templates\admin_poll_management2.tpl
    - poll\templates\poll_mini.tpl
    - poll\templates\poll.tpl

- QUESTIONCAPTCHA
    - QuestionCaptcha\templates\QuestionCaptchaFormFieldQuestions.tpl

- SEARCH
    - search\templates\admin_search.tpl
    - search\templates\search_mini.tpl

- SHOUTBOX
    - shoutbox\templates\ShoutboxAjaxMessagesBoxController.tpl
    - shoutbox\templates\ShoutboxHomeController.tpl
    - shoutbox\templates\ShoutboxModuleMiniMenu.tpl

- SITEMAP
    - sitemap\templates\export\module_map.html.tpl

- SOCIALNETWORKS
    - SocialNetworks\templates\AdminSocialNetworksConfigController.tpl

- STATS
    - stats\templates\admin_stats_management.tpl
    - stats\templates\stats.tpl

- TEMPLATES/base
    - templates\base\body.tpl

- TEMPLATES/default
    - templates\default\admin\admin_extend.tpl
    - templates\default\admin\admin_files_management.tpl
    - templates\default\admin\admin_files_move.tpl
    - templates\default\admin\AdminMenuDisplayResponse.tpl
    - templates\default\admin\langs\AdminLangsInstalledListController.tpl
    - templates\default\admin\langs\AdminLangsNotInstalledListController.tpl
    - templates\default\admin\member\AdminExtendedFieldsMemberlistController.tpl
    - templates\default\admin\menus\configuration\edit.tpl
    - templates\default\admin\menus\filters.tpl
    - templates\default\admin\menus\links.tpl
    - templates\default\admin\menus\menu_edition.tpl
    - templates\default\admin\menus\menu.tpl
    - templates\default\admin\menus\panel.tpl
    - templates\default\admin\modules\AdminModuleAddController.tpl
    - templates\default\admin\modules\AdminModulesManagementController.tpl
    - templates\default\admin\modules\AdminModuleUpdateController.tpl
    - templates\default\admin\themes\AdminThemesInstalledListController.tpl
    - templates\default\admin\themes\AdminThemesNotInstalledListController.tpl
    - templates\default\admin\updates\detail.tpl
    - templates\default\admin\updates\updates.tpl
    - templates\default\framework\builder\form\fieldelements\FormFieldSelectSources.tpl
    - templates\default\framework\builder\form\FormFieldMultipleAutocompleter.tpl
    - templates\default\framework\builder\form\FormFieldMultipleFilePicker.tpl
    - templates\default\framework\builder\form\FormFieldPossibleValues.tpl
    - templates\default\framework\builder\form\FormFieldUploadFile.tpl
    - templates\default\framework\content\categories\category.tpl
    - templates\default\framework\content\comments\comments_list.tpl
    - templates\default\framework\content\notation\notation.tpl
    - templates\default\framework\content\share\ContentSharingActionsMenu.tpl
    - templates\default\framework\content\share\ContentSharingActionsMenuLink.tpl
    - templates\default\framework\content\syndication\feed_with_images.tpl
    - templates\default\framework\content\syndication\menu.tpl
    - templates\default\framework\helper\message.tpl
    - templates\default\framework\menus\links.tpl
    - templates\default\framework\util\mini_calendar_response.tpl
    - templates\default\framework\util\mini_calendar.tpl
    - templates\default\framework\util\pagination.tpl
    - templates\default\maintain.tpl

- THEMESSWITCHER
    - ThemesSwitcher\templates\themeswitcher.tpl

- UPDATE
    - update\templates\database.tpl
    - update\templates\introduction.tpl
    - update\templates\server-config.tpl

- USER
    - user\templates\contribution_panel.tpl
    - user\templates\js\cookiebar.js
    - user\templates\moderation_panel.tpl
    - user\templates\pm.tpl
    - user\templates\upload_move.tpl
    - user\templates\upload.tpl
    - user\templates\UserError403Controller.tpl
    - user\templates\UserError404Controller.tpl
    - user\templates\UserErrorController.tpl
    - user\templates\UserExploreGroupsController.tpl
    - user\templates\UserHomeProfileController.tpl
    - user\templates\UserLoginController.tpl
    - user\templates\UserViewProfileController.tpl

- WEB
    - web\templates\WebDisplaySeveralWebLinksController.tpl
    - web\templates\WebDisplayWebLinkController.tpl
    - web\templates\WebModuleMiniMenu.tpl

- WIKI
    - wiki\templates\admin_wiki_groups.tpl
    - wiki\templates\admin_wiki.tpl
    - wiki\templates\history.tpl
    - wiki\templates\index.tpl
    - wiki\templates\post.tpl
    - wiki\templates\property.tpl
    - wiki\templates\wiki_js_tools.tpl
    - wiki\templates\wiki.tpl
