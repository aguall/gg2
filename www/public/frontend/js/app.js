$(document).ready(function(){
	
	// Сегменты URL
	var segments = window.location.pathname.split( '/' );
	
	// Языки
	$('.languages .current').click(function(event){
		event.preventDefault();
		$('.languages').find('ul').toggle();
	});
	$(document).on('click', function(event){
		if( !$(event.target).closest('.languages').length )
			$('.languages').find('ul').hide();
		event.stopPropagation();
	});
	
	// AJAAX форма входа
	$('a.login').magnificPopup({
		type: 'inline',
		preloader: false
	});
	$('#form-login').submit(function(event){
		event.preventDefault();
		$.ajax({
			type: $(this).attr('method'),
			url: $(this).attr('action'),
			dataType: 'JSON',
			data: $(this).serialize(),
			success: function(data){
				if( data.errors )
					$('#form-login .message').html('<div class="alert danger">' + data.errors + '</div>');
				else
				{
					var segment  = '/profile';
					if( segments[1] == 'en' || segments[1] == 'ua' )
						segment  = '/' + segments[1] + '/profile';
					window.location.replace( window.location.protocol + '//' + window.location.hostname + segment );
				}
			},
			error: function(data){
				var errors = data.responseJSON,
					errorsHTML = '';
				$.each(errors, function(index, value) {
					errorsHTML += '<li>' + value + '</li>';
				});
				$('#form-login .message').html('<div class="alert danger"><ul>' + errorsHTML + '</ul></div>');
			}
		});
	});
	
	// Кнопка "Развернуть" Топ 10
	if( $('.shops li').size() > 4 ){
		
		// Скрываем остальные элементы в списке после 5-го
		$('.shops li').each(function(counter){
			if( counter == 4 )
				$(this).css('borderBottom', 'none');
			if( counter > 4 )
				$(this).hide();
		});
		
		// Добавляем кнопку
		var buttonExpandText = 'Развернуть';
		switch( segments[1] )
		{
			case 'ua':
				buttonExpandText = 'Розгорнути';
			break;
			case 'en':
				buttonExpandText = 'Show more';
			break;	
		}

		$('.shops').after('<a href="#" class="expand">' + buttonExpandText + '</a>');
		$('.expand').on('click', function(event){
			event.preventDefault();
			$('.shops li').each(function(counter){
				if( $(this).attr('style') !== undefined )
					$(this).removeAttr('style');
			});
			$(this).remove();
		});
	}
	
	// Показать/скрыть каталог
	if( $('.catalog span.hidden').length > 0 ){
		$('span.hidden').on('click', function(){
			$('.catalog').find(' > ul').slideToggle( 'fast', function(){
				$('span.hidden').toggleClass('off', $(this).is(':visible'));
			});
		});
	}
	
	// Сортировка
	if( $('.sorting').length > 0 ){
		$('.sorting .item > a').on('click', function(event){
			event.preventDefault();
			$(this).parent().find('ul').toggle();
		});
		$(document).on('click', function(event){
			if( !$(event.target).closest('.sorting').length )
				$('.sorting ul').hide();
			event.stopPropagation();
		});
	}
	
	// Скрытие текста
	if( $('.hidden-text').length ){
		
		// Скрываем основной текст
		$('.hidden-text').hide();

		// Добавляем ссылку "Читать далее"
		$('.hidden-text').after('<p><a href="#" class="more">Читать далее</a></p>');

		// Покажем текст и сразу удалим ссылку
		$('.more').on('click', function(event){
			event.preventDefault();
			$('.hidden-text').show();
			$(this).remove();
		});
	}
	
	if( $('.scroll-pane').length > 0 ){
		
		// Скролинг для фильтров
		$('.scroll-pane').jScrollPane();
	
		// Подчеркивание названия фильтра, если выбран
		$('.scroll-pane label').each(function(){
			if( $(this).find('input[type="checkbox"]').prop('checked') )
				$(this).find('span.name').css({ 'border-bottom': '1px solid #666'});
		});
		$('.scroll-pane input[type="checkbox"]').on('change', function() {
			if( $(this).prop('checked') )
				$(this).parent().find('span.name').css({ 'border-bottom': '1px solid #666'});
			else
				$(this).parent().find('span.name').css({ 'border-bottom': 'none'});
		});
	}
	
	// Продукция
	if( $('.product').length > 0 ){
		
		// Галерея
		$('.product .thumb').magnificPopup({
			delegate: 'a.photo',
			type: 'image',
			tLoading: 'Загрузка изображения #%curr%...',
			mainClass: 'mfp-img-mobile',
			gallery: {
				enabled: true,
				navigateByImgClick: true,
				preload: [0, 1]
			},
			image: {
				tError: '<a href="%url%">Изображение #%curr%</a> не агружено.'
			}
		});
		
		// Табы
		$('ul.caption').on('click', 'li:not(.active)', function(){
			$(this).addClass('active').siblings().removeClass('active').closest('div.tabs').find('div.tabs-content').removeClass('active').eq($(this).index()).addClass('active');
		});
		$('ul.caption li').first().addClass('active');
		$('.tabs-content').first().addClass('active');
	}
	
	// Личный кабинет
	if( $('.user-info').length > 0 ){

		// Языки визульного редактора
		var lang = 'en';
		if( segments[1] == 'ua' )
			lang = 'uk';
		else if( segments[1] == 'profile' )
			lang = 'ru';
		
		// Редактирование личной информации
		$('a.edit-info').magnificPopup({
			type: 'inline',
			preloader: false
		});
		
		// Добавление статьи
		$('a.add-article').magnificPopup({
			type: 'inline',
			preloader: false,
			callbacks: {
				open: function(){
					tinyMCE.init({ 
						mode: 'specific_textareas',
						editor_selector: 'mceEditor',
						language: lang,
						height: 200
					});
				},
				close: function(){
					tinyMCE.remove();
				}
			}
		});
		
		// Добавление видео
		$('a.add-video').magnificPopup({
			type: 'inline',
			preloader: false
		});
	}
	
	// Фильтрация
	if( $('.user-selection').length > 0 )
	{
		// Удаление из фильтрации по одному
		$('.user-selection .filters-del a').click(function(event){
			event.preventDefault();
			$(".filters-block input[id='" + $(this).data('id') + "']").removeAttr('checked').closest('form').submit();
			return false;
		});
		
		// Удаление из фильтрации всх параметров
		/*
		$('.filters-del-all').click(function(event){
			event.preventDefault();
			$(".filters-block input").removeAttr('checked').closest('form').submit();
		});
		*/
	}
	
	// Рейтинг магазина +1
	if( $('.in-store').length > 0 )
	{
		$('.in-store').click(function(event){
			event.preventDefault();
			$.post(window.location.href, { _token: $(this).data('token'), shop_id: $(this).data('shop-id') });
			window.open($(this).data('href'),'_blank');
		});
	}
	
	// Скрываем/показываем списки на карте сайта
	if( $('#category-list').length > 0 || $('#blog-list').length > 0 )
	{
		$('#category-list, #blog-list').treeview({
			persist: 'location',
			collapsed: true,
			unique: true
		});
	}
});