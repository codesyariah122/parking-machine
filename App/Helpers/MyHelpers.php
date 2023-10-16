<?php
namespace App\Helpers;

class MyHelpers {

	public function generateRandomString($length) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';

		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}

		return $randomString;
	}

	public function formatRupiah($num, $digit)
	{
		$num = ($num !== null) ? $num : 0;

		$rupiah = number_format($num, $digit, ',', '.');

		return $rupiah; // Output: Rp 1.500.000

	}

	public function createBarcode($text) {
	    $barcodeWidth = 300; // Lebar barcode dalam piksel
	    $barcodeHeight = 100; // Tinggi barcode dalam piksel
	    
	    // Buat gambar baru dengan lebar dan tinggi yang ditentukan
	    $image = imagecreatetruecolor($barcodeWidth, $barcodeHeight);
	    
	    // Warna latar belakang putih
	    $backgroundColor = imagecolorallocate($image, 255, 255, 255);
	    
	    // Warna teks hitam
	    $textColor = imagecolorallocate($image, 0, 0, 0);
	    
	    // Isi latar belakang dengan warna putih
	    imagefill($image, 0, 0, $backgroundColor);
	    
	    // Buat font untuk teks barcode
	    $fontPath = '/public/assets/fonts/Oliciy.ttf'; // Ganti dengan path font yang Anda gunakan
	    $fontSize = 24;
	    $font = imageloadfont($fontPath);
	    
	    // Hitung posisi horizontal tengah untuk teks
	    $textWidth = imagefontwidth($font) * strlen($text);
	    $x = ($barcodeWidth - $textWidth) / 2;
	    
	    // Gambar teks pada gambar barcode
	    imagestring($image, $font, $x, 0, $text, $textColor);
	    
	    // Simpan gambar barcode ke file
	    $imageFile = "/public/assets/images/barcode/{$barcode}.png"; // Ganti dengan path file yang Anda inginkan
	    
	    imagepng($image, $imageFile);
	    
	    // Hapus gambar dari memori
	    imagedestroy($image);
	    
	    return $imageFile;
	}

	public function calculatePercentage($data, $type) {
		$countType = 0;
		$total = count($data);

		if ($total === 0) {
			return 0;
		}

		foreach ($data as $payment) {
			if ($payment['type'] === $type) {
				$countType++;
			}
		}

		return ($countType / $total) * 100;
	}
}