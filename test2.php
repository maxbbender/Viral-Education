<?php
	$content = 'Este libro de tan alto y significativo nombre -Libro de Buen Amor1- fue compuesto en aquel lugar lamentable «donde toda incomodidad tiene su asiento, y donde todo triste ruido hace su habitación». Dígase claro que su autor estaba preso cuando le escribió2. Mas la aspereza y desabrimiento del lugar, no engendró un libro amargo y desalentado, como el de Silvio Pellico, ni versos desgarradores y dolientes, como los del autor de la Lira Focia, sino un libro claro, jocundo, desasosegado y burlón; a veces libertino, a veces gravemente moralizador; unas, urbano, otras, transcendiendo a flores rústicas y montaraces. Se le ha comparado con Rabelais y con Chaucer, y más cerca está de la minuciosa erudición del autor del Parlement of foules, que de la machuna alegría gala del padre de Pantagruel. Es un libro español, y, más aún, castellano; y al través del grave metro del mester de clerecía, ondula y vibra el espíritu nacional, elegante y señor, sobreponiéndose y dominando a los amables horrores y libertinas licencias que relata. Y para referir tanto suceso, el rígido y bronco romance, que en el Poema del Cid suena al paso de andar de los férreos barraganes, y en Berceo se apoya en clericales rodrigones latinos, en este libro aparece suelto, destrabado, ágil, gracioso, a veces un poco femenino, inflamado por una poderosa y lírica inspiración. Su autor se llama Juan Ruiz, de menester arcipreste de Hita, en la Provincia de Guadalajara3. Vivió a mediados del siglo XIV, siendo arzobispo de Toledo Don Gil Albornoz (1337-1367) y reinando en Castilla el señor rey Alfonso XI. Unos creen que fue natural de Alcalá, otros que de Guadalajara. Murió antes de 1351, pues en una donación hecha por el arzobispo D. Gil, en 7 de enero del dicho año, ordena al arcipreste de Hita, Don Pedro Fernández, ponga en posesión al monasterio de San Blas de Villaviciosa de una casa y heredad, objeto de la donación. Si Juan Ruiz no había muerto para esa fecha, desempeñaría otro cargo; lo cierto es que en 1351 no era arcipreste de Hita.';
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

