<?php

// Шаблоны
Route::pattern('provider', '(vkontakte|facebook|google)');
Route::pattern('id', '[0-9]+');
Route::pattern('option_id', '[0-9]+');
Route::pattern('page_url', '[0-9a-z-_]+');
Route::pattern('product_url', '[0-9a-z-A-Z-_]+');
Route::pattern('category_url', '[0-9a-z-_]+');

// Панель администратора
Route::group(['prefix' => 'master', 'middleware' => 'auth'], function(){
	
	// Консоль
	Route::get('/', 'Admin\AdminHomeController@index');
	
	// Настройки
	Route::get('/settings', 'Admin\AdminSettingsController@index' );
	Route::post('/settings', 'Admin\AdminSettingsController@postEdit' );
	Route::post('/settings/sitemap', 'Admin\AdminSettingsController@getSitemapXML' );
	
	// Меню
	Route::get('/menu', 'Admin\AdminMenuController@index');
	Route::post('/menu', 'Admin\AdminMenuController@deleteMenu');
	Route::get('/menu/edit/{id}', 'Admin\AdminMenuController@editPost');
	Route::post('/menu/edit/{id}', 'Admin\AdminMenuController@postEdit');
	Route::get('/menu/add', 'Admin\AdminMenuController@getAdd');
	Route::post('/menu/add', 'Admin\AdminMenuController@postAdd');
	Route::post('/menu/visible', 'Admin\AdminMenuController@visibleMenuItem');
	Route::post('/menu/sortable', 'Admin\AdminMenuController@sortableMenu');
	
	// Страницы
	Route::get('/pages', 'Admin\AdminPagesController@index');
	Route::post('/pages', 'Admin\AdminPagesController@deletePages');
	Route::get('/pages/edit/{id}', 'Admin\AdminPagesController@editPost');
	Route::post('/pages/edit/{id}', 'Admin\AdminPagesController@postEdit');
	Route::get('/pages/add', 'Admin\AdminPagesController@getAdd');
	Route::post('/pages/add', 'Admin\AdminPagesController@postAdd');
	
	// Блог
	Route::get('/blog', 'Admin\AdminBlogController@index');
	Route::get('/blog/categories', 'Admin\AdminBlogController@getBlogCategories');
	Route::post('/blog/categories', 'Admin\AdminBlogController@deleteBlogCategories');
	Route::get('/blog/categories/add', 'Admin\AdminBlogController@getBlogCategoryAdd');
	Route::post('/blog/categories/add', 'Admin\AdminBlogController@postBlogCategoryAdd');
	Route::get('/blog/categories/edit/{id}', 'Admin\AdminBlogController@getBlogCategoryEdit');
	Route::post('/blog/categories/edit/{id}', 'Admin\AdminBlogController@postBlogCategoryEdit');
	Route::get('/blog/posts', 'Admin\AdminBlogController@getBlogPosts');
	Route::post('/blog/posts', 'Admin\AdminBlogController@deleteBlogPosts');
	Route::get('/blog/posts/edit/{id}', 'Admin\AdminBlogController@getBlogPostEdit');
	Route::post('/blog/posts/edit/{id}', 'Admin\AdminBlogController@postBlogPostEdit');
	Route::get('/blog/posts/add', 'Admin\AdminBlogController@getBlogPostAdd');
	Route::post('/blog/posts/add', 'Admin\AdminBlogController@postBlogPostAdd');
	Route::post('/blog/posts/visible', 'Admin\AdminBlogController@visibleBlogPost');
	
	// Магазин
	Route::get('/shop', 'Admin\AdminShopController@index');
	/* Категории товаров */
	Route::get('/shop/categories', 'Admin\AdminProductsCategoriesController@index');
	Route::post('/shop/categories', 'Admin\AdminProductsCategoriesController@actionCategories');
	Route::get('/shop/categories/add', 'Admin\AdminProductsCategoriesController@getProductsCategoryAdd');
	Route::post('/shop/categories/add', 'Admin\AdminProductsCategoriesController@postProductsCategoryAdd');
	Route::get('/shop/categories/edit/{id}', 'Admin\AdminProductsCategoriesController@getProductsCategoryEdit');
	Route::post('/shop/categories/edit/{id}', 'Admin\AdminProductsCategoriesController@postProductsCategoryEdit');
	/* Параметры фильтрации (группы) */
	Route::get('/shop/filters', 'Admin\AdminShopController@getFilters');
	Route::get('/shop/filters/features-groups', 'Admin\AdminProductsFeaturesController@index');
	Route::post('/shop/filters/features-groups', 'Admin\AdminProductsFeaturesController@deleteProductsFeaturesGroups');
	Route::get('/shop/filters/features-groups/add', 'Admin\AdminProductsFeaturesController@getProductsFeaturesGroupAdd');
	Route::post('/shop/filters/features-groups/add', 'Admin\AdminProductsFeaturesController@postProductsFeaturesGroupAdd');
	Route::get('/shop/filters/features-groups/edit/{id}', 'Admin\AdminProductsFeaturesController@getProductsFeaturesGroupEdit');
	Route::post('/shop/filters/features-groups/edit/{id}', 'Admin\AdminProductsFeaturesController@postProductsFeaturesGroupEdit');
	/* Параметры фильтрации */
	Route::get('/shop/filters/features', 'Admin\AdminProductsFeaturesController@getProductsFeatures');
	Route::post('/shop/filters/features', 'Admin\AdminProductsFeaturesController@deleteProductsFeatures');
	Route::get('/shop/filters/features/add', 'Admin\AdminProductsFeaturesController@getProductsFeatureAdd');
	Route::post('/shop/filters/features/add', 'Admin\AdminProductsFeaturesController@postProductsFeatureAdd');
	Route::get('/shop/filters/features/edit/{id}', 'Admin\AdminProductsFeaturesController@getProductsFeatureEdit');
	Route::post('/shop/filters/features/edit/{id}', 'Admin\AdminProductsFeaturesController@postProductsFeatureEdit');
	/* Опции параметров */
	Route::get('/shop/filters/features-options/{id}', 'Admin\AdminProductsOptionsController@index');
	Route::post('/shop/filters/features-options/{id}', 'Admin\AdminProductsOptionsController@deleteProductsOptions');
	Route::get('/shop/filters/features-options/{id}/add', 'Admin\AdminProductsOptionsController@getProductsOptionAdd');
	Route::post('/shop/filters/features-options/{id}/add', 'Admin\AdminProductsOptionsController@postProductsOptionAdd');
	Route::get('/shop/filters/features-options/{id}/edit/{option_id}', 'Admin\AdminProductsOptionsController@getProductsOptionEdit');
	Route::post('/shop/filters/features-options/{id}/edit/{option_id}', 'Admin\AdminProductsOptionsController@postProductsOptionEdit');
	/* Товары */
	Route::get('/shop/products', 'Admin\AdminProductsController@index');
	Route::post('/shop/products', 'Admin\AdminProductsController@deleteProducts');
	Route::get('/shop/products/add', 'Admin\AdminProductsController@getProductAdd');
	Route::post('/shop/products/add', 'Admin\AdminProductsController@postProductAdd');
	Route::get('/shop/products/edit/{id}', 'Admin\AdminProductsController@getProductEdit');
	Route::post('/shop/products/edit/{id}', 'Admin\AdminProductsController@postProductEdit');
	Route::post('/shop/products/visible', 'Admin\AdminProductsController@visibleProduct');
	Route::post('/shop/products/advertising', 'Admin\AdminProductsController@advertisingProduct');
	Route::post('/shop/products/sortable', 'Admin\AdminProductsController@sortableProducts');
	Route::get('/shop/products/xml-products-not-delete', 'Admin\AdminProductsController@getProductsNotDelete');
	/* Выбор опций для товара */
	Route::get('/shop/products/options/{id}', 'Admin\AdminProductsController@getProductOptions');
	Route::post('/shop/products/options/{id}', 'Admin\AdminProductsController@postProductOptions');
	/* Изображения товара */
	Route::get('/shop/products/images/{id}', 'Admin\AdminProductsController@getProductImages');
	Route::post('/shop/products/images/{id}', 'Admin\AdminProductsController@postProductImages');
	/* Видеообзоры товара */
	Route::get('/shop/products/video/{id}', 'Admin\AdminProductsController@getProductVideo');
	Route::post('/shop/products/video/{id}', 'Admin\AdminProductsController@postProductVideo');
	Route::get('/shop/products/video/{id}/add', 'Admin\AdminProductsController@getProductVideoAdd');
	Route::post('/shop/products/video/{id}/add', 'Admin\AdminProductsController@postProductVideoAdd');
	Route::get('/shop/products/video/{id}/edit/{option_id}', 'Admin\AdminProductsController@getProductVideoEdit');
	Route::post('/shop/products/video/{id}/edit/{option_id}', 'Admin\AdminProductsController@postProductVideoEdit');
	/* Цены товара в магазинах */
	Route::get('/shop/products/prices/{id}', 'Admin\AdminProductsController@getProductPrices');
	Route::post('/shop/products/prices/{id}', 'Admin\AdminProductsController@postProductPrices');
	Route::get('/shop/products/prices/{id}/add', 'Admin\AdminProductsController@getProductPriceAdd');
	Route::post('/shop/products/prices/{id}/add', 'Admin\AdminProductsController@postProductPriceAdd');
	Route::get('/shop/products/prices/{id}/edit/{option_id}', 'Admin\AdminProductsController@getProductPriceEdit');
	Route::post('/shop/products/prices/{id}/edit/{option_id}', 'Admin\AdminProductsController@postProductPriceEdit');
	/* XML парсер */
	Route::get('/shop/parser', 'Admin\AdminShopController@getParser');
	Route::get('/shop/parser/control', 'Admin\AdminShopsXMLController@index');
	Route::post('/shop/parser/control', 'Admin\AdminShopsXMLController@deleteShopsXML');
	Route::get('/shop/parser/control/add', 'Admin\AdminShopsXMLController@getShopXMLAdd');
	Route::post('/shop/parser/control/add', 'Admin\AdminShopsXMLController@postShopXMLAdd');
	Route::get('/shop/parser/control/edit/{id}', 'Admin\AdminShopsXMLController@getShopXMLEdit');
	Route::post('/shop/parser/control/edit/{id}', 'Admin\AdminShopsXMLController@postShopXMLEdit');
	Route::get('/shop/parser/process', 'Admin\AdminShopsXMLController@getShopXMLProcess');
	Route::post('/shop/parser/process', 'Admin\AdminShopsXMLController@postShopXMLProcess');
	Route::post('/shop/parser/process-status', 'Admin\AdminShopsXMLController@postShopXMLProcessStatus');
	Route::post('/shop/parser/remove-price', 'Admin\AdminShopsXMLController@postShopXMLRemovePrice');
	
	// Магазины
	Route::get('/shops', 'Admin\AdminShopsController@index');
	Route::post('/shops', 'Admin\AdminShopsController@deleteShops');
	Route::get('/shops/add', 'Admin\AdminShopsController@getShopAdd');
	Route::post('/shops/add', 'Admin\AdminShopsController@postShopAdd');
	Route::get('/shops/edit/{id}', 'Admin\AdminShopsController@getShopEdit');
	Route::post('/shops/edit/{id}', 'Admin\AdminShopsController@postShopEdit');
	Route::post('/shops/visible', 'Admin\AdminShopsController@visibleShop');
	
	// Пользователи
	Route::get('/users', 'Admin\AdminUsersController@index');
	Route::get('/users/show', 'Admin\AdminUsersController@getUsers');
	Route::post('/users/show', 'Admin\AdminUsersController@deleteUsers');
	Route::get('/users/edit/{id}', 'Admin\AdminUsersController@getEdit');
	Route::post('/users/edit/{id}', 'Admin\AdminUsersController@postEdit');
	Route::get('/users/add', 'Admin\AdminUsersController@getAdd');
	Route::post('/users/add', 'Admin\AdminUsersController@postAdd');
	Route::get('/users/video', 'Admin\AdminUsersController@getUsersVideo');
	Route::post('/users/video', 'Admin\AdminUsersController@deleteUsersVideo');
	Route::post('/users/video-info', 'Admin\AdminUsersController@getInfoUserVideo');
	Route::get('/users/articles', 'Admin\AdminUsersController@getUsersArticles');
	Route::post('/users/articles', 'Admin\AdminUsersController@deleteUsersArticles');
	Route::post('/users/article-info', 'Admin\AdminUsersController@getInfoUserArticle');

	// SEO модуль
	Route::get('/seo', 'Admin\AdminSeoController@index');
	Route::post('/seo', 'Admin\AdminSeoController@deletePosts');
	Route::get('/seo/edit/{id}', 'Admin\AdminSeoController@editPost');
	Route::post('/seo/edit/{id}', 'Admin\AdminSeoController@postEdit');
	Route::get('/seo/add', 'Admin\AdminSeoController@getAdd');
	Route::post('/seo/add', 'Admin\AdminSeoController@postAdd');

	// Реклама
	Route::get('/advertising', 'Admin\AdminAdvertisingController@index');
	/* Баннеры */
	Route::get('/advertising/banners', 'Admin\AdminBannersController@index');
	Route::post('/advertising/banners', 'Admin\AdminBannersController@deleteBanners');
	Route::get('/advertising/banners/add', 'Admin\AdminBannersController@getBannerAdd');
	Route::post('/advertising/banners/add', 'Admin\AdminBannersController@postBannerAdd');
	Route::get('/advertising/banners/edit/{id}', 'Admin\AdminBannersController@getBannerEdit');
	Route::post('/advertising/banners/edit/{id}', 'Admin\AdminBannersController@postBannerEdit');
	Route::post('/advertising/banners/visible', 'Admin\AdminBannersController@visibleBanner');
	Route::post('/advertising/banners/sortable', 'Admin\AdminBannersController@sortableBanners');
	/* Друзья и партнеры */
	Route::get('/advertising/friends-and-partners', 'Admin\AdminFriendsAndPartnersController@index');
	Route::post('/advertising/friends-and-partners', 'Admin\AdminFriendsAndPartnersController@deletePartners');
	Route::get('/advertising/friends-and-partners/add', 'Admin\AdminFriendsAndPartnersController@getPartnerAdd');
	Route::post('/advertising/friends-and-partners/add', 'Admin\AdminFriendsAndPartnersController@postPartnerAdd');
	Route::get('/advertising/friends-and-partners/edit/{id}', 'Admin\AdminFriendsAndPartnersController@getPartnerEdit');
	Route::post('/advertising/friends-and-partners/edit/{id}', 'Admin\AdminFriendsAndPartnersController@postPartnerEdit');
	Route::post('/advertising/friends-and-partners/visible', 'Admin\AdminFriendsAndPartnersController@visiblePartner');
	Route::post('/advertising/friends-and-partners/sortable', 'Admin\AdminFriendsAndPartnersController@sortablePartners');
});

