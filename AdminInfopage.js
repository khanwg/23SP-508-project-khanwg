$(document).ready(function(){
	
	var tableStudents = $('#table-students').DataTable({
		"dom": 'Blfrtip',
		"autoWidth": false,
		"processing":true,
		"serverSide":true,
		"pageLength":15,
		"lengthMenu":[[15, 25, 50, 100, -1], [15, 25, 50, 100, "All"]], // Number of rows to show on the table
		"responsive": true,
		"language": {processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>'}, // Loading icon while data is read from the database
		"order":[],
		"ajax":{
			url:"AdminInfopage-Action.php",
			type:"POST",
			data:{
				action:'listStudents'
			},
			dataType:"json",
			success: function(data) {
				console.log(data);
			}
		},
		"columns": [
			{"data": "V-Number"},
			{"data": "department_name"},
			{"data": "advisor_name"},
			{"data": "eID"},
			{"data": "Name"},
			{"data": "DOB"},
			{"data": "Total Credits", "defaultContent": "NA"},
			{"data": "Enrollment Date", "defaultContent": "Unknown"},
			{"data": "Expected Graduation Date", "defaultContent": "Unknown"}
		],
		"buttons": [
			{
				extend: 'excelHtml5',
				title: 'Students',
				filename: 'Students',
				exportOptions: {columns: [0,1,2,3,4,5,6,7,8]}
			},
			{
				extend: 'pdfHtml5',
				title: 'Students',
				filename: 'Students',
				exportOptions: {columns: [0,1,2,3,4,5,6,7,8]}
			},
			{
				extend: 'print',
				title: 'Students',
				filename: 'Students',
				exportOptions: {columns: [0,1,2,3,4,5,6,7,8]}
			}
		]
	});	
	
	 // Add click event listener to "Sort by Name (ASC)" button
  $('#sortAsc').click(function() {
    // Send AJAX request to retrieve sorted data
    $.ajax({
      type: 'POST',
      url: 'AdminInfopage-Action.php',
      data: {order: 'asc'},
      dataType: 'json',
      success: function(data) {
        // Replace table data with new sorted data
        var table = $('#table-students').DataTable();
        table.clear().draw();
        table.rows.add(data).draw();
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
      }
    });
  });
	  // Add click event listener to "Sort by Name (DESC)" button
  $('#sortDesc').click(function() {
    // Send AJAX request to retrieve sorted data
    $.ajax({
      type: 'POST',
      url: 'AdminInfopage-Action.php',
      data: {order: 'desc'},
      dataType: 'json',
      success: function(data) {
        // Replace table data with new sorted data
        var table = $('#table-students').DataTable();
        table.clear().draw();
        table.rows.add(data).draw();
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
      }
    });
  });
});

	
	
