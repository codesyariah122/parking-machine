<?php
namespace App\Controllers\Auth;

use App\Config\BaseController;
use App\Data\{WebAppDataSource};
use App\Models\{WebDataModel, Parkings};

class LoginController extends BaseController {

    public function __construct()
    {
        session_start();

        if(isset($_SESSION['token'])){
            header("Location: /dashboard/{$_SESSION['role']}");
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

            $layout = 'App/Views/AppLayout.php';
            $view = 'App/Views/index.php';
            $dataViews = [
                'title' => 'parking - login',
                'page' => 'login',
                'view' => $view
            ];

            self::views($layout, $dataViews);

        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            echo "Error : " . $errorMessage;
        }
    }

    
}