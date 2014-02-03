<html>

<head>

<style>
h2{
	font-family:Tahoma, Geneva, sans-serif;
	font-size:18px;
	font-weight:bold;
	text-align:left;
	padding-left:10px;
}
p{
	font-family:Tahoma, Geneva, sans-serif;
	font-size:12px;
	text-align:justify;
	width:95%;
	padding-left:10px;
}
a{
	color:#6699CC;
	font-family:Tahoma, Geneva, sans-serif;
	font-weight:bold;
	font-size:12px;
}

a:hover{
	color:#6699CC;
	font-family:Tahoma, Geneva, sans-serif;
	font-weight:bold;
	font-size:12px;
}

</style>

</head>

<body>
<div id="headerimg" align="center"><img src="images/v2/logoLangblog.png"></div>


<h2><?php if($_GET["id"]=='c') echo 'Crèdits';else echo 'Créditos';?></h2>


<p><?php if($_GET["id"]=='c') echo "LANGblog és un projecte d'innovació docent del Programa de Llengües de la Universitat Oberta de Catalunya, 2008 (projecte finançat per AGAUR - Generalitat de Catalunya, 2007 MQD 00158).";
	else echo "LANGblog es un proyecto de innovación docente del Programa de Lenguas de la Universitat Oberta de Catalunya, 2008 (proyecto financiado por AGAUR - Generalitat de Catalunya, 2007 MQD 00158).";
?></p>

<p><?php if($_GET["id"]=='c') echo "L'investigador principal és Federico Borges Sáiz, professor d'anglès del Programa de Llengües, UOC. L'equip de creació i desenvolupament de LANGblog ha estat format per membres del departament de Tecnologia Educativa de la UOC i professorat de les assignatures de xinès, francès, anglès i japonès del Programa de Llengües de la  UOC.";
	else echo "El investigador principal es Federico Borges Sáiz, profesor de inglés del Programa de Lenguas, UOC. El equipo que ha creado y desarrollado LANGblog está compuesto por miembros del departamento de Tecnología Educativa de la UOC, y profesorado de las asignaturas de chino, francés, inglés y japonés del Programa de Lenguas de la UOC.";
?></p>

<p><?php if($_GET["id"]=='c') echo "Qualsevol institució educativa pot disposar de LANGblog lliurement. Més informació sobre aquest ús lliure i sobre LANGblog a la web <a href='http://www.eduforge.org/projects/langblog' target='_blank'>http://www.eduforge.org/projects/langblog</a>";
	else echo "Cualquier institución educativa puede disponer de LANGblog libremente. Más información sobre este uso libre y sobre LANGblog en la web <a href='http://www.eduforge.org/projects/langblog' target='_blank'>http://www.eduforge.org/projects/langblog</a>";
?></p>

<p><b>Powered by : UOC - RED5 - FFMPEG</b></p>
</body>
 
</html>
