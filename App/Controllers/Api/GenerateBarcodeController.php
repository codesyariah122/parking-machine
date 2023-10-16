<?php

namespace App\Controllers\Api;

use App\Helpers\MyHelpers;

class GenerateBarcodeController {

	public function run($barcode)
	{
		try {
			// var_dump($barcode); die;
			$helpers = new MyHelpers;
			$generateBarcode = $helpers->createBarcode($barcode);
			
			$data = [
				'success' => true,
				'message' => "Barcode generate !",
				'data' => $generateBarcode
			];

			echo json_encode($data);
		} catch (\Exception $e) {
			$data = [
				'error' => true,
				'message' => 'Generate error barcode'
			];

			echo json_encode($data);
		}
	}
}