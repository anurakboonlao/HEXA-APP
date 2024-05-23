$("#checkall input").change(function() {
	var listaElementos = document.querySelectorAll('.check');

	for(var i=0, n=listaElementos.length; i<n; i++){
		var element = listaElementos[i];

		if($('#checkall input').is(":checked")) {
			element.MaterialCheckbox.check();
	}
		else {
			element.MaterialCheckbox.uncheck();
		}
	}
});

$('.check').change(function() {
	var listaElementos = document.querySelectorAll('.check');
	
    for (var i = 0, n = listaElementos.length; i < n; i++) {
		var element = listaElementos[i];
        if ($('.check input:checked').length == $('.check input').length) {
			document.querySelector('#checkall').MaterialCheckbox.check();
        } else {
			document.querySelector('#checkall').MaterialCheckbox.uncheck();
        }
    }
});

function thousands_separators(num) {
    var num_parts = num.toString().split(".");
    num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return num_parts.join(".");
}