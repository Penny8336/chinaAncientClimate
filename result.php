<?php
	$sqlEvent = $_POST['event'];
	$sqlYearStart = $_POST['start'];
	$sqlYearEnd = $_POST['end'];
	$sqlProvin = $_POST['provin'];
	
	$args = "event=".$sqlEvent."&start=".$sqlYearStart."&end=".$sqlYearEnd."&provin=".$sqlProvin;

	echo $args;
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Historical Climate</title>
	<script src="https://d3js.org/d3.v4.js"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="./layout.css">
	<script src="map.js"></script>
	<script src="//unpkg.com/timelines-chart@2"></script> 
	<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/ramda/0.25.0/ramda.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.4/lodash.js"></script> -->

	<!-- color -->
	<script src="https://d3js.org/d3-color.v1.min.js"></script>
	<script src="https://d3js.org/d3-interpolate.v1.min.js"></script>
	<script src="https://d3js.org/d3-scale-chromatic.v1.min.js"></script>

    <script src="https://d3js.org/d3-geo-projection.v2.min.js"></script>
    <script src='https://npmcdn.com/@turf/turf/turf.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>

</head>
<body class = "all" onload = "Init()">

	<div class = "container shadow-lg p-3 mb-5 bg-light rounded" style="margin-top: 50px;">
		<div class="row" >
			<div class="col-md-12" id="myhead" >
				<h3 class="text-center" style="margin-bottom: 100px;">Meteorological History in China</h3>
			</div>
		</div>

		<div class="row" >
			<div class="col-md-2"></div>
			<div class="col-md-8" ></div>
			<div class="col-md-2"></div>
		</div>

		<div class="row">
			<div class="col-md-8" ><div id="map" ></div></div>
			<div class="col-md-4" id="bar"  style="padding-top: 200px;" ></div>
		</div>
		<!-- style="background-color: #CDE1C4;" -->
		<!-- <div class="row">
            <div class="col-md-1" ></div>
            <div class="col-md-10" style="background-color: rgb(255, 255, 255);"></div>
            <div class="col-md-1"></div>
        </div> -->

        <div id="timeline" style="margin-bottom: 100px"></div>
		<div id="mapsvg"></div>

	</div>

