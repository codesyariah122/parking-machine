<?php
namespace App\Controllers\WebBase;

use App\Config\BaseController;
use App\Data\{WebAppDataSource};
use App\Models\{Parkings};

class WebPageController extends BaseController {

	private static function views($layout, $param)
    {
         $vehicles = Parkings::getVehicle();

         $contents = WebAppDataSource::render($param);

         $page = $param['page'];

         $slot = 16;

         $cta_image = '/public/assets/animation/parking-anim.gif';

         $banner_img = '/public/assets/images/parking-lot.jpg';

         extract($contents);

         ob_start();
         require_once $param['view'];
         $content = ob_get_clean();

         ob_start();
         require_once $layout;
         $output = ob_get_clean();

         echo $output;
    }
	
    public function index() {
        $layout = 'App/Views/AppLayout.php';
        $view = 'App/Views/index.php';
        
        $dataViews = [
            'title' => 'parking - simulation',
            'page' => 'home',
            'view' => $view
        ];

        self::views($layout, $dataViews);
    }

    public function parkir()
    {
        try {

            $layout = 'App/Views/AppLayout.php';
            $view = 'App/Views/index.php';
            $dataViews = [
                'title' => 'parking - slot',
                'page' => 'parkir',
                'view' => $view
            ];

            self::views($layout, $dataViews);

        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            echo "Error : " . $errorMessage;
        }
    }
}