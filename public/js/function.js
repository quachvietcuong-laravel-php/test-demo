	// Set up csrf-token for ajax
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
	// Show error message and fade it
	function showAndFadeErrorText(idElement, message) {
		$(idElement).removeClass('hidden').text(message);
		setTimeout(function(){
			$(idElement).text('').addClass('hidden')
		}, 3000);
	}

	// Show success message with Sweet Alert and reset form data
	function alertAndResetForm(message, formId) {
		Swal.fire('Good job!', message, 'success');
    	document.getElementById(formId).reset();
	}