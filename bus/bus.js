	geoPosition.init();
	
	String.prototype.startWith = function(string) {
		return this.indexOf(string) == 0;
	}
	
	function getMyCoordinate(success, error) {
		geoPosition.getCurrentPosition(success, error, {enableHighAccuracy: true});
	}
	function renderMap(myPosition, busStop) {
		$('#map').width('100%').height(400);
		var coords = new google.maps.LatLng(myPosition.coords.latitude, myPosition.coords.longitude);

		var map = new google.maps.Map(document.getElementById("map"), {
			zoom: 18,
			center: coords,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		});
		
		var marker = new google.maps.Marker({
			map: map,
			position: coords
		});
		
		// Render bus stop markers
		for (i in busStop) {
			console.log(busStop[i]);
			marker = new google.maps.Marker({
				map: map,
				icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png',
				url: 'bus.php?id=' + busStop[i].code,
				position: new google.maps.LatLng(busStop[i].latitude, busStop[i].longitude)
			});
			
			google.maps.event.addListener(marker, 'click', function() {
				window.location.href = this.url;
			});
		}
	}
	function getNearestBusStop(data) {
		$.ajax({
			type: 'POST',
			data: data,
			dataType: 'json',
			url: 'nearest.php',
			success: function(xhr) {
				console.log(xhr);
				//console.log(xhr[0].code);
				loadBusData(xhr[0].code, false);
				renderMap(data, xhr);
				setInterval(function() {
					loadBusData(xhr.code);
				}, 5000);
			},
			error: function() {
				alert('Failed to get nearest bus stop.');
			},
		});
	}
	
	function insertBusStop(data) {
		console.log("Inserting to database.");
		$.ajax({
			type: "POST",
			data: data,
			url: 'insert.php',
			success: function(jqXHR) {
				console.log('Inserted ' + data.code);
			}
		});
	}
	
	function loadBusData(number, performInsert) {
		
		//$('#result').html('Loading...');
		//for (var i = 0; i < 4; i++) {
			var code = number.startWith('B') ? number : 'B'+pad(number, 4);
			console.log(code);
			$.ajax({
				type: "GET",
				dataType: "jsonp",
				url: "http://www.ctbsolutions.com.my/jsonp/?a=eta.aspx?bid=" + code,
				error: function() {
					console.log('error');
					$('#result').html('<table class="table"><tr><td>Error</td></tr></table>');
				},
				success: function (jqXHR) {
					//console.log(jqXHR);
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
					var busStopName = x[0].getElementsByTagName("string")[0].firstChild.nodeValue;
					var busStopCode = x[0].getElementsByTagName("string")[1].firstChild.nodeValue;
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
					
					var insert = {
						code: code,
						bus_stop_code: busStopCode,
						bus_stop: busStopName,
						latitude: LatBS,
						longitude: LongBS,
						//route:
						//destination:
					};
					//console.log(insert);
					if (performInsert === undefined || performInsert) {
						insertBusStop(insert);
					}
					
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
		//}
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
	function pad (str, max) {
		str = str.toString();
		return str.length < max ? pad("0" + str, max) : str;
	}