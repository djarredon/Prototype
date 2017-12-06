<?php
/*
   Copyright (c) Daniel J. Arredondo
   The MIT License (MIT)

   This is a chat tool for the website
   should include:
   	> Select friend
	> Send messages
*/

//include 'ddb.php';

echo "
<nav class=\"navbar navbar-default navbar-fixed-bottom\">
<div class=\"container\">";

// app layout
echo "
	<pre id=\"output\"></pre>
	<select id=\"clients\"><select>
	<input id=\"message\" type=\"text\" placeholder=\"message\">
	<input id=\"submit\" type=\"button\" value=\"send\">
";

echo "
	<script type=\"text/javascript\">
		var objectSocket = io.connect('http://babbage.cs.pdx.edu:8001');

		var strIdent = '';

		objectSocket.emit('user', {
			'username': '$_SESSION[username]'
		});

		objectSocket.on('hello', function(objectData) {
			strIdent = objectData.strIdent;
		});

		objectSocket.on('clients', function(objectData) {
			$('#clients')
				.empty()
				.append($('<option></option>')
					.val('everyone')
					.text('everyone')
				)
				.each(function() {
					for (var i = 0; i < objectData.strClients.length; i += 1) {
						$(this)
							.append($('<option></option>')
								.val(objectData.strClients[i])
								.text(objectData.strClients[i])
							)
						;
						
						if (objectData.strClients[i] === strIdent) {
							$(this).find('option').last()
								.text(objectData.strClients[i] + ' - me')
							;
						}
					}
				})
			;
		});

		objectSocket.on('message', function(objectData) {
			$('#output').append(objectData.strFrom + ' to ' 
				+ objectData.strTo + ': '
				+ objectData.message + '\\n');
		});

		$('#submit').on('click', function() {
			objectSocket.emit('message', {
				'strTo': $('#clients').val(),
				'strFrom': '$_SESSION[username]',
				'message': $('#message').val()
			});
		});

	</script>
";

echo "
	</div>
	</nav>
";
?>
