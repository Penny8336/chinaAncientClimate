var fill;
var projection;
var china;
var chooseFlag;


function DrawMap(center,scale,fill_,chooseFlag_){

    // Map and projection
    projection = d3.geoMercator().center(center).scale(scale)
    fill = fill_;
    chooseFlag = chooseFlag_
    // Load external data and boot
    d3.json("https://raw.githubusercontent.com/Penny8336/DV-homework2/master/C.json",draw );

    // if(chooseFlag)
    //     chooseArea();
    

}

function draw(mapjson){
    var path = d3.geoPath().projection(projection)


    // The svg
    china = d3.select("#map")
    .append("svg")
    .attr("width", 1200)
    .attr("height", 600)
    .attr("id", "pathSvg")

    // Draw the map
    china.append("g")
    .selectAll("path")
    .data(mapjson.features)
    .enter()
    .append("path")
    .attr("id", function(d){ return d.properties.name})
    .attr("class", "MAP")
    .attr("fill",fill)
    .attr("d", d3.geoPath().projection(projection))
    .style("stroke", "#fff")
    .style("opacity", .5)
    .on("mouseover", mouseover)
	.on("mouseleave", mouseleave)
    .on("click", function(d){
        if(chooseFlag){
            var provins = document.getElementById("provin").value.split(".");
            if(provins.includes(d.properties.name)){
                provins.splice(provins.indexOf(d.properties.name),1);
                d3.select(this).style("opacity", .5);
            }
            else if(provins.length <= 5){
                provins.push(d.properties.name);
                d3.select(this).style("opacity", .9)
            }
            document.getElementById("provin").value = provins.join(".");
            //alert(document.getElementById("provin").value);
        }
    })

    var star = "https://svgshare.com/i/Kpp.svg"

    china.append("g")
    .selectAll("labels")
    .data(mapjson.features)
    .enter()
    .append('svg:image')
    .attr("xlink:href", star)
    .attr("d", d3.geoPath().projection(projection))
    .attr("x", function(d) {
        console.log(path.centroid(d)[0])
        return path.centroid(d)[0] - 15
    })
    .attr("y", function(d) {

        return path.centroid(d)[1] - 15
    })
    .attr("width", 20)
    .attr("height", 20)

    ;
}

	var mouseover = function(d) {
        console.log(d.properties.name)
        document.getElementById("Submit").innerHTML = d.properties.name
	  }
    var mouseleave = function(d) {
        document.getElementById("Submit").innerHTML ="cilck map to select area "
        }
      
// function chooseArea(){
//     console.log(d3.selectAll("path"));
// 	d3.selectAll("path")
// 	.on("click", function(d){
// 		var provins = document.getElementById("provin").value.split(".");
// 		if(provins.includes(d.properties.name)){
// 			provins.splice(provins.indexOf(d.properties.name),1);
// 			d3.select(this).style("opacity", .5);
// 		}
// 		else if(provins.length <= 5){
// 			provins.push(d.properties.name);
// 			d3.select(this).style("opacity", .9)
// 		}
// 		document.getElementById("provin").value = provins.join(".");
// 		//alert(document.getElementById("provin").value);
// 	});
// }