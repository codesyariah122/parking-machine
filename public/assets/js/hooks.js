async function getData(endPoint) {
	try {
		const parsed = await fetch(endPoint, {
			method: 'GET'
		})

		if (!parsed.ok) {
			throw new Error("Data not found!");
		}

		const result = await parsed.json();
		return result;

	}catch (err) {
		console.error(err)
	}
}


async function postData(endPoint, data) {
	try {
		const response = await fetch(endPoint, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify(data)
		})

		if (!response.ok) {
			throw new Error("Data not found!");
		}

		const result = await response.json();
		return result;

	} catch (err) {
		console.error(err)
	}
}