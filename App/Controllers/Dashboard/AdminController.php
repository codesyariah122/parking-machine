<?php
namespace App\Controllers\Dashboard;

use App\Config\BaseController;
use App\Data\{WebAppDataSource};
use App\Models\{WebDataModel, Parkings};

class AdminController extends BaseController {

    public function __construct()
    {
        session_start();

        if(!isset($_SESSION['token'])) {
            header("Location: /?error=forbaiden");
        }
    }

    private static function views($layout, $param)
    {
        $vehicles = Parkings::getVehicle();
        
        $contents = WebAppDataSource::render($param);

        $page = $param['page'];

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
        try {
            $layout = 'App/Views/DashboardLayout.php';
            $view = 'App/Views/index.php';
            $dataViews = [
                'title' => 'Dashboard::Admin',
                'page' => 'dashboard/admin',
                'view' => $view
            ];

            self::views($layout, $dataViews);

        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            echo "Error : " . $errorMessage;
        }
    }

}