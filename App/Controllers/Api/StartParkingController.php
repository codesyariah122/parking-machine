<?php

namespace App\Controllers\Api;

use App\Helpers\MyHelpers;
use App\Models\{Parkings, Tickets, Slots};

class StartParkingController {

	private $vehicleModel, $ticketModel, $slotModel, $helpers;

	public function __construct()
	{
		header("Content-Type: application/json");
		$this->helpers = new MyHelpers;
	}

	public function store()
	{
		$data = @$_POST;

		if($data['slot_id'] !== "" || $data['vehicle_id'] !== "" || $data['barcode'] !== "") {

			$checkTicket = Tickets::checkTicket($data['barcode']);

			if(!$checkTicket) {
				$data = [
					'error' => true,
					'message' => 'Data ticket tidak ditemukan ...'
				];

				echo json_encode($data);
			} else {
				if($checkTicket['barcode'] === $data['barcode']) {
					$prepareUpdateTicket = [
						'slot_id' => intval($data['slot_id']),
						'id' => $checkTicket['id']
					];

					$prepareSlot = [
						'slot_id' => $data['slot_id'],
						'status' => $data['status']
					];

					$storeStatusSlot = Slots::store($prepareSlot, 'slots');


					if($storeStatusSlot > 0 && Tickets::update($prepareUpdateTicket, 'tickets') > 0) {
						$parkingSlot = Slots::getData($prepareSlot['slot_id']);
						
						$data = [
							'success' => true,
							'message' => "Successfully added slot parking!",
							'data' => $parkingSlot
						];

						echo json_encode($data);
					}
				} else {
					$data = [
						'error' => true,
						'message' => 'Data ticket tidak sesuai ...'
					];

					echo json_encode($data);
				}
			}

		} else {
			$data = [
				'error' => true,
				'message' => 'Input its empty, please check again ...'
			];

			echo json_encode($data);
		}
	}
}