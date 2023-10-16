
$(document).ready(function() {

	$('#login-user').on('click', '.signin', function(e) {
		e.preventDefault()
		const email = $('#email').val()
		const password = $('#password').val()
		Login({email: email, password: password})
	})

	$('#default-sidebar').on('click', '.signout', function(e) {
		e.preventDefault()
		Swal.fire({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, logout!'
		}).then((result) => {
			if (result.isConfirmed) {
				const tokenData = getTokenLogin('token').token
				if(tokenData) {
					Logout(tokenData)
				}
			}
		})
	})

	// checkSessionLogin()
	if(pagePath !== 'admin') getAllData(pagePath, 1)

	// Pagination displaying data consume
	$('#displaying').on('click', '.page-link', function(e) {
		e.preventDefault()
		const keyword = $('#search-data').val();
		const pageNum = $(this).data('num')

		if(keyword) {
			getAllData(pagePath, pageNum, keyword)
		} else {
			getAllData(pagePath, pageNum, keyword)
		}

	})

	$('#displaying').on('keyup', '#search-data', function(e) {
		e.preventDefault()

		const keyword = e.target.value

		const param = {
			data: keyword
		}

		searchData(param, pagePath)
	})

	$('#displaying').on('click', '.copyButton', function(e) {
		let kode = $(this).data('kode');
		let textToCopy = $(this).closest('.barcode-copy').find('.data-barcode-copy').text();

		let tempTextArea = document.createElement('textarea');
		tempTextArea.value = textToCopy;
		document.body.appendChild(tempTextArea);
		tempTextArea.select();

		try {
			document.execCommand('copy');
			showToast(`<i class="fa-solid fa-check"></i> Kode bayar ${kode} berhasil di copy`);
		} catch (err) {
			console.error('Gagal menyalin teks ke clipboard:', err);
		}

		document.body.removeChild(tempTextArea);
	});

	
})