</body>
<script>
	var citysRank = new Array();
	function prepare(data){
		console.log(data);

		var citys = new Array();
		var citysCount = new Array();
		var citysCountRecord = new Array();

		data.forEach(function(item){
			citys.push(item.group);
			var eventCount = 0;
			item.data.forEach(function(eventCategory){
				citysCountRecord = eventCategory.data.length
				eventCount = eventCount + eventCategory.data.length;
			})
			citysCount.push(eventCount);
		})
		drawbarchart(citys, citysCount)
		for (var i = 0; i < citysCount.length; i++){
			let x = citysCount.indexOf(Math.max(...citysCount));
			citysCount[x] = -1 ;
			citysRank.push(citys[x]);
			cityDomain = (citys[x])
		}
		timeline(data,categories,);
		download(data);
		console.log(citysRank)
	}

	//**************** BAR CHART *******************//
	var margin = {top: 30, right: 30, bottom: 70, left: 60},
		width = 300 - margin.left - margin.right,
		height = 300 - margin.top - margin.bottom;

	// append the svg object to the body of the page
	var bar = d3.select("#bar")
		.append("svg")
		.attr("width", width + margin.left + margin.right)
		.attr("height", height + margin.top + margin.bottom)
		.append("g")
		.attr("transform","translate(" + margin.left + "," + margin.top + ")")
		.style("padding-left",100);
		
	function drawbarchart(citys,count){
	var max = Math.max(...count)
	var min = Math.min(...count)
	var colorRange = max-min
	var citydata = new Array();
	
	for (var i = 0;i<citys.length;i++){
		var temp = {"city":citys[i],"count":count[i]}
		citydata[i] = temp
	}
	
	citydata.sort(function(b, a) {
		return a.count - b.count;
	});

	var x = d3.scaleBand()
		.range([ 0, width ])
		.domain(citydata.map(function(d) { return d.city;}))
		.padding(0.2);
	
	bar.append("g")
		.attr("transform", "translate(0," + height + ")")
		.call(d3.axisBottom(x))
		.selectAll("text")
		.attr("transform", "translate(-10,0)rotate(-45)")
		.style('font-size', '12px')
		.style("text-anchor", "end");

	// Add Y axis
	var y = d3.scaleLinear()
		.domain([0, max])
		.range([ height, 0]);

	var colorlist = ["#B6CED1", "#85C1E9" ,"#3498DB","#2874A6","#1B4F72"]
	var myColor = d3.scaleThreshold()
		.range(colorlist)
		.domain([colorRange*0.1, colorRange*0.3, colorRange*0.5, colorRange*0.7]);

	bar.append("g")
		.attr("id", "yaxis")
		.call(d3.axisLeft(y));

	bar.select("#yaxis")
		.append("text")
		.attr("x",100)
		.attr("y",0)
		.attr('transform', `translate(-50, ${height/2}) rotate(-90)`)
		.attr('fill', '#000')
		.style('font-size', '15px')
		.style("margin-right", "50px")
		.text('total number of events');

  	var mouseOverBar = function(d) {
		d3.selectAll(".MAP")
		.transition()
		.duration(200)
		.style("opacity", .3)
		
		choosemap = this.id
		d3.select("#"+choosemap)
		.transition()
		.duration(200)
		.style("opacity", 1)
	  }
  	var mouseLeaveBar = function(d) {
		d3.selectAll(".MAP")
		.transition()
		.duration(200)
		.style("opacity", .9)
	  }

	  
	bar.selectAll("mybar")
		.data(citydata)
		.enter()
		.append("rect")
		.attr("x", function(d) { return x(d.city); })
		.attr("y", function(d) { return y(d.count); })
		.attr("width", x.bandwidth())
		.attr("height", function(d) { return height - y(d.count); })
		.attr("fill", function(d){return myColor(d.count)})
		.style("opacity",0.9)
		.attr("id", function(d) { return d.city})
		.on("mouseover", mouseOverBar)
		.on("mouseleave", mouseLeaveBar )

	bar.selectAll("textcount")
		.data(citydata)
		.enter()
		.append("text")
		.text(function(d) {return d.count;})
		.attr("font-family" , "sans-serif")
		.attr("font-size" , "11px")
		.attr("x" , function(d) { return x(d.city)+x.bandwidth()/2})
		.attr("y", function(d) { return y(d.count)-5; })
		.attr("fill" , "black")
		.attr("text-anchor", "middle");

		for(i = 0 ; i < citydata.length ; i++){
			console.log(citydata[i].count)
			d3.select("#"+citydata[i].city).attr("fill",myColor(citydata[i].count)).style("opacity",0.9)
		}
	}

	//************** BAR CHART END ******************//


	//**************** TIMELINE *******************//
	var Data = []
	var minTimes = new Date()
	var DateFormat = d3.timeParse("%Y-%m");
	var categories = new Array();
	var testjson = []

	function timeline(data){
		TimelinesChart().width([1100])	
		.data(getRandomData(true))
		.zQualitative(false)
		.zColorScale(d3.scaleOrdinal(d3.schemeRdBu[9]))
		.maxLineHeight([30])
			(document.getElementById('timeline'));

		function getRandomData(ordinal = false) {

			return [...Array(data.length).keys()].map(i => ({
			group: data[i].group,
			data: getGroupData(i)
			}));

			//
			function getGroupData(t) {
				return [...Array(data[t].data.length).keys()].map(i => ({ 
					label: data[t].data[i].label,
					data: getSegmentsData(t,i)
				}));

				//

				function getSegmentsData(t,k) {

					return [...Array(data[t].data[k].data.length).keys()].map(i => {
						DATE = (data[t].data[k].data[i].timeRange.split(","))

							start = new Date(DATE[0]),
							end = new Date(DATE[1]);

						return {
							timeRange: [start, end],
							val: data[t].data[k].data[i].val
						};
					});
				}
			}
		}		
	}
		//**** start excel ****/
	function convertToCSV(objArray) {
		var array = typeof objArray != 'object' ? JSON.parse(objArray) : objArray;
		var str = '';

		for (var i = 0; i < array.length; i++) {
			var line = '';
			for (var index in array[i]) {
				if (line != '') line += ','

				line += array[i][index];
			}

			str += line + '\r\n';
		}

		return str;
	}

	function exportCSVFile(headers, items, fileTitle) {
		if (headers) {
			items.unshift(headers);
		}

		// Convert Object to JSON
		var jsonObject = JSON.stringify(items);

		var csv = this.convertToCSV(jsonObject);

		var exportedFilenmae = fileTitle + '.csv' || 'export.csv';

		var blob = new Blob(["\uFEFF" + csv], { type: 'text/csv;charset=utf-8;' });
		if (navigator.msSaveBlob) { // IE 10+
			navigator.msSaveBlob(blob, exportedFilenmae);
		} else {
			var link = document.createElement("a");
			if (link.download !== undefined) { // feature detection
				// Browsers that support HTML5 download attribute
				var url = URL.createObjectURL(blob);
				link.setAttribute("href", url);
				link.setAttribute("download", exportedFilenmae);
				link.style.visibility = 'hidden';
				document.body.appendChild(link);
				link.click();
				document.body.removeChild(link);
			}
		}
	}
	function download(NotFormatted){
		var headers = {
			model: ' '.replace(/,/g, ''), // remove commas to avoid errors
			chargers: " ",
			cases: " ",
			earphones: " "
		};

		var itemsFormatted = [];

	// format the data
		NotFormatted.forEach((item) => {
			itemsFormatted.push({
			model: item.group.replace(/,/g, ''), // remove commas to avoid errors,
			});
			item.data.forEach((type) => {
				type.data.forEach((items) => {
				itemsFormatted.push({
					chargers: type.label,
					cases: items.val,
    				earphones: items.timeRange
    				});
				});
			});
		});

		var fileTitle = 'csv'; // or 'my-unique-title'

		exportCSVFile(headers, itemsFormatted, fileTitle); // call the exportCSVFile() function to process the JSON and trigger the download
}

		//***** end excel *****/
//**************** TIMELINE END ****************//

	function Init(){
		DrawMap([110,40],550,"#DBEED8");
		d3.json("mysqlAPI.php?<?=$args?>",prepare);
	}
	


</script>
</html>
