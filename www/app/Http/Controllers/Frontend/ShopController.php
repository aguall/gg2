<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Frontend\BaseController;

use App\Models\Seo;
use App\Models\Shop;
use App\Models\Product;
use App\Models\ProductFeature;
use App\Models\ProductCategory;
use App\Models\ProductShopPrice;
use App\Models\ProductFeatureGroup;

use DB;
use URL;
use App;
use Input;
use Redirect;
use LaravelLocalization;

class ShopController extends BaseController
{
    // Вывод категорий/продукции
    public function index(Request $request, $category_url)
    {

        $page = ProductCategory::whereSlug($category_url)->firstOrFail();

        // Показываем текст категории только на текущей странице категории
        if ($request->fullUrl() != LaravelLocalization::getLocalizedURL(App::getLocale(), '/category/' . $category_url))
            $page->{'body_' . App::getLocale()} = '';

        // Сео данные для фильтрации
        $robotsIndex = $this->robotsIndex;
        $canonicalUrl = $this->canonicalUrl;

        // Если выбрана сортировка - закрываем от индексации
        if ($request->get('sort'))
            $robotsIndex = 'NOINDEX';

        if ($seoData = Seo::getSeoData($request->getRequestUri())) {
            $robotsIndex = 'INDEX';

            $canonicalUrl = URL::to($request->getRequestUri());

            $locale = App::getLocale();

            $seoDataColumns = ['name_' . $locale, 'body_' . $locale, 'meta_title_' . $locale, 'meta_keywords_' . $locale, 'meta_description_' . $locale];

            foreach ($seoDataColumns as $item)
                if ($seoData->{$item})
                    $page->{$item} = $seoData->{$item};
        }

        $categories = $page->getImmediateDescendants();

        // ID текущей категории
        $categoryId = $page->id;

        $breadcrumb = ProductCategory::getParentCategories($categoryId);

        if (count($categories) > 0)
            return view('frontend.showShopCategories', compact(['page', 'categories', 'breadcrumb']));
        else {
            // Сортировка товаров
            $sort = Input::get('sort');

            // Все типы сортировок
            $sorting = [
                'name_asc' => trans('design.by_name_a_z'),
                'name_desc' => trans('design.by_name_z_a'),
                'price_asc' => trans('design.by_price_min_max'),
                'price_desc' => trans('design.by_price_max_min'),
                'offers_desc' => trans('design.on_proposals_max_min')
            ];

            // Сортировка по умолчанию
            $orderParam = 'product_offers';
            $orderDirection = 'DESC';

            switch ($sort) {
                // Сортировка по названию (А - Я)
                case 'name_asc':
                    $orderParam = 'product_name';
                    $orderDirection = 'ASC';
                    break;

                // Сортировка по названию (Я - А)
                case 'name_desc':
                    $orderParam = 'product_name';
                    $orderDirection = 'DESC';
                    break;

                // Сортировка по цене (от дешевых к дорогим)
                case 'price_asc':
                    $orderParam = DB::raw('ISNULL(MIN(NULLIF(CEIL(products_shops_prices.shop_product_price), 0))), MIN(NULLIF(CEIL(products_shops_prices.shop_product_price), 0))');
                    $orderDirection = 'ASC';
                    break;

                // Сортировка по цене (от дорогих к дешевым)
                case 'price_desc':
                    $orderParam = 'product_price';
                    $orderDirection = 'DESC';
                    break;

                // Сортировка по предложениям (от большего к меньшему)
                case 'offers_desc':
                    $orderParam = 'product_offers';
                    $orderDirection = 'DESC';
                    break;
            }

            // Товары текущей категории
            $products = Product::leftJoin('products_shops_prices', 'products_shops_prices.product_id', '=', 'products.id')
                ->select([
                    'products.id',
                    'products.slug AS product_url',
                    'products.name_' . App::getLocale() . ' AS product_name',
                    'products.advertising AS product_advertising',
                    'products.position AS product_position',
                    DB::raw('COUNT(products_shops_prices.id) AS product_offers'), // Количество предложений
                    DB::raw('MIN(NULLIF(CEIL(products_shops_prices.shop_product_price), 0)) AS product_price') // Минимальная цена продукта
                ])

                // Изображения товара
                ->with('product_images')

                // Видео обзоры товара
                ->with('product_video')

                // Опции товара
                ->with(['product_options' => function ($query) {

                        $query->leftJoin('products_features', 'products_features.id', '=', 'products_options.feature_id')
                            ->leftJoin('products_options_variants', 'products_options_variants.id', '=', 'products_options.option_variant_id')
                            ->select([
                                'products_options.*',
                                'products_features.name_' . App::getLocale() . ' AS option_name',
                                'products_options_variants.name_' . App::getLocale() . ' AS option_value'
                            ])
                            ->orderBy('option_name');
                    }])

                ->where('products.category_id', $categoryId)
                ->where('products.visible', 1)
                ->where(function ($query) {
                    // Фильтрация при выборе товаров
                    if ($filter = Input::get('filter')) {
                        foreach ($filter as $feature_id => $option_variant_ids) {
                            $query->whereIn('products.id', function ($q) use ($feature_id, $option_variant_ids) {
                                $q->select('products_options.product_id')
                                    ->from('products_options')
                                    ->where('products_options.feature_id', $feature_id)
                                    ->whereIn('products_options.option_variant_id', $option_variant_ids);
                            });
                        }
                    }
                })
                ->groupBy('products.id')
                ->orderBy('product_advertising', 'DESC')
                ->orderBy($orderParam, $orderDirection)
                ->orderBy('product_name', 'ASC')
                ->paginate(25);

            // Связанная фильтрация
            $features = ProductFeature::groupBy('id')
                ->orderByRaw('CASE WHEN name_' . App::getLocale() . ' LIKE "Т%" THEN 1 ELSE 2 END')
                ->with(['options' => function ($query) use ($categoryId) {

                        $query->join('products', 'products.id', '=', 'products_options.product_id')
                            ->join('products_options_variants', 'products_options_variants.id', '=', 'products_options.option_variant_id')
                            ->where('products.category_id', $categoryId);

                        if ($filter = Input::get('filter')) {
                            foreach ($filter as $feature_id => $option_variant_ids) {
                                $query->where(function ($query) use ($feature_id, $option_variant_ids) {
                                    $query->where('products_options.feature_id', $feature_id)
                                        ->orWhereIn('products_options.product_id', function ($q) use ($feature_id, $option_variant_ids) {
                                            $q->select('products_options.product_id')
                                                ->from('products_options')
                                                ->where('products_options.feature_id', $feature_id)
                                                ->whereIn('products_options.option_variant_id', $option_variant_ids);
                                        });
                                });
                            }
                        }

                        $query->select([
                            'products_options.*',
                            'products_options_variants.name_' . App::getLocale() . ' AS product_option_value',
                            DB::raw('COUNT(products_options.product_id) AS product_count')
                        ])
                            ->groupBy('product_option_value', 'products_options.feature_id')
                            ->orderByRaw('`product_option_value` = 0, -`product_option_value` DESC')
                            ->orderBy('product_option_value');
                    }])
                ->get();

            // Индикатор наличия фильтра для товаров
            $hasOptions = FALSE;
            foreach ($features as $feature)
                if (count($feature->options)) {
                    $hasOptions = TRUE;
                    break;
                }

            return view('frontend.showShopProducts', compact(['page', 'products', 'features', 'sorting', 'hasOptions', 'breadcrumb', 'robotsIndex', 'canonicalUrl']));
        }
    }

