<?php

namespace App\Models;

use App\Data\{Sliders};

class WebDataModel {

	public function sliders()
	{
		try {
			$sliders = new Sliders;
			if(count($sliders->run()) > 0){
				return $sliders->run();
			} 
		} catch (\Exception $e) {
			// throw new \Error("Terjadi kesalahan dalam mendapatkan data slider.");
			$data = [
				'error' => true,
				'message' => "Error fetch sliders data : ".$e->getMessage(),
			];
			echo json_encode($data);
		}
	}

}