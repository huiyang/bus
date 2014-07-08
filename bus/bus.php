<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" type="text/css" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>
	loadBusData();
	setInterval(loadBusData, 5000);
	function loadBusData() {
		//$('#result').html('Loading...');
		$.ajax({
			type: "GET",
			dataType: "jsonp",
			url: "http://www.ctbsolutions.com.my/jsonp/?a=eta.aspx?bid=<?php echo $_GET['id']; ?>",
			error: function() {
				$('#result').html('<table class="table"><tr><td>Error</td></tr></table>');
			},
			success: function (jqXHR) {
				result = jqXHR.a;
				if (result == '') {
					$('#result').html('<table class="table"><tr><td>No data</td></tr></table>');
					return;
				}
				xmlDoc = StringtoXML(result);
				//console.log(xmlDoc );
				var x = xmlDoc.getElementsByTagName("dict");
				//console.log(result);
				//console.log(xmlDoc);
				var y=x[0].getElementsByTagName("array");//determine category
				var z=y[0].getElementsByTagName("dict");//the place info - contains string0=placeName, string1=queryId
				var LatBS  = x[0].getElementsByTagName("string")[2].firstChild.nodeValue;
				var LongBS = x[0].getElementsByTagName("string")[3].firstChild.nodeValue;
				var str = str + "<table id='tab1' width='100%' class='resultTab'>";
				var curr = new Date();
				var month = curr.getMonth() + 1;
				var day = curr.getDate();
				var year = curr.getFullYear();
				var hours = curr.getHours();
				var minutes = curr.getMinutes();
				var ampm ;    
				if (minutes < 10) {
					minutes = "0" + minutes;
				}
				if(hours > 11){
					ampm = "PM";
				} else {
					ampm = "AM";
				}
				str = "<table class=\"ui table celled\">";
				//str = str + "<tr class='tabHead1'>";
				//str = str +     "<td colspan='4'>";
				//str = str +         "<table>";
				
				str = str +         "<tr>";
				str = str +             "<td>Date</td>";
				str = str +             "<td >: "+ day + "/" + month + "/" + year +"</td>";
				str = str +         "</tr> ";
				str = str +         "<tr>";
				str = str +             "<td>Time</td>";
				str = str +             "<td >: "+ hours + ":" + minutes + " " + ampm + "</td>";
				str = str +         "</tr> ";
				str = str +         "<tr>";
				str = str +             "<td>Bus Stop</td>";
				str = str +             "<td >: "+ x[0].getElementsByTagName("string")[0].firstChild.nodeValue + " - " + x[0].getElementsByTagName("string")[1].firstChild.nodeValue + "</td>";
				str = str +         "</tr> ";
				str = str +         "</table>";
				//str = str +     "</td>";
				//str = str + "</tr>";
				str = str + "<table class=\"ui table celled segment\">";
				str = str + "<tr class='tabHead2'>";
				str = str + "<th>Route</th>";
				str = str + "<th align='left'>Last Destination</th>";
				str = str + "<th>Time To Arrive</th>";
				str = str + "<th>Map</th>";
				str = str + "</tr>";
				
				var str2 = "";
				//ETA info
				   for (i=0;i<y.length ; i++){
						var z=y[i].getElementsByTagName("dict");
						
						for (j=0; j<z.length; j++){
							var arrow = "arrow" + j ; //set row name
							//row = String(row);
						   
							var LatBus = z[j].getElementsByTagName("string")[1].firstChild.nodeValue;
							var LongBus = z[j].getElementsByTagName("string")[2].firstChild.nodeValue;
							var route = z[j].getElementsByTagName("string")[3].firstChild.nodeValue;
							 
							str = str + "<tr class='result' >";
							str = str +     "<td valign='middle'>";
							str = str +     "<img id='"+ arrow +"' class=\"imgArrow\" src='images/arrowRight.png' alt='arrow' width='10px' style=' visibility : hidden '/>";
							str = str +     route ; //route
							str = str +     "</td>";
							str = str +     "<td align='left'>";
							str = str +     z[j].getElementsByTagName("string")[4].firstChild.nodeValue; //last destination
							str = str +     "</td>";
							str = str +     "<td>";
							str = str +     z[j].getElementsByTagName("string")[0].firstChild.nodeValue; //ETA
							str = str +     "</td>";
							str = str +     "<td onclick='changeBG(\""+arrow+"\","+ z.length +");showETAmap("+ LatBus +","+ LongBus +","+ LatBS +","+ LongBS +",\""+ route +"\");'>";
							//str = str +     "<img src='images/bus.png' style='cursor:pointer' width='50px' /> "; ; //click to view map
							str = str + "Not supported";
							str = str +     "</td>";
							str = str + "</tr>";
							
							
						}
					}
				 
				str = str + "</table>";
				
				$('#result').html(str);
			}
		});
	}
	function StringtoXML(text){
		if (window.ActiveXObject){
		  var doc=new ActiveXObject('Microsoft.XMLDOM');
		  doc.async='false';
		  doc.loadXML(text);
		} else {
		  var parser=new DOMParser();
		  var doc=parser.parseFromString(text,'text/xml');
		}
		return doc;
	}
</script>
</head>
<body>
	<div>
		<table class="table">
			<tr><td><a href='index.php' style="padding: 10px;">Back</a></td></tr>
		</table>
	</div>	
	<div id="result" style="padding: 10px;"><table class="table"><tr><td>Loading...</td></tr></table></div>
</body>
</html>