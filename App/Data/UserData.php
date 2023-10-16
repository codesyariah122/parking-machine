<?php

namespace App\Data;

class UserData {

	public function data()
	{

		return [
			[
				'id' => 1,
				'name' => 'Puji Ermanto',
				'email' => 'admin@admin.com',
				'role' => 'admin',
				'password' => password_hash("admin", PASSWORD_DEFAULT),
				'loginStatus' => 0
			],
			[
				'id' => 2,
				'name' => 'Dadan Kuswanto',
				'email' => 'kasir@kasir.com',
				'role' => 'kasir',
				'password' => password_hash("kasir", PASSWORD_DEFAULT),
				'loginStatus' => 0
			]
		];
	}

}