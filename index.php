<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!-- Load d3.js -->
    <script src="https://d3js.org/d3.v4.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<!--Plugin CSS file with desired skin-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css"/>
	<script src="https://d3js.org/d3-geo-projection.v2.min.js"></script>

    <!--jQuery-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    
    <!--Plugin JavaScript file-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>
	<link rel="stylesheet" href="./layout.css">
	<script src="map.js"></script>
</head>
<body class = "all" style="background-color:rgb(244, 250, 250);" onload="Init()">
	<div class="row" style="margin-bottom: 100px;"></div>
	<div class = "container shadow-lg p-3 mb-5 bg-light rounded">
		<div class="row" >
			<div class="col-md-12 bg-light" id="myhead">
				<h1 class="text-center" >Meteorological History in China</h1>
			</div>
		</div>
		<div class="row" >
			<div class="col-md-2"></div>
			<div class="col-md-8" >
				<nav class="navbar navbar-expand-lg navbar-light bg-light">
				<div class="collapse navbar-collapse" id="main_nav">
					<ul class="navbar-nav">
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="category">  EVENTS  </a>
						<ul class="dropdown-menu">
							<?php
								$temp = file_get_contents("code.json");
								$code = json_decode($temp, true);
								$count = 0 ;
								foreach($code['level1'] as $level1){
									echo '<li><a class="dropdown-item" href="#"> '.$level1.' </a>';
									echo '<ul id="'.$level1.'"class="submenu dropdown-menu">';
									$count2 = 0 ;
									foreach($code['level2'][$count] as $level2){
										echo '<li><a class="dropdown-item" onclick=\'setEvent('.$code['code'][$count][$count2].',"'.$level2.'")\'> '.$level2.' </a></li>';
										$count2 = $count2 +1 ;
									}
									echo '</ul>';
									echo '</li>';
									$count = $count +1 ;
								}
							?>
						</ul>
					</ul>
				</div>
				<div >
					<form action="result.php" method="post" id='myform'>
						<input type="hidden" id="event" name="event" value="">
						<input type="hidden" id="provin" name="provin" value="">
						<input type="hidden" id="start" name="start" value="1000">
						<input type="hidden" id="end" name="end" value="1700">
						<a  style="opacity: 0.85" onclick="validateForm()" type="submit" value="Submit">Submit</a>
					</form>
				</div>
				</nav>
			</div>
			<div class="col-md-2"></div>
		</div>

		<div class="row">
			<div class="col-md-2" ></div>
			<div id="Submit" class="col-md-8" style="margin-left: 212px; opacity: 0.85" >cilck map to select area</div>
			<div class="col-md-2"></div>
		</div>

		<div class="row">
			<div class="col-md-2" ></div>
			<div class="col-md-8" ><div id="map" style="margin-bottom: 130px;"></div></div>
			<div class="col-md-2"></div>
		</div>
		<div class="row">
			<div class="col-md-2" ></div>
			<div class="col-md-8" ><input type="text" class="js-range-slider" name="my_range" value="" /></div>
			<div class="col-md-2">	<div class="row" style="margin-bottom: 130px;"></div>
		</div>
	</div>
</body>
<script src="selector.js"></script>
</html>