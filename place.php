	<?php
		global $keyword;
		global $distinmiles;
		global $select_category;
		global $distflag;
		$distflag=0;
		$keyword=" ";
		$distinmiles=" ";
		$select_category="default";
		error_reporting(E_ERROR | E_PARSE);
		if(isset($_POST['Category']))
			$select_category=$_POST["Category"];
		$keyword=urlencode($_POST["keyword"]);
		//$select_category=$_POST["Category"];
		//echo "category".$select_category;
		//print $select_category;
		
		if(isset($_POST["distmiles"]))
		{
			$distinmiles=$_POST["distmiles"];
			$distflag=1;

		}
		else {
			$distinmiles=10;
		}
		$travelForm=$_POST["travelform"];
		$radius=1609.34*$distinmiles;
		$radioButton=$_POST["radiofamily"];
		$count1="";
		$t=array();
		$counter2="";
		if($_POST["radiofamily"]=="current_loc") {
		$loc_lat=$_POST["lat"];
		$loc_long=$_POST["long"];
		$start_Address="University of Southern California";
		$extract_table_content="https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=".$loc_lat.",".$loc_long."&radius=".$radius."&type=".$select_category."&keyword=".$keyword."&key=AIzaSyAnXwonImQPfYwRW31rerYVr_pPywUuDdk";
			$response_json=file_get_contents($extract_table_content);
			$output=json_decode($response_json,true);	
	 	}
		elseif($_POST["radiofamily"]=="location") {
			$locationval=urlencode($_POST["location"]);
			$obtainLoc="https://maps.googleapis.com/maps/api/geocode/json?address=".$locationval."&key=AIzaSyAnXwonImQPfYwRW31rerYVr_pPywUuDdk";
			$temp=json_decode(file_get_contents($obtainLoc),true);
			//to extract the latitude and longitude from the decoded json in php
			$loc_lat= $temp['results'][0]['geometry']['location']['lat'];
			$loc_long=$temp['results'][0]['geometry']['location']['lng'];
			$start_Address=$temp['results'][0]['formatted_address'];
			//to get the corresponding table of places that matches the keyword,radius and location
			$extract_table_content="https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=".$loc_lat.",".$loc_long."&radius=".$radius."&type=".$select_category."&keyword=".$keyword."&key=AIzaSyAnXwonImQPfYwRW31rerYVr_pPywUuDdk";
			$response_json=file_get_contents($extract_table_content);
			var_dump($response_json);
			$output=json_decode($response_json,true);

			//echo json_encode($output,JSON_UNESCAPED_SLASHES);
		}
		
		if(isset($_GET["xyz"])) {
			$getPlaceID=$_GET["xyz"];
		 	$extractPhotoReview="https://maps.googleapis.com/maps/api/place/details/json?placeid=".$getPlaceID."&key=AIzaSyAnXwonImQPfYwRW31rerYVr_pPywUuDdk";
		 	$m=file_get_contents($extractPhotoReview);
		 	$outputL2=json_decode($m,true);
		 	if($outputL2['status']=='OK')
		 	 {
		 	 $count1=sizeof($outputL2['result']['photos']);
		 	 if($count1>5) {
		 	 	$counter2=5;
		 	 }
		 	 else {
		 	 	$counter2=$count1;
		 	 }
		 	// json_decode($counter2,true);
		 	for($x=0;$x<$counter2;$x++){
		 		$t[$x]=time();
		 		$photoReference=$outputL2['result']['photos'][$x]['photo_reference'];
            $image1 = file_get_contents('https://maps.googleapis.com/maps/api/place/photo?maxwidth=750&photoreference='.$photoReference.'&key=AIzaSyAnXwonImQPfYwRW31rerYVr_pPywUuDdk');
             file_put_contents("image".$x.".jpg",$image1);
		 	}	 
		 	  echo json_encode($outputL2,JSON_UNESCAPED_SLASHES);
		 	}
		 	  die();
		 	}
		// }   
		//getphpcContent();	
	?>
