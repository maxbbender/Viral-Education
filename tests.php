<html>
<head>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script>
			function go () {
				
				var words = $("#text").text().split(" ");
				$("#hello").empty();
				$.each(words, function(i, v) {
					$("#hello").append($("<span>").text(v));
				});
				alert("done");
			}
			
			function dodo(){
				$("hello")
			}
		</script>
	</head>
<body>
	<p id="hello">Hello How Are You</p>
	<textarea id="text"></textarea><br>
	<button onclick="go()">Go</button>
	<br><br>
	Translated<br>
	<div id="trans">FSFSF</div>
	
</body>
</html>