<?php
/*
   Copyright (c) Daniel J. Arredondo
   The MIT License (MIT)

   This is a chat tool for the website
   should include:
   	> Select friend
	> Send messages
*/

include 'ddb.php';

if (!(isset($_SESSION['username']))) {
	header('Location: /~arredon/world0/world0.php');
}

echo "<div class=\"container\">";

// app layout
echo "
	<select id=\"clients\"><select>
	<input id=\"message\" type=\"text\" placeholder=\"message\">
	<input id=\"submit\" type=\"submit\" value=\"send\">
	<br>
	<pre id=\"output\"></pre>
";

// javascript
//<script type=\"text/javascript\" src=\"/socket.io/socket.io.js\">
/*
	var objectSocket = io.connect('http://babbage.cs.pdx.edu:8001');
	var objectSocket = io.connect('http://quark.cs.pdx.edu:8001');	// works

	var objectSocket = io.connect('http://quark.cs.pdx.edu:8080'
			|| 'http://babbage.cs.pdx.edu:8080');
*/
echo "
	<script type=\"text/javascript\">
		var objectSocket = io.connect('http://babbage.cs.pdx.edu:8081');

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
			// if message is from this user, diplay message on the right
			if (strIdent === objectData.strFrom) {
				$('#output').prepend(jQuery('<div></div>')
					.attr({
						'align': 'right'
					})
					.text(objectData.message + ' --(me)')
				);
				//+ ' --' + objectData.strFrom + '</div>');
			}
			else {
				$('#output').prepend($('<div></div>')
					.text(objectData.strFrom + ': '
						+ objectData.message));
			}
			/*
			$('#output').prepend(objectData.strFrom + ': ' 
				+ objectData.message + '\\n');
			$('#output').prepend(objectData.strFrom + ' to ' 
				+ objectData.strTo + ': '
				+ objectData.message + '\\n');
			*/
		});

		// send message, clear box
		$('#submit').on('click', function() {
			objectSocket.emit('message', {
				'strTo': $('#clients').val(),
				'strFrom': '$_SESSION[username]',
				'message': $('#message').val()
			});
			$('#message').val('');
		});

		$('#message').keypress(function(event) {
			if (event.which == 13) {
				$('#submit').trigger('click');
			}
		});

	</script>
";

echo "
	</div>
";
?>