<!DOCTYPE html>
<head>
	<meta charset="utf-8"/>
	<title>Travel and entertainment search</title>	
</head>
<body id="bodyid" >
		<style>
	.collapse{
  cursor: pointer;
  display: block;
  background: #cdf;
	}
	.collapse + input{
  	display: none; /* hide the checkboxes */
	}
	.collapse + input + div{
  	display:none;
	}
	.collapse + input:checked + div{
 	 display:block;
	}
	h2 {
		font-style: italic;
		font-size: 40px;
	}
	
		th,td {
		border: 1px solid #A9A9A9;
		border-collapse: collapse;
	}
	tr{
		border: 1px solid #A9A9A9;
	}
	hr {border-color: #A9A9A9;}
	fieldset {
		width:650px;
		float:left;
		background-color:#E8E8E8;
		border: 3px solid #A9A9A9;
		border-color: #A9A9A9;	
	}
	table {
		border-collapse: collapse;
		width: 80%;
		border: 1px solid #A9A9A9;
	}
	#displayResults {
		border: 1px solid #A9A9A9;
	}
	#maparea{
		position: absolute;
	
	}
	.map_overlay{
		z-index: 1;
	}
	#floating-panel {
		z-index: 100;
        position: absolute;
        top: 10px;
        left: 25%;
        background-color: #e7e7e7;
        padding: 5px;
        border: 1px solid #999;
        text-align: center;
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
        display: none;
      }
      #h4 {
      	padding-left: 100px;
      	padding-bottom: -30px;
      }
      #demo1 {
      	padding-top: 250px;
      	padding-left:570px;
      }
	#demo{
		display: none;
		margin-top:-50px;
		padding-left:300px;
	}
	#reviewUp {
		padding-left: 500px;
		padding-top: 20px;
	}
	#reviewDown {
		padding-top:20px;
		padding-left: 500px;
	}
	#photoUp {
	padding-left: 500px;
	padding-top: 20px;
	}
	#photoDown {
		padding-top:20px;
		padding-left: 500px;
	}
	#reviewTable {
		width:70%;
		padding-top:-90px;
	}
	#arrows {
		width: 30px;
		height: 30px;
	}
	label {
		font-weight: bold;
		font-size: 18px;
		text-align: left;
	}
	#search{margin-left: -12cm;}
	#clearID{}
	#key {margin-left:-10cm;}
	#key1 {margin-left: -11.25cm;}
	#key2 {margin-left: -6.5cm;}
	#key22 {font-weight: bold;}
	#table1 {
		padding-left: 150px;
		width: 90%;
	}
	#imagediv {
		display: none;
		padding-left: 400px;
		width: 500px;
	}
	#travelid {
		padding-top: 100px;
		padding-left: 350px;
	}
	#mode1,#mode2,#mode3 {
		padding: 10px 25px;
		text-decoration: none;
		border: none;
		background-color:#E8E8E8;
	}
	#reveal,#hideReview,#displayPhoto,#hidePhoto {
		margin-left: 100px;
		text-decoration: none;
		background-color:transparent;
		width: 50px;
		height: 50px;
		border:none;
	}
	#span1,#span2,#span3,#span4{padding-left: 50px;
		padding-top: -30px;}
	#radio2{
		margin-left: 5.30cm;
	}
	</style>

	<script type="text/javascript">
	var finaljson;
		xmlhttp=new XMLHttpRequest();
	
	function getCurrentLoc() {
		document.getElementById('locInput').disabled=true;
	var jsondoc;
	var urlForIp="http://ip-api.com/json";
	//use this in case ip-api.com fails to load 
	//var urlForIp="http://ip-api.co/json";
	xmlhttp.open('GET',urlForIp,false);
	xmlhttp.onload=function(){
					status=xmlhttp.status;
					if(status!=200)
					{
						var latitude="34.0266";
						var longitude="-118.2831";
					}
				};
	xmlhttp.send();
	jsondoc=xmlhttp.responseText;
	var obj=JSON.parse(jsondoc);
	//finaljson=JSON.stringify(obj);
	console.log(obj);
	var latitude=obj.lat||"34.0266";
	//corresponding values for ip-api.co json latitude and longitude
	//var latitude=obj.latitude;
	//var longitude=obj.longitude;
	var longitude=obj.lon||"-118.2831";
	console.log(latitude);
	console.log(longitude);
	var myform=document.getElementById("form1");
	document.getElementById("latitude").value=latitude;
	document.getElementById("longitude").value=longitude;
	document.getElementById("search").disabled=false;
	//myform.submit();
} 
function checkLocField() {
	if(document.getElementById("radio2").checked) {
		document.getElementById("search").disabled=false;
		document.getElementById("locInput").disabled=false;
		document.getElementById("locInput").required=true;
	}
}

