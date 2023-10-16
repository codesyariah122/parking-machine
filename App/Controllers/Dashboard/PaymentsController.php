<?php
namespace App\Controllers\Dashboard;

use App\Config\BaseController;
use App\Data\{WebAppDataSource};
use App\Helpers\MyHelpers;
use App\Models\{WebDataModel, Parkings, Payments};

class PaymentsController extends BaseController {

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
                'title' => 'Parking Payments',
                'page' => 'dashboard/payments',
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
        $helpers = new MyHelpers;
        $limit = 10;
        $keyword = isset($_GET['keyword']) ? @$_GET['keyword'] : '';
        $page = isset($_GET['page']) ? intval(@$_GET['page']) : 1;
        $offset = ($page - 1) * $limit;

        if (!empty($keyword)) {
            $countPage = Payments::countSearchData($keyword);
            $totalPage = ceil($countPage / $limit);
            $payments = Payments::searchData($keyword, $offset, $limit);
            $analysis = [];
            $totalPendapatan = '';
            $mostVehicles=[];
        } else {
            $today = date('Y-m-d');

            $oneDaysAgo = date('Y-m-d', strtotime('-1 days'));

            $twoDaysAgo = date('Y-m-d', strtotime('-2 days'));

            $countPage = Payments::countAllData();

            $totalPage = ceil($countPage / $limit);

            $payments = Payments::all("
                SELECT
                p.id AS payment_id,
                p.barcode,
                p.duration,
                p.paymentAmount,
                p.paymentDate,
                v.id AS vehicle_id,
                v.type,
                v.harga
                FROM
                payments p
                INNER JOIN vehicles v ON p.vehicle_id = v.id
                ORDER BY p.id DESC
                LIMIT $offset, $limit
                ");

            $paymentData = [];

            foreach ($payments as $payment) {
                $paymentDate = date('Y-m-d H:i:s', strtotime($payment['paymentDate']));

                if (date('Y-m-d') === date('Y-m-d', strtotime($paymentDate))) {
                    $paymentTime = date('H:i:s', strtotime($paymentDate));

                    if (array_key_exists($paymentTime, $paymentData)) {
                        $paymentData[$paymentTime] += $payment['paymentAmount'];
                    } else {
                        $paymentData[$paymentTime] = $payment['paymentAmount'];
                    }
                }
            }

            ksort($paymentData); // Mengurutkan data berdasarkan waktu

            $times = array_keys($paymentData);
            $amounts = array_values($paymentData);


            $totalMonthlyIncome = Payments::all("
                SELECT
                YEAR(p.paymentDate) AS year,
                MONTH(p.paymentDate) AS month,
                SUM(p.paymentAmount) AS total_pendapatan
                FROM
                payments p
                INNER JOIN
                vehicles v ON p.vehicle_id = v.id
                GROUP BY
                YEAR(p.paymentDate), MONTH(p.paymentDate)
                ");

            $totalPages = ceil(count($totalMonthlyIncome) / $limit);

            $monthlyIncomePerPage = array_sum(array_column($totalMonthlyIncome, 'total_pendapatan')) / $totalPages;


            $totalPaymentPerDay = Payments::all("
                SELECT
                DATE(p.paymentDate) AS paymentDay,
                SUM(p.paymentAmount) AS total_pendapatan
                FROM
                payments p
                INNER JOIN vehicles v ON p.vehicle_id = v.id
                GROUP BY DATE(p.paymentDate)
                ");

            $resultToday = 0;

            foreach ($totalPaymentPerDay as $paymentPerDay) {
                $resultToday = $paymentPerDay['total_pendapatan'];
            }
            

            $vehicleTotals = Payments::all("
                SELECT
                p.id AS payment_id,
                v.type
                FROM
                payments p
                INNER JOIN vehicles v ON p.vehicle_id = v.id
                ORDER BY p.id DESC
                LIMIT $offset, $limit
                ");


            $vehicleTotalTodays = Payments::all("
                SELECT
                p.id AS payment_id,
                v.type
                FROM
                payments p
                INNER JOIN vehicles v ON p.vehicle_id = v.id
                WHERE DATE(p.paymentDate) = '$today'
                ORDER BY p.id DESC
                LIMIT $offset, $limit
                ");

            $vehicleTypesCount = array();

            $vehicleTypesCountToday = array();

            foreach ($vehicleTotals as $vehicle) {
                $type = $vehicle['type'];
                if (isset($vehicleTypesCount[$type])) {
                    $vehicleTypesCount[$type]++;
                } else {
                    $vehicleTypesCount[$type] = 1;
                }
            }

            $maxType = '';
            $maxCount = 0;
            foreach ($vehicleTypesCount as $type => $count) {
                if ($count > $maxCount) {
                    $maxCount = $count;
                    $maxType = $type;
                }
            }

            foreach ($vehicleTotalTodays as $vehicle) {
                $type = $vehicle['type'];
                if (isset($vehicleTypesCountToday[$type])) {
                    $vehicleTypesCountToday[$type]++;
                } else {
                    $vehicleTypesCountToday[$type] = 1;
                }
            }


            $maxTypeToday = '';
            $maxCountToday = 0;
            foreach ($vehicleTypesCountToday as $type => $count) {
                if ($count > $maxCountToday) {
                    $maxCountToday = $count;
                    $maxTypeToday = $type;
                }
            }

            if (!empty($totalMonthlyIncome)) {
                $totalPendapatan = $monthlyIncomePerPage;
            } else {
                $totalPendapatan = 0;
            }

            $analysis =  [
                'totalToday' => $resultToday,
                'totalTypeToday' => $maxTypeToday,
                'finalTotalType' => $maxType,
                'finalTotal' => $helpers->formatRupiah($totalPendapatan, 3),
                'date' => $times,
                'amount' => $amounts
            ];

            $paymentsPerDay = Payments::all("
                SELECT
                p.id AS payment_id,
                DATE(p.paymentDate) AS paymentDate,
                v.id AS vehicle_id,
                v.type,
                v.harga
                FROM
                payments p
                INNER JOIN vehicles v ON p.vehicle_id = v.id
                ORDER BY p.id DESC
                ");

            $paymentsPerMonth = Payments::all("
                SELECT
                p.id AS payment_id,
                DATE_FORMAT(p.paymentDate, '%Y-%m') AS paymentMonth,
                v.id AS vehicle_id,
                v.type,
                v.harga
                FROM
                payments p
                INNER JOIN vehicles v ON p.vehicle_id = v.id
                ORDER BY p.id DESC
                ");

            if(isset($_GET['page'])) {
                $percentageRodaDuaPerDay = $helpers->calculatePercentage($paymentsPerDay, 'RODA DUA');
                $percentageRodaEmpatPerDay = 100 - $percentageRodaDuaPerDay;
                $percentageRodaDuaPerMonth = $helpers->calculatePercentage($paymentsPerMonth, 'RODA DUA');
                $percentageRodaEmpatPerMonth = 100 - $percentageRodaDuaPerMonth;
            } else {
                $percentageRodaDuaPerDay = $helpers->calculatePercentage($vehicleTotalTodays, 'RODA DUA');
                $percentageRodaEmpatPerDay = 100 - $percentageRodaDuaPerDay;

                $percentageRodaDuaPerMonth = $helpers->calculatePercentage($vehicleTotals, 'RODA DUA');
                $percentageRodaEmpatPerMonth = 100 - $percentageRodaDuaPerMonth;                
            }
            

            $mostVehicles = [
                'daily' => [
                    'rodaDua' => ceil($percentageRodaDuaPerDay),
                    'rodaEmpat' => ceil($percentageRodaEmpatPerDay)
                ],
                'monthly' => [
                    'rodaDua' => ceil($percentageRodaDuaPerMonth),
                    'rodaEmpat' => ceil($percentageRodaEmpatPerMonth)
                ]
            ];

        }


        if (!empty($payments)) {

            $data = [
                'success' => true,
                'message' => "Lists parking payments!",
                'session_user' => (isset($_SESSION['name'])) ? $_SESSION['name'] : '',
                'data' => $payments,
                'analysis' => $analysis,
                'mostlyVehicles' => $mostVehicles,
                'totalData' => count($payments),
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