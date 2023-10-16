<?php

namespace App\Controllers\Api;

use App\Helpers\MyHelpers;
use App\Models\{Parkings, Tickets};

class ParkingProcessController {

	private $vehicleModel, $ticketModel, $helpers;

	public function __construct()
	{
		header("Content-Type: application/json");
		$this->helpers = new MyHelpers;
	}

	public function checkAvailable()
	{
		try {
			$from = @$_GET['from'] ?? 1;
			$to = @$_GET['to'] ?? 8;

			$booths = Parkings::checkAvailableBooth($from, $to);

			$data = [
				'success' => true,
				'message' => "Lists of slot from : {$from}, to : {$to}",
				'data' => $booths
			];

			// var_dump($data); die;

			echo json_encode($data);

		} catch (\Exception $e) {
			$data = [
				'error' => true,
				'message' => 'Error process for checking available ...'
			];

			echo json_encode($data);
		}
	}

	public function store()
	{
		try {
			$data = @$_POST;

			if(count($data) > 0) {
				$dateTime = time();

				$vehicleType = Parkings::checkAvailableVehicle($data['vehicle_id']);

				if(count($vehicleType) > 0) {
					$prepareTicket = [
						'barcode' => $this->helpers->generateRandomString(7),
						'vehicle_id' => $vehicleType[0]['id'],
						'startedAt' => date('Y-m-d H:i:s', $dateTime)
					];

					$storeTicket = Tickets::store($prepareTicket, 'tickets');

					$ticketData = Tickets::getData($storeTicket);

					if($storeTicket > 0) {					
						$data = [
							'success' => true,
							'message' => 'Ticket berhasil diambil, silahkan menuju tempat parkir Anda!',
							'data' => $ticketData
						];

						echo json_encode($data);
					} else {
						$data = [
							'error' => true,
							'message' => 'Anda belum mengisi slot'
						];

						echo json_encode($data);
					}
				} else {
					$data = [
						'error' => true,
						'message' => 'Data kendaraan tidak ditemukan'
					];

					echo json_encode($data);
				}

			} else {
				$data = [
					'error' => true,
					'message' => 'Input its empty, please check again ...'
				];

				echo json_encode($data);
			}

		} catch (\Exception $e) {
			$data = [
				'error' => true,
				'message' => 'Error process for checking available ...'
			];

			echo json_encode($data);
		}
	}
}