</script> 
	<div id="travelid">
	<fieldset>
	<form method="POST" id="form1" name="travelform" value="sentForm" action="">
	<div class="formdiv" style="text-align: center">
	<h2> Travel and entertainment search</h2>
	<hr></hr>
	<label id="key">Keyword</label><input type="text" name="keyword" id="keyID" placeholder="keyword" required/><br/>
	<label id="key1">Category</label><select id="catID" name="Category">
	<option value="default" id="defaultid" selected>default</option>
	<option value="cafe">Cafe</option>
	<option value="bakery">Bakery</option>
	<option value="restaurant">Restaurant</option>
	<option value="beauty_salon">Beauty Salon</option>
	<option value="casino">Casino</option>
	<option value="movie_theater">Movie Theater</option>
	<option value="lodging">Lodging</option>
	<option value="airport">Airport</option>
	<option value="train_station">Train Station</option>
	<option value="subway_station">Subway Station</option>
	<option value="bus_station">Bus Station</option>
	</select><br/>
	<label id="key2">Distance(miles)</label><input type="text" id="distID" name="distmiles" placeholder="10"/><label id="key22">from </label>
	<input type="radio" id="radio1" name="radiofamily" value="current_loc" onclick="getCurrentLoc();">Here</input><br/>
	<input type="radio" id="radio2" name="radiofamily" value="location" onclick="checkLocField();"> <input type="text" id="locInput" name="location" placeholder="location" disabled/></input><br/>
	<input type="hidden" name="lat" id="latitude"/>
	<input type="hidden" name="long" id="longitude"/>
	<button type="submit" id="search" name="search" value="search">Search</button>
	<button type="button" value="clear" id="clearID" onclick="clearValue();">Clear</button>
</div>
</form>
</fieldset>
</div>
<div id="table1">
</div>
 <div id="maparea" style="width: 400px;height:400px;"></div>
 <div id="demo1"></div>
 <div id="reviewDown" style="display:none;">
 	<span id="span1">Click to display reviews<br/>
 		<button id= "reveal"><img  src="http://cs-server.usc.edu:45678/hw/hw6/images/arrow_down.png" width="20px" height="20px"/></button>
 	</span>
 </div>
 <div id="reviewUp" style="display: none;">
 	<span id="span2"> Click to close reviews<br/>
 		<button id="hideReview"><img  src="http://cs-server.usc.edu:45678/hw/hw6/images/arrow_up.png" width="20px" height="20px"/></button>
 	</span>
 </div>
<div id="demo"></div>
<div id="photoDown" style="display:none;">
 	<span id="span3">Click to display photos<br/>
 	<button id="displayPhoto"><img  src="http://cs-server.usc.edu:45678/hw/hw6/images/arrow_down.png" width="20px" height="20px"/></button>
 	</span>
 </div>
  <div id="photoUp" style="display:none;">
 	<span id="span4"> Click to close photos<br/>
 	<button id="hidePhoto"><img  src="http://cs-server.usc.edu:45678/hw/hw6/images/arrow_up.png" width="20px" height="20px"/></button>
 	</span>
 </div>
