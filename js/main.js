;(function ( $, window, document, undefined ) {
	/**
	 * Add new row click handler
	 * @param  {Event}	e	Click event
	 */
	$('.btn--add-row').on('click', function(e) {
		e.preventDefault();

		var $tbody = $('table#form-table tbody'),
			newTableRowIndex = $tbody.find('tr').length + 1;
			html = '';

		html += '<tr>';
		html += '<td><input type="text" name="key_' + newTableRowIndex + '"></td>';
		html += '<td><input type="text" name="value_' + newTableRowIndex + '"></td>';
		html += '<td class="remove text--center"><a href="#" class="btn btn--remove-row"><i class="fa fa-minus-square fa-1x"></i></td>';
		html += '</tr>';

		$tbody.append(html);
	});

	/**
	 * Delete row click handler
	 * @param  {Event}	e	Click event
	 */
	$('.quick-info').on('click', '.btn--remove-row', function(e) {
		e.preventDefault();

		var $tr = $(this).parents('tr');
		$tr.remove();
	});

})( jQuery, window, document );