Route::group([ 'prefix' => LaravelLocalization::setLocale() ], function(){
	
	// Дефолтная авторизация/регистрация
	Route::controllers([
		'auth' 		=> 'Auth\AuthController',
		'password' 	=> 'Auth\PasswordController',
	]);
	
	// Главная страница
	Route::get('/', 'Frontend\HomeController@index');
	
	// Поиск по сайту
	Route::any('/search', 'Frontend\SearchController@index');

	// Личный кабинет
	Route::get('/profile', 'Frontend\ProfileController@index');
	Route::post('/profile', 'Frontend\ProfileController@postEditInfo');
	Route::get('/register', 'Frontend\ProfileController@getRegister');
	Route::post('/register', 'Frontend\ProfileController@postRegister');
	Route::post('/login', 'Frontend\ProfileController@postLogin');
	Route::get('/logout', 'Frontend\ProfileController@logout');
	Route::get('/socialite/{provider}', 'Frontend\ProfileController@redirectToProvider');
	Route::get('/socialite/{provider}/callback', 'Frontend\ProfileController@handleProviderCallback');
	Route::post('/add-user-video', 'Frontend\ProfileController@postAddVideo');
	Route::post('/add-user-article', 'Frontend\ProfileController@postAddArticle');
	
	// Блог
	Route::get('/blog/{category_url}', 'Frontend\BlogController@index');
	Route::get('/blog/{category_url}/{page_url}', 'Frontend\BlogController@getPost');
	
	// Категории товаров
	Route::get('/category/{category_url}', 'Frontend\ShopController@index');

	// Карточка товара
	Route::any('/product/{product_url}', 'Frontend\ShopController@getProduct');
	
	// Магазины
	Route::get('/shops', 'Frontend\ShopsController@index');
	Route::get('/shops/{page_url}', 'Frontend\ShopsController@getShop');
	
	// Карта сайта
	Route::get('/sitemap', 'Frontend\SitemapController@index');
	
	// Страницы
	Route::get('/{page_url}','Frontend\PageController@index');
});