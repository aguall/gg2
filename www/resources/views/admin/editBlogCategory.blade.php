@extends('admin.layout')

@section('main')

	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="/master">Главная</a></li>
				<li><a href="/master/blog">Блог</a></li>
				<li><a href="/master/blog/categories">Рубрики</a></li>
				<li class="active">{{ $title }}</li>
			</ol>
		</div>
	</div>
	<div class="languages-container">
		<div class="row">
			<div class="col-md-12">
			
				@if(Session::has('success'))
					<div class="alert alert-success" role="alert">{{ Session::get('success') }}</div>
				@endif
				
				@if( $errors->any() )
					<ul class="alert alert-danger">
						@foreach( $errors->all() as $error )
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				@endif
				
				{!! Form::open(['class' => 'form-horizontal', 'role' => 'form']) !!}
				
					<div class="form-group">
						<div class="col-sm-12">
							<div class="input-group">
								<span class="input-group-addon">/</span>
								{!! Form::text('slug',  $post->slug, ['class' => 'form-control', 'placeholder' => 'URL aдрес']) !!}
							</div>
						</div>
					</div>
					
					<!-- Languages controls -->
					<div class="form-group">
						<ul class="languages-caption">
							<li class="col-md-3 active">
								<a href="#ru" class="thumbnail text-center">
									<i class="fa fa-language fa-5x"></i>
									<div class="title">Русский язык</div>
								</a>
							</li>
							<li class="col-md-3">
								<a href="#ua" class="thumbnail text-center">
									<i class="fa fa-language fa-5x"></i>
									<div class="title">Українська мова</div>
								</a>
							</li>
							<li class="col-md-3">
								<a href="#en" class="thumbnail text-center">
									<i class="fa fa-language fa-5x"></i>
									<div class="title">English language</div>
								</a>
							</li>
						</ul>
					</div>
				
					<!-- RU -->
					<div class="languages-content active">
						<!-- Nav tabs -->
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active">
								<a href="#record_ru" aria-controls="record" role="tab" data-toggle="tab">Страница</a>
							</li>
							<li role="presentation">
								<a href="#options_ru" aria-controls="options" role="tab" data-toggle="tab">Параметры страницы</a>
							</li>
						</ul>
						<!-- End nav tabs -->
						<!-- Tab panes -->
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane active" id="record_ru">
								<div class="form-group">
									{!! Form::label('name_ru', 'Название', ['class' => 'col-sm-2 control-label']) !!}
									<div class="col-sm-10">
										{!! Form::text('name_ru',  $post->name_ru, ['class' => 'form-control']) !!}
									</div>
								</div>
								<div class="form-group">
									{!! Form::label('body_ru', 'Полное описание', ['class' => 'col-sm-2 control-label']) !!}
									<div class="col-sm-10">
										{!! Form::textarea('body_ru',  $post->body_ru, ['class' => 'form-control editor']) !!}
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="options_ru">
								<div class="form-group">
									{!! Form::label('meta_title_ru', 'Заголовок', ['class' => 'col-sm-2 control-label']) !!}
									<div class="col-sm-10">
										{!! Form::text('meta_title_ru',  $post->meta_title_ru, ['class' => 'form-control']) !!}
									</div>
								</div>
								<div class="form-group">
									{!! Form::label('meta_keywords_ru', 'Ключевые слова', ['class' => 'col-sm-2 control-label']) !!}
									<div class="col-sm-10">
										{!! Form::text('meta_keywords_ru',  $post->meta_keywords_ru, ['class' => 'form-control']) !!}
									</div>
								</div>
								<div class="form-group">
									{!! Form::label('meta_description_ru', 'Описание', ['class' => 'col-sm-2 control-label']) !!}
									<div class="col-sm-10">
										<textarea name="meta_description_ru" id="meta_description_ru" class="form-control" rows="3">{{ $post->meta_description_ru }}</textarea>
									</div>
								</div>
							</div>
						</div>
						<!-- End panes -->
					</div>
				
					<!-- UA -->
					<div class="languages-content">
						<!-- Nav tabs -->
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active">
								<a href="#record_ua" aria-controls="record" role="tab" data-toggle="tab">Страница</a>
							</li>
							<li role="presentation">
								<a href="#options_ua" aria-controls="options" role="tab" data-toggle="tab">Параметры страницы</a>
							</li>
						</ul>
						<!-- End nav tabs -->
						<!-- Tab panes -->
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane active" id="record_ua">
								<div class="form-group">
									{!! Form::label('name_ua', 'Название', ['class' => 'col-sm-2 control-label']) !!}
									<div class="col-sm-10">
										{!! Form::text('name_ua',  $post->name_ua, ['class' => 'form-control']) !!}
									</div>
								</div>
								<div class="form-group">
									{!! Form::label('body_ua', 'Полное описание', ['class' => 'col-sm-2 control-label']) !!}
									<div class="col-sm-10">
										{!! Form::textarea('body_ua',  $post->body_ua, ['class' => 'form-control editor']) !!}
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="options_ua">
								<div class="form-group">
									{!! Form::label('meta_title_ua', 'Заголовок', ['class' => 'col-sm-2 control-label']) !!}
									<div class="col-sm-10">
										{!! Form::text('meta_title_ua',  $post->meta_title_ua, ['class' => 'form-control']) !!}
									</div>
								</div>
								<div class="form-group">
									{!! Form::label('meta_keywords_ua', 'Ключевые слова', ['class' => 'col-sm-2 control-label']) !!}
									<div class="col-sm-10">
										{!! Form::text('meta_keywords_ua',  $post->meta_keywords_ua, ['class' => 'form-control']) !!}
									</div>
								</div>
								<div class="form-group">
									{!! Form::label('meta_description_ua', 'Описание', ['class' => 'col-sm-2 control-label']) !!}
									<div class="col-sm-10">
										<textarea name="meta_description_ua" id="meta_description_ua" class="form-control" rows="3">{{ $post->meta_description_ua }}</textarea>
									</div>
								</div>
							</div>
						</div>
						<!-- End panes -->
					</div>
				
					<!-- EN -->
					<div class="languages-content">
						<!-- Nav tabs -->
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active">
								<a href="#record_en" aria-controls="record" role="tab" data-toggle="tab">Страница</a>
							</li>
							<li role="presentation">
								<a href="#options_en" aria-controls="options" role="tab" data-toggle="tab">Параметры страницы</a>
							</li>
						</ul>
						<!-- End nav tabs -->
						<!-- Tab panes -->
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane active" id="record_en">
								<div class="form-group">
									{!! Form::label('name_en', 'Название', ['class' => 'col-sm-2 control-label']) !!}
									<div class="col-sm-10">
										{!! Form::text('name_en',  $post->name_en, ['class' => 'form-control']) !!}
									</div>
								</div>
								<div class="form-group">
									{!! Form::label('body_en', 'Полное описание', ['class' => 'col-sm-2 control-label']) !!}
									<div class="col-sm-10">
										{!! Form::textarea('body_en',  $post->body_en, ['class' => 'form-control editor']) !!}
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="options_en">
								<div class="form-group">
									{!! Form::label('meta_title_en', 'Заголовок', ['class' => 'col-sm-2 control-label']) !!}
									<div class="col-sm-10">
										{!! Form::text('meta_title_en',  $post->meta_title_en, ['class' => 'form-control']) !!}
									</div>
								</div>
								<div class="form-group">
									{!! Form::label('meta_keywords_en', 'Ключевые слова', ['class' => 'col-sm-2 control-label']) !!}
									<div class="col-sm-10">
										{!! Form::text('meta_keywords_en',  $post->meta_keywords_en, ['class' => 'form-control']) !!}
									</div>
								</div>
								<div class="form-group">
									{!! Form::label('meta_description_en', 'Описание', ['class' => 'col-sm-2 control-label']) !!}
									<div class="col-sm-10">
										<textarea name="meta_description_en" id="meta_description_en" class="form-control" rows="3">{{ $post->meta_description_en }}</textarea>
									</div>
								</div>
							</div>
						</div>
						<!-- End panes -->
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-success">Сохранить</button>
						</div>
					</div>
				
				{!! Form::close() !!}
				
			</div>
		</div>
	</div>
	<script>
		// Переключение языков
		$('ul.languages-caption').on('click', 'li:not(.active)', function(){
			$(this).addClass('active').siblings().removeClass('active').closest('div.languages-container')
			.find('div.languages-content').removeClass('active').eq($(this).index()).addClass('active');
		});
		
		// Генерация данных
		meta_title_touched = false;
		url_touched = false;

		$('input[name="slug"]').change(function(){ url_touched = true; });

		$('input[name="name_ru"]').keyup(function(){
			if( !url_touched )
				$('input[name="slug"]').val(generate_url()); 
			if(!meta_title_touched)
				$('input[name="meta_title_ru"]').val( $('input[name="name_ru"]').val() );
		});
		
		$('input[name="meta_title_ru"]').change(function(){ meta_title_touched = true; });

		function generate_url(){
			url = $('input[name="name_ru"]').val();
			url = url.replace(/[\s]+/gi, '-');
			url = translit(url);
			url = url.replace(/[^0-9a-z_\-]+/gi, '').toLowerCase();	
			return url;
		}

		function translit(str){
			var ru=("А-а-Б-б-В-в-Ґ-ґ-Г-г-Д-д-Е-е-Ё-ё-Є-є-Ж-ж-З-з-И-и-І-і-Ї-ї-Й-й-К-к-Л-л-М-м-Н-н-О-о-П-п-Р-р-С-с-Т-т-У-у-Ф-ф-Х-х-Ц-ц-Ч-ч-Ш-ш-Щ-щ-Ъ-ъ-Ы-ы-Ь-ь-Э-э-Ю-ю-Я-я").split("-")   
			var en=("A-a-B-b-V-v-G-g-G-g-D-d-E-e-E-e-E-e-ZH-zh-Z-z-I-i-I-i-I-i-J-j-K-k-L-l-M-m-N-n-O-o-P-p-R-r-S-s-T-t-U-u-F-f-H-h-TS-ts-CH-ch-SH-sh-SCH-sch-'-'-Y-y-'-'-E-e-YU-yu-YA-ya").split("-")   
			var res = '';
			for(var i=0, l=str.length; i<l; i++){ 
				var s = str.charAt(i), n = ru.indexOf(s);
				if(n >= 0) { res += en[n]; } 
				else { res += s; } 
			} 
			return res;
		}
	</script>
	
	@include('admin.tinymceInit')
	
@stop