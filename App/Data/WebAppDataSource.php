<?php

namespace App\Data;

use App\Config\Environment;

class WebAppDataSource {

	public function __construct()
	{
		Environment::run();
	}

	public static function render($params)
	{
		switch($params['page']) {
			case "home":
			$title = APP_NAME;
			$navbar = 'App/Views/Partials/navbar.php';
			$sidebar = '';
			$contents = [
				'loading' => 'App/Views/Partials/loading.php',
				'loadingOverlay' => '',
				'slider' => 'App/Views/Home/banner.php',
				'cta_banner' => 'App/Views/Home/cta-banner.php',
				'content' => 'App/Views/Home/page-content.php'
			];
			$footer_content = 'App/Views/Partials/footer-content.php';
			$links = [
				'/public/assets/css/styles.css',
				'/public/assets/css/card.css'
			];
			$scripts = [
				'/public/assets/js/script.js',
				'/public/assets/js/hooks.js',
				'/public/assets/js/slider.js'
			];
			break;

			case "parkir":
			$title = APP_NAME;
			$navbar = 'App/Views/Partials/navbar.php';
			$sidebar = '';
			$footer_content = 'App/Views/Partials/footer-content.php';
			$contents = [
				'loading' => '',
				'loadingOverlay' => 'App/Views/Partials/loading-overlay.php',
				'content' => 'App/Views/Parkir/page-content.php'
			];
			$links = [
				'/public/assets/css/styles.css',
				'/public/assets/css/card.css'
			];
			$scripts = [
				'/public/assets/js/script.js',
				'/public/assets/js/hooks.js',
				'/public/assets/js/slider.js',
				'/public/assets/js/parkir/app.js',
				'/public/assets/js/parkir/functions.js',
			];
			break;

			case "login":
			$title = APP_NAME;
			$navbar = '';
			$sidebar = '';
			$footer_content = 'App/Views/Partials/footer-content.php';
			$contents = [
				'loading' => '',
				'loadingOverlay' => 'App/Views/Partials/loading-overlay.php',
				'content' => 'App/Views/Auth/LoginForm.php'
			];
			$links = [
				'/public/assets/css/styles.css',
				'/public/assets/css/card.css'
			];
			$scripts = [
				'/public/assets/js/script.js',
				'/public/assets/js/hooks.js',
				'/public/assets/js/slider.js',
				'/public/assets/js/dashboard/functions.js',
				'/public/assets/js/dashboard/app.js'
			];
			break;

			case "dashboard/admin":
			$title = "Dashboard::Admin";
			$navbar = 'App/Views/Partials/navbar.php';
			$sidebar = 'App/Views/Partials/sidebar.php';
			$footer_content = 'App/Views/Partials/footer-content.php';
			$contents = [
				'loading' => '',
				'loadingOverlay' => 'App/Views/Partials/loading-overlay.php',
				'content' => 'App/Views/Dashboard/Admin/page-contents.php'
			];
			$links = [
				'/public/assets/css/styles.css',
				'/public/assets/css/card.css'
			];
			$scripts = [
				'/public/assets/js/script.js',
				'/public/assets/js/hooks.js',
				'/public/assets/js/slider.js',
				'/public/assets/js/dashboard/functions.js',
				'/public/assets/js/dashboard/charts.js',
				'/public/assets/js/dashboard/app.js'
			];
			break;

			case "dashboard/tickets":
			$title = "Dashboard::Tickets";
			$navbar = 'App/Views/Partials/navbar.php';
			$sidebar = 'App/Views/Partials/sidebar.php';
			$footer_content = 'App/Views/Partials/footer-content.php';
			$contents = [
				'loading' => '',
				'loadingOverlay' => 'App/Views/Partials/loading-overlay.php',
				'content' => 'App/Views/Dashboard/ticket.php'
			];
			$links = [
				'/public/assets/css/styles.css',
				'/public/assets/css/card.css'
			];
			$scripts = [
				'/public/assets/js/script.js',
				'/public/assets/js/hooks.js',
				'/public/assets/js/slider.js',
				'/public/assets/js/dashboard/functions.js',
				'/public/assets/js/dashboard/app.js'
			];
			break;

			case "dashboard/payments":
			$title = "Dashboard::Payments";
			$navbar = 'App/Views/Partials/navbar.php';
			$sidebar = 'App/Views/Partials/sidebar.php';
			$footer_content = 'App/Views/Partials/footer-content.php';
			$contents = [
				'loading' => '',
				'loadingOverlay' => 'App/Views/Partials/loading-overlay.php',
				'content' => 'App/Views/Dashboard/payment.php'
			];
			$links = [
				'/public/assets/css/styles.css',
				'/public/assets/css/card.css'
			];
			$scripts = [
				'/public/assets/js/script.js',
				'/public/assets/js/hooks.js',
				'/public/assets/js/slider.js',
				'/public/assets/js/dashboard/functions.js',
				'/public/assets/js/dashboard/app.js'
			];
			break;

			default:
			$contents = [];
			$scripts = [];
			break;
		}

		return [
			'navbar' => $navbar,
			'sidebar' => $sidebar,
			'contents' => $contents,
			'footer_content' => $footer_content,
			'scripts' => $scripts,
			'links' => $links,
			'head_title' => $params['title'],
			'title' => $title,
			'logo' => '/public/assets/img/icon.png',
			'favicon' => '/public/assets/img/favicon.ico',
			'tagline' => 'Warungku App | Digitalisasikan operasional warungmi',
			'brand' => 'Warungku App',
			'bg' => '',
			'vendor' => [
				'tailwind' => '/public/assets/vendor/js/tailwind.js',
				'fontawesome' => '/public/assets/vendor/css/all.min.css',
				'flowbite' => [
					'css' => '/public/assets/vendor/css/flowbite.min.css',
					'js' => '/public/assets/vendor/js/flowbite.min.js',
				],
				'contentful' => '/public/assets/vendor/js/contentful.browser.min.js',
				'sweetalert' => '/public/assets/vendor/js/sweetalert2@11.js'
			],
		];
	}
}