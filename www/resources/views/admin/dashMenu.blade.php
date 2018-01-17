<li @if( Request::segment(2) == 'menu' ) class="active" @endif>
	<a href="/master/menu"><i class="fa fa-bars"></i> Меню</a>
</li>
<li @if( Request::segment(2) == 'pages' ) class="active" @endif>
	<a href="/master/pages"><i class="fa fa-file-text"></i> Страницы</a>
</li>
<li @if( Request::segment(2) == 'blog' ) class="active" @endif>
	<a href="/master/blog"><i class="fa fa-file-text"></i> Блог</a>
</li>
<li @if( Request::segment(2) == 'shops' ) class="active" @endif>
	<a href="/master/shops"><i class="fa fa-shopping-bag"></i> Список магазинов</a>
</li>
<li @if( Request::segment(2) == 'shop' ) class="active" @endif>
	<a href="/master/shop"><i class="fa fa-shopping-cart"></i> Магазин</a>
</li>
<li @if( Request::segment(2) == 'seo' ) class="active" @endif>
	<a href="/master/seo"><i class="fa fa-line-chart"></i> SEO модуль</a>
</li>
<li @if( Request::segment(2) == 'advertising' ) class="active" @endif>
	<a href="/master/advertising"><i class="fa fa-television"></i> Реклама</a>
</li>
<li @if( Request::segment(2) == 'users' ) class="active" @endif>
	<a href="/master/users"><i class="fa fa-users"></i> Пользователи</a>
</li>