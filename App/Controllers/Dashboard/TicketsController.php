<?php
namespace App\Controllers\Dashboard;

use App\Config\BaseController;
use App\Data\{WebAppDataSource};
use App\Models\{WebDataModel, Parkings, Tickets};

class TicketsController extends BaseController{

	public function __construct()
    {
        session_start();
    }

    private static function views($layout, $param)
    {
        $vehicles = Parkings::getVehicle();
        
        $contents = WebAppDataSource::render($param);

        $page = $param['page'];

        $title = $param['title'];

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
            if(!isset($_SESSION['token'])) {
                header("Location: /?error=forbaiden");
            }
            $layout = 'App/Views/DashboardLayout.php';
            $view = 'App/Views/index.php';
            $dataViews = [
                'title' => 'Parking Tickets',
                'page' => 'dashboard/tickets',
                'view' => $view
            ];

            self::views($layout, $dataViews);

        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            echo "Error : " . $errorMessage;
        }
    }

    public function all()
    {
        header("Content-Type: application/json");

        $limit = 10;
        $keyword = isset($_GET['keyword']) ? @$_GET['keyword'] : '';
        $page = isset($_GET['page']) ? intval(@$_GET['page']) : 1;
        $offset = ($page - 1) * $limit;

        if (!empty($keyword)) {
            $countPage = Tickets::countSearchData($keyword);
            $totalPage = ceil($countPage / $limit);
            $tickets = Tickets::searchData($keyword, $offset, $limit);
        } else {
            $countPage = Tickets::countAllData();
            $totalPage = ceil($countPage / $limit);
            $tickets = Tickets::all("SELECT 
                v.id AS vehicle_id,
                v.type,
                v.harga,
                t.id AS ticket_id,
                t.barcode,
                t.startedAt,
                s.id AS slot_id,
                s.name
                FROM vehicles v
                INNER JOIN tickets t ON v.id = t.vehicle_id
                LEFT JOIN slots s ON t.slot_id = s.id
                ORDER BY t.id DESC
                LIMIT $offset, $limit");
        }
        $mostlyVehicles = Tickets::all("SELECT 
            v.id AS vehicle_id,
            v.type,
            v.harga,
            t.id AS ticket_id,
            t.barcode,
            t.startedAt,
            s.id AS slot_id,
            s.name
            FROM vehicles v
            INNER JOIN tickets t ON v.id = t.vehicle_id
            LEFT JOIN slots s ON t.slot_id = s.id
            ORDER BY t.id DESC
            LIMIT $offset, $limit");

        $vehicleTypeCount = array();

        // Loop melalui hasil query dan hitung jumlah kemunculan setiap tipe kendaraan
        foreach ($mostlyVehicles as $vehicle) {
            $vehicleType = $vehicle['type'];

            if (isset($vehicleTypeCount[$vehicleType])) {
                $vehicleTypeCount[$vehicleType]++;
            } else {
                $vehicleTypeCount[$vehicleType] = 1;
            }
        }

        $mostCommonVehicleType = '';
        $maxCount = 0;

        foreach ($vehicleTypeCount as $type => $count) {
            if ($count > $maxCount) {
                $mostCommonVehicleType = $type;
                $maxCount = $count;
            }
        }

        if (!empty($tickets)) { 
            $data = [
                'success' => true,
                'message' => "Lists active parking tickets!",
                'session_user' => (isset($_SESSION['name'])) ? $_SESSION['name'] : '',
                'data' => $tickets,
                'mostVehicles' => $mostCommonVehicleType,
                'totalData' => count($tickets),
                'countPage' => $countPage,
                'totalPage' => $totalPage,
                'aktifPage' => $page
            ];

            echo json_encode($data);
        } else {
            $data = [
                'empty' => true,
                'message' => "Data not found !!",
            ];

            echo json_encode($data);
        }
    }
}