@extends('admin.layout')

@section('main')

	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="/master">Главная</a></li>
				<li><a href="/master/shop">Магазин</a></li>
				<li class="active">{{ $title }}</li>
			</ol>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<a href="/master/shop/categories/add" class="thumbnail text-center">
				<i class="fa fa-plus-circle fa-5x"></i>
				<div class="title">Добавить категорию</div>
			</a>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			
			@if( $categoriesCount > 0 )
				<div class="title-module">
					<div class="pull-left">
						<strong>Название</strong>
					</div>
					<div class="pull-right">
						<strong class="text-control">Управление</strong>
					</div>
					<div class="clear"></div>
				</div>
				
				{!! Form::open() !!}
					
					<input type="hidden" name="action" value="delete" />
				
					<div ng-app="treeApp">
						<div ng-controller="treeCtrl">
							<script type="text/ng-template" id="nodes_renderer.html">
								<div class="tree-node">
									<div class="pull-left tree-handle" ui-tree-handle>
										<i class="fa fa-bars"></i>
									</div>
									<div class="tree-node-content">
										<div class="tree-checkbox">
											<input name="check[]" type="checkbox" value="[[node.id]]">
										</div>
										<a class="black_link" href="/master/shop/categories/edit/[[node.id]]">[[node.name_ru]]</a>
										<div class="pull-right btn-group tree-btn-goup">
											<a title="Редактировать категорию" href="/master/shop/categories/edit/[[node.id]]" class="btn btn-warning">
												<i class="fa fa-pencil"></i>
											</a>
											<a href="/category/[[node.slug]]" class="btn btn-primary" title="Открыть в новом окне" target="_blank">
												<i class="fa fa-share"></i>
											</a>
											<button title="Удалить категорию" type="button" class="delete btn btn-danger" data-id="1">
												<i class="fa fa-times"></i>
											</button>
										</div>
									</div>
								</div>
								<ol ui-tree-nodes="" ng-model="node.children" ng-class="{hidden: collapsed}">
									<li ng-repeat="node in node.children" ui-tree-node ng-include="'nodes_renderer.html'"></li>
								</ol>
							</script>
							<div ui-tree="dataOptions" id="tree-root">
								<ol ui-tree-nodes ng-model="data">
									<li ng-repeat="node in data" ui-tree-node ng-include="'nodes_renderer.html'"></li>
								</ol>
							</div>
						</div>
					</div>
				
				{!! Form::close() !!}
			@else
				<div class="alert alert-warning">Категории еще не созданы</div>
			@endif
		
		</div>
	</div>

	<script src="/admin/angular/angular.min.js"></script>
	<script src="/admin/angular/angular-ui-tree.min.js"></script>
	<script>
		// Angular
		(function(){
			'use strict';

			var app = angular.module('treeApp', ['ui.tree'], function($interpolateProvider){
				$interpolateProvider.startSymbol('[[');
				$interpolateProvider.endSymbol(']]');
			});
			
			app.controller('treeCtrl', function($scope, $http){

				$scope.data = {!! $categories !!}

				$scope.dataOptions = {
					dropped: function(event){
						$http.post('/master/shop/categories', { _token: '{{ Session::token() }}', data: $scope.data, action: 'rebuild' }).
						success(function(data, status, headers, config) {
							console.log(data);
						}).
						error(function(data, status, headers, config) {
							alert('error');
						});
					}
				};
			});
		})();

		// jQuery
		$(function(){
			
			// Удаление записи
			$('.delete').click(function(){
				
				$('input[type="checkbox"][name*="check"]').prop('checked', false);
				$(this).closest('li .tree-node-content').find('input[type="checkbox"][name*="check"]').prop('checked', true);
				
				if( !confirm('Подтвердите удаление') )
					return false;
				else
					$(this).closest('form').submit();
			});
		});
	</script>
	
@endsection