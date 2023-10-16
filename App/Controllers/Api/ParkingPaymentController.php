<?php

namespace App\Controllers\Api;

use App\Helpers\MyHelpers;
use App\Models\{Parkings, Tickets, Slots, Payments};

class ParkingPaymentController {

	private $vehicleModel, $ticketModel, $slotModel, $paymentModel, $helpers;

	public function __construct()
	{
		header("Content-Type: application/json");
		$this->helpers = new MyHelpers;
	}


	public function store()
	{
		try {
			$data = @$_POST;
			if($data['vehicle_id'] !== "" || $data['slot_id'] !== "" || $data['barcode'] !== "") {

				$checkTiket = Tickets::checkTicket($data['barcode']);

				
				if(!$checkTiket) {
					$data = [
						'error' => true,
						'message' => 'Data parkir tidak ditemukan'
					];

					echo json_encode($data);
				} else {
					if($checkTiket['vehicle_id'] === intval($data['vehicle_id']) && $checkTiket['slot_id'] === intval($data['slot_id']) && $checkTiket['barcode'] === $data['barcode']) {


						$startedAt = $checkTiket['startedAt'];

						$startedDateTime = \DateTime::createFromFormat('Y-m-d H:i:s', $startedAt);
						
						$currentDateTime = new \DateTime();

						$diff = $currentDateTime->diff($startedDateTime);

						$interval = $currentDateTime->diff($startedDateTime);

						$diff = $currentDateTime->diff($startedDateTime);


						$days = $diff->days; // Selisih hari antara $startedAt dan waktu saat ini
						$hours = $diff->h; // Selisih jam antara $startedAt dan waktu saat ini

						// Mengakumulasi jumlah jam jika selisih harinya lebih dari 1 hari
						$totalHours = ($days > 0) ? ($days * 24) + $hours : $hours;

						$duration = $totalHours;

						$checkVehicle = Parkings::checkAvailableVehicle($data['vehicle_id'])[0];
						$harga = $checkVehicle['harga'];

						$paymentAmount = $this->helpers->formatRupiah($harga * $duration, 0);

						$preparePayment = [
							'vehicle_id' => $checkTiket['vehicle_id'],
							'slot_id' => $checkTiket['slot_id'],
							'barcode' => $checkTiket['barcode'],
							'duration' => $duration > 0 ? "$duration Jam" : "$diff->i Menit",
							'paymentAmount' => $duration > 0 ? $paymentAmount : $harga,
							'paymentDate' => $currentDateTime->format('Y-m-d H:i:s')
						];

						// var_dump($preparePayment); die;

						$payment = Payments::store($preparePayment, 'payments');

						// Update ticket endedAt
						Tickets::endedAt($data['barcode']);

						// Update slot
						$prepareUpdateSlot = [
							'slot_id' => $data['slot_id'],
							'status' => 'AVAILABLE'
						];

						$slot_id = Slots::update($prepareUpdateSlot, 'slots');

						$dataSlotUpdate = Slots::slotById($slot_id);

						$paymentData = Payments::getData($payment);

						if($paymentData) {
							Tickets::deleted($checkTiket['id']);
							$data = [
								'success' => true,
								'message' => "Pembayaran dengan code {$checkTiket['barcode']} berhasil !",
								'data' => $paymentData,
								'dataSlot' => $dataSlotUpdate
							];

							echo json_encode($data);
						}


					} else {
						$data = [
							'error' => true,
							'message' => 'Data parkir tidak ditemukan'
						];

						echo json_encode($data);
					}
				}

			}

		} catch (\Exception $e) {
			$data = [
				'error' => true,
				'message' => $e->getMessage()
			];

			echo json_encode($data);
		}
	}
}