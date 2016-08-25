<?php
	$content = 'Este libro de tan alto y significativo nombre -Libro de Buen Amor1- fue compuesto en aquel lugar lamentable �donde toda incomodidad tiene su asiento, y donde todo triste ruido hace su habitaci�n�. D�gase claro que su autor estaba preso cuando le escribi�2. Mas la aspereza y desabrimiento del lugar, no engendr� un libro amargo y desalentado, como el de Silvio Pellico, ni versos desgarradores y dolientes, como los del autor de la Lira Focia, sino un libro claro, jocundo, desasosegado y burl�n; a veces libertino, a veces gravemente moralizador; unas, urbano, otras, transcendiendo a flores r�sticas y montaraces. Se le ha comparado con Rabelais y con Chaucer, y m�s cerca est� de la minuciosa erudici�n del autor del Parlement of foules, que de la machuna alegr�a gala del padre de Pantagruel. Es un libro espa�ol, y, m�s a�n, castellano; y al trav�s del grave metro del mester de clerec�a, ondula y vibra el esp�ritu nacional, elegante y se�or, sobreponi�ndose y dominando a los amables horrores y libertinas licencias que relata. Y para referir tanto suceso, el r�gido y bronco romance, que en el Poema del Cid suena al paso de andar de los f�rreos barraganes, y en Berceo se apoya en clericales rodrigones latinos, en este libro aparece suelto, destrabado, �gil, gracioso, a veces un poco femenino, inflamado por una poderosa y l�rica inspiraci�n. Su autor se llama Juan Ruiz, de menester arcipreste de Hita, en la Provincia de Guadalajara3. Vivi� a mediados del siglo XIV, siendo arzobispo de Toledo Don Gil Albornoz (1337-1367) y reinando en Castilla el se�or rey Alfonso XI. Unos creen que fue natural de Alcal�, otros que de Guadalajara. Muri� antes de 1351, pues en una donaci�n hecha por el arzobispo D. Gil, en 7 de enero del dicho a�o, ordena al arcipreste de Hita, Don Pedro Fern�ndez, ponga en posesi�n al monasterio de San Blas de Villaviciosa de una casa y heredad, objeto de la donaci�n. Si Juan Ruiz no hab�a muerto para esa fecha, desempe�ar�a otro cargo; lo cierto es que en 1351 no era arcipreste de Hita.';
	$explodedContent = explode(" ", $content);
?>

<html>
<head>

</head>
<body>
	<div class="row">
		<div class="small-12 columns">
			<?php foreach ($explodedContent as $word) {
				echo ' <span class="someClass"> ' . $word .  ' </span>';
			}?>
		</div>
	</div>
	<?php require 'includes/javascript_basic.php';?>
	<script>
		$('.someClass').click(function(clickEle) {
			var wordClicked = $(this).html();
			alert(wordClicked);
		});
	</script>
</body>
</html>

