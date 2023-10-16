<?php

namespace App\Controllers\Auth;

use App\Data\UserData;
use App\Models\{Users};

class AuthManagementController {

	private $data;

	public function __construct()
	{
		header('Content-Type: application/json');
		session_set_cookie_params(3600);
		session_start();
		$this->data = new UserData;
	}

	public function add()
	{
		$user_data = $this->data->data();
		$model = new Users;
		$result = $model->store($user_data);
		if ($result === true) {
			header("Location: /?user=success-create");
		} else {
			echo "Terjadi kesalahan: " . $result;
		}
	}

	public function checkSessionLogin()
	{
		$data = @$_GET['token'];

		if(!isset($_SESSION['token'])) {
			$data = [
				'error' => true,
				'message' => 'Expired session!!'
			];

			echo json_encode($data);
			exit();
		}


	}

	public function login()
	{

		if(@$_POST['email'] === NULL || @$_POST['email'] === "" && @$_POST['password'] === NULL || @$_POST['password'] === "") {
			$data = [
				'error' => true,
				'message' => 'Email Or Password wajib diisi !!'
			];

			echo json_encode($data);
			exit();
		}

		$userData = [
			'email' => @$_POST['email'],
			'password' => @$_POST['password']
		];

		$checkUser = Users::userByEmail($userData['email']);

		if(!$checkUser) {
			$data = [
				'error' => true,
				'message' => 'Email tidak terdaftar !!'
			];

			echo json_encode($data);
			exit();
		}

		if(!password_verify($userData['password'], $checkUser['password'])) {
			$data = [
				'error' => true,
				'message' => 'Password salah !!'
			];

			echo json_encode($data);
			exit();
		}

		$currentDateTime = new \DateTime();
		$token = bin2hex(random_bytes(64)); // Generate random token
		$hashedToken = password_hash($token, PASSWORD_DEFAULT);

		$saveLoginStatus = Users::login([
			'loginStatus' => 1, 
			'lastLogin' => $currentDateTime->format('Y-m-d H:i:s'), 
			'token' => $hashedToken, 
			'email' => $userData['email']
		]);

		if($saveLoginStatus > 0) {
			$expiryTime = time() + 3600;

			$dataLogin = Users::userByEmail($userData['email']);

			$_SESSION['name'] = $dataLogin['name'];
			$_SESSION['email'] = $dataLogin['email'];
			$_SESSION['status'] = $dataLogin['loginStatus'];
			$_SESSION['role'] = $dataLogin['role'];
			$_SESSION['token'] = $dataLogin['token'];
			$_SESSION['login_time'] = $expiryTime;

			$data = [
				'success' => true,
				'message' => 'Login successfully!',
				'data' => $dataLogin,
				'token' => $hashedToken
			];

			echo json_encode($data);
		} else {
			$data = [
				'success' => false,
				'message' => 'User sedang digunakan!'
			];

			echo json_encode($data);
		}
	}

	public function logout()
	{
		$userData = [
			'token' => @$_POST['token']
		];

		if($userData['token'] === NULL) {
			echo json_encode(['error' => true, 'message' => 'User token not found!']);
		} else {
			$userToken = Users::userByToken($userData['token']);

			if(!$userToken) {
				$data = [
					'error' => true,
					'message' => 'Data user tidak ditemukan !!'
				];

				echo json_encode($data);
				exit();
			}

			$userLogout = Users::logout($userToken['email']);

			if($userLogout > 0) {
				$data = [
					'success' => true,
					'message' => 'Logout successfully!',
					'data' => $userToken
				];

				echo json_encode($data);

				session_unset();
				session_destroy();
				
				unset($_SESSION['name']);
				unset($_SESSION['email']);
				unset($_SESSION['status']);
				unset($_SESSION['login_time']);
				exit();
			}
		}


	}
}