    // Карточка товара
    public function getProduct(Request $request, $product_url)
    {
        if ($request->ajax()) {
            // Увеличиваем рейтинг магазина по клику на ссылку "В магазин"
            Shop::find((int)$request->get('shop_id'))->increment('rating');
        } else {
            // Если есть заглавные буквы, то редиректим на тот же URL только с буквами в нижнем регистре
            if (preg_match('/[A-Z]/', $product_url))
                return Redirect::to(LaravelLocalization::getLocalizedURL(App::getLocale(), '/product/' . strtolower($product_url)), 301);
            else {
                $page = Product::with(['product_options' => function ($query) {

                        // Опции товаров
                        $query->leftJoin('products_features', 'products_features.id', '=', 'products_options.feature_id')
                            ->leftJoin('products_options_variants', 'products_options_variants.id', '=', 'products_options.option_variant_id')
                            ->select([
                                'products_options.*',
                                'products_features.feature_group_id AS option_group_id',
                                'products_features.name_' . App::getLocale() . ' AS option_name',
                                'products_options_variants.name_' . App::getLocale() . ' AS option_value'
                            ])
                            ->orderBy('option_name');
                    }])
                    ->with(['product_prices' => function ($query) {

                            // Цены на товар в магазинах
                            $query->leftJoin('shops', 'shops.id', '=', 'products_shops_prices.shop_id')
                                ->select([
                                    'products_shops_prices.*',
                                    'shops.logo AS shop_logo',
                                    'shops.name_' . App::getLocale() . ' AS shop_name',
                                    'shops.slug AS shop_slug'
                                ])
                                ->orderByRaw('products_shops_prices.shop_product_price = 0, products_shops_prices.shop_product_price ASC');
                        }])
                    ->with('product_images') // Изображения товара
                    ->with('product_video') // Видео обзоры товара
                    ->whereSlug($product_url)
                    ->firstOrFail();

                // Опции товаров разбитые по группам
                $optionByGroups = [];
                if (count($page->product_options)) {
                    $groups = ProductFeatureGroup::all();
                    foreach ($page->product_options as $option)
                        foreach ($groups as $group)
                            if ($option->option_group_id == $group->id)
                                $optionByGroups[$group->name][] = $option;
                }

                $breadcrumb = ProductCategory::getParentCategories($page->category_id);

                // URL текущей категории в которой находится товар
                $currentProductCategory = end($breadcrumb);
                $currentProductCategoryURL = LaravelLocalization::getLocalizedURL(App::getLocale(), '/category/' . $currentProductCategory->slug);

                return view('frontend.showShopProduct', compact(['page', 'breadcrumb', 'optionByGroups', 'currentProductCategoryURL']));
            }
        }
    }
}