<div id="imagediv"></div>
<div id="javascript_content">
		<script type="text/javascript">
			//document.getElementById("form1").onload=function () {
			//	document.getElementById('distID').value="";
			//}
		 jsonPlaceid="";
		 duplicateVal="";
		 placeId=[];
		 nameArray=[];
		 addressArray=[];
		 latArray=[];
		 longArray=[];
		 opt=[];
		 retrieveLongitude="";
		 retrieveLatitude="";
		 keywordRetain="<?php echo $keyword; ?>";
		 distvalueRetain="<?php echo $distinmiles; ?>";
		 categoryRetain="<?php echo $select_category; ?>";
		 radioRetain="<?php echo $radioButton; ?>";
		 locationRetain="<?php echo $locationval; ?>";
		 formValue="<?php echo $travelForm; ?>";
		 opt=document.getElementById('catID').options;
		 //for (i=0;i<opt.length;i++)
		 //{
		 	//if(categoryRetain==opt[i].text)
		 		document.getElementById('catID').value=categoryRetain;
		 //}
		// document.getElementById('radio1').checked=true;
		 if(radioRetain == "current_loc") {
		 	document.getElementById('radio1').checked=true;
		 	//document.getElementById('search').disabled=false;
		 	document.getElementById('locInput').disabled=true;
		 	document.getElementById('search').disabled=false;
		 }
		 else if(radioRetain == "location")
		 {
		 	document.getElementById('radio2').checked=true;
		 	document.getElementById('locInput').disabled=false;
		 	document.getElementById('locInput').value=locationRetain;
		 	//document.getElementById('search').disabled=false;
		 }
		 else 
		 {
		 	//document.getElementById('radio1').checked=false;
		 	document.getElementById('radio2').checked=false;
		 }
		// radioRetain=<?php //if(isset($_POST['radio2' ?>;
		//document.getElementById("defaultid").selected=true;
		//document.getElementById("catID").value=categoryRetain;
		console.log(categoryRetain);
		document.getElementById("keyID").value=keywordRetain;
		var dist_flag="<?php echo $distflag; ?>";
		if(dist_flag==1)
		document.getElementById("distID").value=distvalueRetain;
		
		 function clearValue() {
		 	document.getElementById("distID").value="";
		 	document.getElementById("search").disabled=true;
		 	document.getElementById("keyID").value="";
		 	document.getElementById("radio1").checked=false;
		 	document.getElementById("radio2").checked=false;
		 	document.getElementById("table1").style.display="none";
		 	document.getElementById("demo1").style.display="none";
		 	document.getElementById("reviewDown").style.display="none";
		 	document.getElementById("reviewUp").style.display="none";
		 	document.getElementById("photoUp").style.display="none";
		 	document.getElementById("photoDown").style.display="none";
		 	document.getElementById("demo").style.display="none";
		 	document.getElementById("imagediv").style.display="none";

		 }

		function printTable() {		
		 var jsonString=<?php print json_encode($output); ?>;
		 console.log(jsonString.results);
		 if(jsonString.status == "INVALID_REQUEST" || jsonString.results.length == 0) 
		 {
		 	var x="<table id='displayResults1'>";
		 	x+="<tr><td style='text-align:center;text-weight:bold;'>No records has been found</td></tr>";
		 	document.getElementById("table1").innerHTML=x;
		 	return;
		 }
		 examplelength=jsonString.results.length;
		 var printContent="<table id='displayResults'>";
		 printContent+="<tr><th>Category</th><th>Name</th><th>Address</th></tr>";
		 for(i=0;i<examplelength;i++) {
		 	var addCount=i;
		 	var temp=JSON.stringify(jsonString);
			var temp2=JSON.stringify(jsonString.results[i]);
		 	temp3=JSON.parse(temp2);
		 	placeId[i]=temp3["place_id"];
		 	nameArray[i]=temp3["name"];
		 	addressArray[i]=temp3["vicinity"];
		 	var latArray=temp3["geometry"]["location"]["lat"];
		 	var longArray=temp3["geometry"]["location"]["lng"];
		 	var extractName=temp3["name"];
		 	var extractPlaceId=temp3["place_id"];
		 	 tempnamex=temp3["name"];
		 	printContent+='<tr><td><img id="arrows" src="'+temp3["icon"]+'"></td>';
		 	var tempName="<td onclick=getRowNum(this);>"+temp3["name"]+"</td>";
		 	printContent+=tempName;
		 	printContent+="<td id='"+addCount+ "'><span onclick='setForMapDisplay(this,"+addCount+","+latArray+","+longArray+","+examplelength+")'>"+temp3["vicinity"]+"</span><span id=\"map_"+addCount+"\" class='map_overlay' style='display:none;height:300px;width:400px;position:absolute;overflow:hidden;z-index:1'></span></td>";
		 	printContent+="</tr>"; 	
		 }
		

		 	printContent+="</table>";
		 	document.getElementById("table1").innerHTML=printContent;
			}
			printTable();
			function setForMapDisplay(index,locid,destLat,destLong,tableLength){
				
				var p=index.parentNode.rowIndex;
				var point=(p-1);
				var topPosition=index.getBoundingClientRect().top + window.pageYOffset + 20;
				var leftPosition=index.getBoundingClientRect().left ;
				//document.getElementById("map_"+locid).style.left=leftPosition+"px";
				//document.getElementById("map_"+locid).style.top=topPosition+"px";
				if(document.getElementById("map_"+locid).style.display == "none") {
				
                       	for(i=0;i<tableLength;i++) {

                       		document.getElementById("map_"+i).innerHTML="";
                       		document.getElementById("map_"+i).style.display = 'none';
                       	}

                  		retrieveLatitude=destLat;
                   		retrieveLongitude=destLong;
                  		 addMap(document.getElementById("map_"+locid));
                       document.getElementById("map_"+locid).style.display = "block";
                       //document.getElementById("floating-panel").style.display = "block";
                   } else
                   {
                       document.getElementById("map_"+locid).style.display = "none";
                      // document.getElementById("floating-panel").style.display = "none";
                   }
			}
			function getRowNum(e) {
				row=e.parentNode.rowIndex;
				col=e.cellIndex;
				m=row-1;
					retrieveName=nameArray[m];
					retrievePlace=placeId[m];
					var divx=document.getElementById("table1");
					divx.style.display="none";
					var displayArrows="<h4 text-align='center'>"+retrieveName+"</h4><br/>";
					document.getElementById("demo1").innerHTML=displayArrows;
					document.getElementById("reviewDown").style.display="block";
					displayPic(retrievePlace); //function to display the review table
					document.getElementById("photoDown").style.display="block";
					//displayPhotos();//function that displays the stored photos
					document.getElementById("displayPhoto").addEventListener('click',function() {
						imageRetrieve();
					});
					document.getElementById("hidePhoto").addEventListener('click',function() {
						imageHide();
					})
					document.getElementById("reveal").addEventListener('click',function() {
						displayTable();
					});
					document.getElementById("hideReview").addEventListener('click',function() {
						displayHide();
					})
				}
				function displayTable() {
					document.getElementById("imagediv").style.display="none";
					document.getElementById("photoDown").style.display="block";
					document.getElementById("photoUp").style.display="none";
					document.getElementById("reviewDown").style.display="none";
					document.getElementById("reviewUp").style.display="block";
					document.getElementById("demo").style.display="block";

				}
			function displayHide() {
				document.getElementById("demo").style.display="none";
				document.getElementById("reviewDown").style.display="block";
				document.getElementById("reviewUp").style.display="none";
			}
			function imageRetrieve() {
				displayPhotos();
				document.getElementById("demo").style.display="none";
				document.getElementById("reviewDown").style.display="block";
				document.getElementById("reviewUp").style.display="none";
				document.getElementById("photoDown").style.display="none";
				document.getElementById("photoUp").style.display="block";
				document.getElementById("imagediv").style.display="block";
				
			}
			function imageHide() {
				document.getElementById("photoUp").style.display="none";
				document.getElementById("imagediv").style.display="none";
				document.getElementById("photoDown").style.display="block";
				document.getElementById("reviewDown").style.display="block";
			}
			//function to display reviews in form of a table
			function displayPic(placeIdpass) {
			 var url="place.php?xyz="+placeIdpass;
			 var getJSON=function(url,callback){
			 	httpx=new XMLHttpRequest();
            	httpx.open("GET",url,true);
				httpx.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
				 httpx.setRequestHeader("Content-Type", "text/plain;charset=UTF-8");
				httpx.onload=function(){
					status=httpx.status;
					if(status==200)
					{
						callback(null,httpx.response);
					}
					else{
						callback(status,httpx.response);
					}
			};
			httpx.send();
			};
			getJSON(url,function(err,jsonobj){
					if(err!==null){
						alert("something is wrong");
					}
					else{

						var length;
						 jsonPlaceid=JSON.parse(jsonobj);
						console.log(jsonPlaceid);
						var printReview="";
						var noResults="";
						noResults+="<table id='reviewTable1' style='border='1 solid #D3D3D3'>";
							noResults+="<tr><td style='font-weight:bold;font-size: 14px;text-align:center;'>NO REVIEWS FOUND</td></tr>";
							noResults+="</table>";
						printReview+="<table id='reviewTable' border='1 solid #D3D3D3'>";
						
						var printNoPhoto="";
					if(typeof jsonPlaceid["result"]["photos"]!="undefined" && jsonPlaceid["result"]["photos"].length>0)	{
						var printPhoto="<table id='pics'>";
						var county=jsonPlaceid["result"]["photos"].length;
							if(county>5)
							{
							county=5;
							}
							else
							{
								county=jsonPlaceid["result"]["photos"].length;
								}
					for(i=0;i<county;i++) {
       				printPhoto+='<tr><td><img src="image'+i+'.jpg" onclick="window.open(this.src)" width="500px" height="350px"/></td></tr>';
       
       					}
       				printPhoto+="</table>";
        			document.getElementById("imagediv").innerHTML=printPhoto;
					}
					else
					{
					 printNoPhoto+="<table id='pics1'>";
    				printNoPhoto+="<tr><td 'style=text-align:center;'>NO PHOTOS FOUND</td></tr>";
    				printNoPhoto+="</table>";
    				document.getElementById("imagediv").innerHTML=printNoPhoto;	
					}
					var gggg="";

					if(typeof jsonPlaceid["result"]["reviews"]!="undefined" && jsonPlaceid["result"]["reviews"].length>0){

						if(jsonPlaceid["result"]["reviews"].length > 5)
						{
							length1=5;
						}
						else
						{
							length1=jsonPlaceid["result"]["reviews"].length;
						}
						for(i=0;i<length1;i++) {
							var jsonReview=jsonPlaceid["result"]["reviews"][i];
							if(!jsonReview) {break;}
							printReview+="<tr style='text-align:center;'>";
							printReview+='<td><img id="arrows" src="'+jsonReview["profile_photo_url"]+'">'+jsonReview["author_name"]+'</td></tr><br/>';
							printReview+='<tr><td>'+jsonReview["text"]+'<br/></td></tr><br/>';
						}
						printReview+="</table><br/>";
						document.getElementById("demo").innerHTML=printReview;
					}
					else {

							
							document.getElementById("demo").innerHTML=noResults;

					}
					}
			});
			
		}
		function toggletable(ele) {
          ele.style.display="none";
		}
		function displayPhotos() {
		
	}
		//displayPhotos();
	</script>  
	<script type="text/javascript">
		var map;
		var x,y;
		var lat_start;
		var lon_start;
		var lat1;
		var long1;
		var directionService;
		var directionsDisplay;
		
		function addMap(locid) {
				//start location or my location
				 lat_start="<?php echo $loc_lat; ?>";
				 lon_start="<?php echo $loc_long; ?>";
				lat1=parseFloat(retrieveLatitude);
			 	long1=parseFloat(retrieveLongitude);
				directionsService=new google.maps.DirectionsService;
				directionsDisplay=new google.maps.DirectionsRenderer;

			map= new google.maps.Map(locid, {
				center: {lat: lat1 ,lng: long1},
				zoom:12,
				disableDefaultUI:true
			});

			directionsDisplay.setMap(map);
			var marker = new google.maps.Marker({
				position: {lat: lat1,lng: long1},
				map: map
			});
			var divElement=document.createElement('div');
			divElement.id="floating-panel";
			divElement.style.display="none";
			var Button1=document.createElement('button');
			var textForButton=document.createTextNode('Walk there');
			Button1.appendChild(textForButton);
			Button1.id="mode1";
			Button1.value="WALKING";
			divElement.appendChild(Button1);
			//divElement.innerHTML="<br/>";
			var breakele=document.createElement('br');
			divElement.appendChild(breakele);
			var Button2=document.createElement('button');
			var textForButton2=document.createTextNode('Bike there');
			Button2.appendChild(textForButton2);
			Button2.id="mode2";
			Button2.value="BICYCLING";
			divElement.appendChild(Button2);
			var breakele2=document.createElement('br');
			divElement.appendChild(breakele2);
			var Button3=document.createElement('button');
			var textForButton3=document.createTextNode('Drive there');
			Button3.appendChild(textForButton3);
			Button3.id="mode3";
			Button3.value="DRIVING";
			divElement.appendChild(Button3);
			//document.getElementById("table1").appendChild(divElement);
			document.body.appendChild(divElement);
			window.onload=document.getElementById('floating-panel').style.visibility='hidden';
			//document.getElementById('floating-panel').style.display="none";
			var control=document.getElementById('floating-panel');
			control.style.display="block";
			map.controls[google.maps.ControlPosition.TOP_LEFT].push(control);
			
			document.getElementById('mode1').addEventListener('click',function() {
				marker.setMap(null);
				calculateAndDisplayRoute(directionsService,directionsDisplay);
			});
			document.getElementById('mode2').addEventListener('click',function() {
				marker.setMap(null);
				calculateAndDisplayRoute2(directionsService,directionsDisplay);
		});
			document.getElementById('mode3').addEventListener('click',function() {
				marker.setMap(null);
				calculateAndDisplayRoute3(directionsService,directionsDisplay);
			});
			
		}
		function calculateAndDisplayRoute(directionsService, directionsDisplay) {

			var selectedMode=document.getElementById('mode1').value;
			directionsService.route({
				origin:{lat:parseFloat(lat_start),lng:parseFloat(lon_start)},
				destination: {lat: lat1,lng : long1},
				travelMode: google.maps.TravelMode[selectedMode]
				},function(response,status) {
					if(status == 'OK') {
				directionsDisplay.setDirections(response);
			} else {
				window.alert('Directions request failed due to' + status);
			}
			});
		}
		function calculateAndDisplayRoute2(directionsService, directionsDisplay) {

			var selectedMode=document.getElementById('mode2').value;
			directionsService.route({
				origin:{lat:parseFloat(lat_start),lng:parseFloat(lon_start)},
				destination: {lat: lat1,lng : long1},
				travelMode: google.maps.TravelMode[selectedMode]
				},function(response,status) {
					if(status == 'OK') {
				directionsDisplay.setDirections(response);
			} else {
				window.alert('Directions request failed due to' + status);
			}
			});
		}
			function calculateAndDisplayRoute3(directionsService, directionsDisplay) {

			var selectedMode=document.getElementById('mode3').value;
			directionsService.route({
				origin:{lat:parseFloat(lat_start),lng:parseFloat(lon_start)},
				destination: {lat: lat1,lng : long1},
				travelMode: google.maps.TravelMode[selectedMode]
				},function(response,status) {
					if(status == 'OK') {
				directionsDisplay.setDirections(response);
			} else {
				window.alert('Directions request failed due to' + status);
			}
			});
		}
		</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAnXwonImQPfYwRW31rerYVr_pPywUuDdk">
</script>

		</div>
</body>
</html>
