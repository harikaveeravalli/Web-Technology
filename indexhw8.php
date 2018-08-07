<?php
global $keyword;
global $selected_Cat;
global $distance;
global $radioval;
global $location;
global $distflag;
$distflag=0;
$keyword=" ";
$distinmiles=" ";
$select_category="default";
$keyword=urlencode($_GET["keyword1"]);
$selected_Cat=urlencode($_GET["Category"]);
$distance=$_GET["distinmiles"];
$radioval=$_GET["radiofamily"];
GLOBAL $placename,$placeaddress,$city1,$state1,$country1;

if(isset($distance))
{
	$radius=1609.34*$distance;
	$distflag=1;
}
else
{
	$radius=16093.4;
}
if($_GET["radiofamily"] == "current_loc") {

$curr_lat=$_GET["lat"];
$curr_long=$_GET["long"];
$extract_values="https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=".$curr_lat.",".$curr_long."&radius=".$radius."&type=".$selected_Cat."&keyword=".$keyword."&key=AIzaSyA0sx7z0zbj5fxWu6zLOAdG6WmYLOPfVpI";
			$response_json=file_get_contents($extract_values);
			$output=json_decode($response_json,true);
			if($output['next_page_token']){
			sleep(3);
$nextPageExtract="https://maps.googleapis.com/maps/api/place/nearbysearch/json?pagetoken=".$output['next_page_token']."&key=AIzaSyA0sx7z0zbj5fxWu6zLOAdG6WmYLOPfVpI";
$responsePage2=file_get_contents($nextPageExtract);
$outputP2=json_decode($responsePage2,true);
}
if($outputP2['next_page_token']) {
sleep(3);
$thirdPage="https://maps.googleapis.com/maps/api/place/nearbysearch/json?pagetoken=".$outputP2['next_page_token']."&key=AIzaSyA0sx7z0zbj5fxWu6zLOAdG6WmYLOPfVpI";
$responsePage3=file_get_contents($thirdPage);
$outputP3=json_decode($responsePage3,true);	
}
}
if(isset($_GET["location"])) {
	//echo "hi";
	$location= urlencode($_GET["location"]);
	$obtainLoc="https://maps.googleapis.com/maps/api/geocode/json?address=".$location."&key=AIzaSyA0sx7z0zbj5fxWu6zLOAdG6WmYLOPfVpI";
			$temp=json_decode(file_get_contents($obtainLoc),true);
			//to extract the latitude and longitude from the decoded json in php
			$loc_lat= $temp['results'][0]['geometry']['location']['lat'];
			$loc_long=$temp['results'][0]['geometry']['location']['lng'];
			$start_Address=$temp['results'][0]['formatted_address'];
			//to get the corresponding table of places that matches the keyword,radius and location
			//echo "lat".$loc_lat;
			$extract_table_content="https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=".$loc_lat.",".$loc_long."&radius=".$radius."&type=".$selected_Cat."&keyword=".$keyword."&key=AIzaSyA0sx7z0zbj5fxWu6zLOAdG6WmYLOPfVpI";
			//echo $extract_table_content;
			$response_json=file_get_contents($extract_table_content);
			//var_dump($response_json);
			$output=json_decode($response_json,true);
			if($output['next_page_token']) {
				sleep(3);
		$nextPageExtract="https://maps.googleapis.com/maps/api/place/nearbysearch/json?pagetoken=".$output['next_page_token']."&key=AIzaSyA0sx7z0zbj5fxWu6zLOAdG6WmYLOPfVpI";
			$responsePage2=file_get_contents($nextPageExtract);
			$outputP2=json_decode($responsePage2,true);
			}
			if($outputP2['next_page_token']) {
			sleep(3);
			$thirdPage="https://maps.googleapis.com/maps/api/place/nearbysearch/json?pagetoken=".$outputP2['next_page_token']."&key=AIzaSyA0sx7z0zbj5fxWu6zLOAdG6WmYLOPfVpI";
			$responsePage3=file_get_contents($thirdPage);
			$outputP3=json_decode($responsePage3,true);	
		}

}
if(isset($_GET["name"]))
{
	$placename=urlencode($_GET["name"]);
	$placeaddress=urlencode($_GET["toaddress"]);
	$city1=urlencode($_GET["citya"]);
	$country1=urlencode($_GET["countrya"]);
	$state1=$_GET["statea"];
	//print_r("value".$placename);
$apikey="Y-mlcDV5Qmf5uebScTsS23hE0TkQ3O9qlc-jOKLCA8gqPQQ460BHK2CdasEPFb9T7pt5Xzce1d91ArJuR2cGhEFanI3zcpNg_35jURstBBGC-IFXlYbYsKOEsMbDWnYx";
//assert($API_KEY, "Please supply your API key.");
//header("Authorization" => "Bearer Y-mlcDV5Qmf5uebScTsS23hE0TkQ3O9qlc-jOKLCA8gqPQQ460BHK2CdasEPFb9T7pt5Xzce1d91ArJuR2cGhEFanI3zcpNg_35jURstBBGC-IFXlYbYsKOEsMbDWnYx")
$makeapicall="https://api.yelp.com/v3/businesses/matches/best?name=".$placename."&city=".$city1."&state=".$state1."&country=US&address1=".$placeaddress;
$ch=curl_init();
$headers = array();
$headers[]="authorization: Bearer Y-mlcDV5Qmf5uebScTsS23hE0TkQ3O9qlc-jOKLCA8gqPQQ460BHK2CdasEPFb9T7pt5Xzce1d91ArJuR2cGhEFanI3zcpNg_35jURstBBGC-IFXlYbYsKOEsMbDWnYx";
curl_setopt($ch,CURLOPT_URL,$makeapicall);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
//curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"GET");
$dummy=curl_exec($ch);
$output3=json_decode($dummy);
curl_close($ch);
if($output3->businesses) {
	$businessId=urlencode($output3->businesses[0]->id);
	$makereviewapicall="https://api.yelp.com/v3/businesses/".$businessId."/reviews";
	$ch2=curl_init();
	$headers1 = array();
	$headers1[]="authorization: Bearer Y-mlcDV5Qmf5uebScTsS23hE0TkQ3O9qlc-jOKLCA8gqPQQ460BHK2CdasEPFb9T7pt5Xzce1d91ArJuR2cGhEFanI3zcpNg_35jURstBBGC-IFXlYbYsKOEsMbDWnYx";
	curl_setopt($ch2,CURLOPT_URL,$makereviewapicall);
	curl_setopt($ch2,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch2,CURLOPT_HTTPHEADER,$headers1);
	$yelpresults=curl_exec($ch2);
	$yelpresults=json_decode($yelpresults);
echo (json_encode($yelpresults));
die();
}
else {
	$yelpresults="no reviews found";
	echo ($yelpresults);
	die();
}
}
include "hw8v1.html";
ob_end_clean();
?>
<!Doctype html>

<style type="text/css">
tr:hover {
background-color: #E8E8E8;
}

#pegman,#mapImg {
	width:10%;
	height:10%;
}
@media screen and (max-width: 300px) {
  #mapid{
   width:20%;
   height: 20%;
 }
#directionsDiv {
	
}
.fa-star {
	color: yellow;
}
.iframe-container{
    position: relative;
    width: 100%;
    padding-bottom: 56.25%; /* Ratio 16:9 ( 100%/16*9 = 56.25% ) */
}
</style>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<!--<link rel="stylesheet" href="https://cdnjs.cloudfare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">-->

<!--<script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js" integrity="sha384-SlE991lGASHoBfWbelyBPLsUlwY1GwNDJo3jSJO04KZ33K2bwfV9YBauFfnzvynJ" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.0/moment.min.js"></script>

<script type="text/javascript">

		keywordRetain="<?php echo $keyword; ?>";
		 distvalueRetain="<?php echo $distance; ?>";
		 categoryRetain="<?php echo $selected_Cat; ?>" || "default";
		 radioRetain="<?php echo $radioval; ?>";
		 locationRetain="<?php echo urldecode($location); ?>";
		 document.getElementById('keyID').value=keywordRetain;
		 document.getElementById('distID').value=distvalueRetain;
		 opt=document.getElementById('catID').options;
		document.getElementById('catID').value=categoryRetain;
		if(radioRetain == "current_loc") {
			document.getElementById('radio1').checked=true;
			document.getElementById('locInput').disabled=true;
		}else if(radioRetain == "location1") {
			document.getElementById('radio2').checked=true;
			document.getElementById('locInput').disabled=false;
			document.getElementById('locInput').value=locationRetain;
		}
		var dist_flag="<?php echo $distflag; ?>";
		if(dist_flag==1)
		document.getElementById("distID").value=distvalueRetain;
	count=0;
	favoriteList=[];
	var printContent,printContentP2,printContentP3;
function generateSecondpage(){
	//document.getElementById('forTable').style.display='none';
	flag2=2;
	placeidArray=[];
	var jsonvariableP3="";
	var jsonvariableP2=<?php print json_encode($outputP2) ?>;
	jsonvariableP3=<?php print json_encode($outputP3) ?>;
	tableLengthPage2=jsonvariableP2.results.length;
	if(jsonvariableP3)
	{
	tableLengthPage3=jsonvariableP3.results.length;
}
	console.log(jsonvariableP3);

 var num2=1;
 table2id=[];
 printContentP2="<button type='button' class='btn btn-default pull-right' id='detailsid2' disabled>Details<i class='fa fa-chevron-right' style='font-size:16px;'></i></button>";
 printContentP2+="<table class='table'>";

 printContentP2+="<thead><tr><th scope='col'>#</th><th scope='col'>Category</th><th scope='col'>Name</th><th scope='col'>Address</th><th scope='col'>Favorite</th><th scope='col'>Details</th></tr></thead><tbody>";
for(i=0;i<tableLengthPage2;i++) {
	var temp3=JSON.stringify(jsonvariableP2.results[i]);
	temp4=JSON.parse(temp3);
	var extractName=temp4["name"];
	var extractAddress=temp4["vicinity"];
	 placeidArray[i]=temp4["place_id"];
	  placeidtemp=temp4["place_id"];
	   table2id[i]="Tw"+num2;
	  favoriteList[i]=temp4;
	printContentP2+="<tr id='"+table2id[i]+"'><td>"+num2+"</td><td><img id='iconsize' src='"+temp4["icon"]+"'></td>";
	printContentP2+="<td>"+extractName+"</td>";
	printContentP2+="<td>"+extractAddress+"</td>";
	if(localStorage.getItem(placeidtemp) === null)
	{
	printContentP2+="<td><button class='btn btn-default' onclick='addTofavorites(favoriteList["+i+"],placeidArray["+i+"],this)'><i class='fa fa-star-o' style='font-size:24px;' id='favid'"+i+"></i></td><td><button  ng-click='hideIt()' class='btn btn-default' onclick='getDetails(placeidArray["+i+"],flag2,table2id["+i+"],favoriteList["+i+"])'><i class='fa fa-chevron-right' style='font-size:24px;' id='detailsid"+i+"' ></i></button></td></tr>";
}
else 
{
	printContentP2+="<td><button class='btn btn-default' onclick='addTofavorites(favoriteList["+i+"],placeidArray["+i+"],this)'><i class='fa fa-star' style='font-size:24px;color:#FFD700;' id='favid'"+i+"></i></td><td><button  ng-click='hideIt()' class='btn btn-default' onclick='getDetails(placeidArray["+i+"],flag2,table2id["+i+"],favoriteList["+i+"])'><i class='fa fa-chevron-right' style='font-size:24px;' id='detailsid"+i+"' ></i></button></td></tr>";
	
	}num2++;

}
printContentP2+="</tbody></table>";
printContentP2+=" <div class='container'><form class='form-horizontal' role='form'><div class='form-group'><div class='col-sm-offset-2 col-sm-8'> <button type='button' onclick='secondPrevious()' class='btn btn-default' id='previousid'>Previous</button>";
document.getElementById("forTable1").innerHTML=printContentP2;
if(jsonvariableP3)
{
 printContentP2=" <form class='form-horizontal' role='form'><div class='form-group'><div class='col-sm-offset-2 col-sm-6'> <button type='button' onclick='getNextPage2()' class='btn btn-default' id='next2id'>Next</button></div>";
document.getElementById("forTable1").innerHTML+=printContentP2;
document.getElementById("forTable1").style.display="none";
}
}
function generateThirdpage() {
	//document.getElementById('forTable1').style.display='none';
	//document.getElementById('forTable').style.display='none';
	//document.getElementById("forTable2").style.display='block';
	placeidArray2=[];
	table3id=[];
	flag3=3;
	jsonvariableP3=<?php print json_encode($outputP3) ?>;
	//tableLengthPage2=jsonvariableP2.results.length;
	if(jsonvariableP3)
	{
	tableLengthPage3=jsonvariableP3.results.length;
}
var num3=1;

printContentP3="<button type='button' class='btn btn-default pull-right' id='detailsid3' disabled>Details<i class='fa fa-chevron-right' style='font-size:16px;'></i></button>";
printContentP3+="<table class='table'>";

 printContentP3+="<thead><tr><th scope='col'>#</th><th scope='col'>Category</th><th scope='col'>Name</th><th scope='col'>Address</th><th scope='col'>Favorite</th><th scope='col'>Details</th></tr></thead><tbody>";
for(i=0;i<tableLengthPage3;i++) {
	var temp3=JSON.stringify(jsonvariableP3.results[i]);
	temp4=JSON.parse(temp3);
	var extractName=temp4["name"];
	var extractAddress=temp4["vicinity"];
	 placeidArray2[i]=temp4["place_id"];
	  var placeidtemp=temp4["place_id"];
	  table3id[i]="Te"+num3;
	printContentP3+="<tr id='"+table3id[i]+"'><td>"+num3+"</td><td><img id='iconsize' src='"+temp4["icon"]+"'></td>";
	printContentP3+="<td>"+extractName+"</td>";
	printContentP3+="<td>"+extractAddress+"</td>";
	favoriteList[i]=temp4;
	//printContentP2+="<td><label class='custom-checkbox'><input type='checkbox' id='favid'"+i+"/><i class='glyphicon glyphicon-star-empty'></i><i class='glyphicon glyphicon-star'></i></label></td><td></td></tr>";
	if(localStorage.getItem(placeidtemp) == null) {
		
	printContentP3+="<td><button class='btn btn-default' onclick='addTofavorites(favoriteList["+i+"],placeidArray2["+i+"],this)'><i class='fa fa-star-o' style='font-size:24px;' id='favid'"+i+"></i></button></td><td><button ng-click='hideIt()' class='btn btn-default' onclick='getDetails(placeidArray2["+i+"],flag3,table3id["+i+"],favoriteList["+i+"])'><i class='fa fa-chevron-right' style='font-size:24px;' id='detailsid"+i+"' ></i></button></td></tr>";
}
else 
{
	printContentP3+="<td><button class='btn btn-default' onclick='addTofavorites(favoriteList["+i+"],placeidArray2["+i+"],this)'><i class='fa fa-star' style='font-size:24px;color:#FFD700;' id='favid'"+i+"></i></button></td><td><button ng-click='hideIt()' class='btn btn-default' onclick='getDetails(placeidArray2["+i+"],flag3,table3id["+i+"],favoriteList["+i+"])'><i class='fa fa-chevron-right' style='font-size:24px;' id='detailsid"+i+"' ></i></button></td></tr>";
	
}
num3++;
}
console.log(table3id);
printContentP3+="</tbody></table>";
printContentP3+=" <div class='container'><form class='form-horizontal' role='form'><div class='form-group'><div class='col-sm-offset-2 col-sm-8'> <button type='button' onclick='thirdPrevious()' class='btn btn-default' id='previous2id'>Previous</button></div>";
document.getElementById("forTable2").innerHTML=printContentP3;
document.getElementById("forTable2").style.display='none';

}
function secondPrevious() {
	if($("#forTable").hasClass("forTableClass"))
	{
		$("#forTable").removeClass("forTableClass");
	}
	document.getElementById('forTable2').style.display='none';
	document.getElementById('forTable1').style.display='none';
	document.getElementById('forTable').style.display='block';
}
function thirdPrevious() {
	if($("#forTable1").hasClass("forTableClass"))
	{
		$("#forTable1").removeClass("forTableClass");
	}
	document.getElementById('forTable1').style.display='block';
	document.getElementById('forTable2').style.display='none';
	document.getElementById('forTable').style.display='none';
}

function printtable() {
	placeArray=[];
	table1id=[];
	flag=1;
	var num=1;
	var jsonvariableP2="";
	var jsonvariable="";	
 jsonvariable=<?php print json_encode($output) ?>;
 jsonvariableP2=<?php print json_encode($outputP2) ?>;

//console.log(jsonvariable);
console.log(jsonvariableP2);
if(jsonvariable){
tableLength=jsonvariable.results.length;
}

if(jsonvariableP2){
tableLengthPage2=jsonvariableP2.results.length;
}
if(jsonvariable.status == "INVALID_REQUEST" || jsonvariable.results.length == 0) {
printContent="<div class='panel panel-warning'><div class='panel-heading'>No Records</div></div>";
}
else {

printContent+="<button type='button' class='btn btn-default pull-right' id='detailsid' disabled>Details<i class='fa fa-chevron-right' style='font-size:16px;'></i></button>";
 printContent+="<table class='table'>";
printContent+="<thead><tr><th scope='col'>#</th><th scope='col'>Category</th><th scope='col'>Name</th><th scope='col'>Address</th><th scope='col'>Favorite</th><th scope='col'>Details</th></tr></thead><tbody>";
for(i=0;i<tableLength;i++) {
	var temp1=JSON.stringify(jsonvariable.results[i]);
	temp2=JSON.parse(temp1);
	var extractName=temp2["name"];
	var extractAddress=temp2["vicinity"];
	placeArray[i]=temp2["place_id"];
	//console.log(placeArray[i]);
	placeidd=temp2["place_id"];
	 table1id[i]="T"+num;
	 favoriteList[i]=temp2;
	printContent+="<tr id='"+table1id[i]+"'><td>"+num+"</td><td><img id='iconsize' src='"+temp2["icon"]+"'></td>";
	printContent+="<td>"+extractName+"</td>";
	printContent+="<td>"+extractAddress+"</td>";
	if(localStorage.getItem(placeidd) === null){
		console.log("ele" + placeidd);
	printContent+="<td><button class='btn btn-default' onclick='addTofavorites(favoriteList["+i+"],placeArray["+i+"],this)'><i class='fa fa-star-o' style='font-size:24px;' id='favid'"+i+"'></i></button></td><td><button type='button' onclick='getDetails(placeArray["+i+"],flag,table1id["+i+"],favoriteList["+i+"])' ng-click='hideIt()' class='btn btn-default'><i class='fa fa-chevron-right' style='font-size:24px;' id='detailidp1"+i+"' ></i></button></td></tr>";
}
else{
	printContent+="<td><button class='btn btn-default' onclick='addTofavorites(favoriteList["+i+"],placeArray["+i+"],this)'><i class='fa fa-star' style='font-size:24px;color:#FFD700;' id='favid'"+i+"'></i></button></td><td><button type='button' onclick='getDetails(placeArray["+i+"],flag,table1id["+i+"],favoriteList["+i+"])' ng-click='hideIt()' class='btn btn-default'><i class='fa fa-chevron-right' style='font-size:24px;' id='detailidp1"+i+"' ></i></button></td></tr>";
	}
	num++;

}
printContent+="</tbody></table>";
}
//console.log(table1id);
document.getElementById('progress-id').style.display='none';
document.getElementById("forTable").innerHTML=printContent;

var printContentP2;
if(jsonvariableP2)
{
 printContent=" <div class='container'><form class='form-horizontal' role='form'><div class='form-group'><div class='col-sm-offset-2 col-sm-8'> <button type='button' onclick='getNextPage1()' class='btn btn-default' id='nextid'>Next</button></div>";
document.getElementById("forTable").innerHTML+=printContent;
}

}

var jsonvariable=<?php print json_encode($output) ?>;
 var jsonvariableP12=<?php print json_encode($outputP2) ?>;
 var jsonvariableP13=<?php print json_encode($outputP3) ?>;
 if(jsonvariable)
 {
tableLength=jsonvariable.results.length;
if(tableLength){
printtable();}
}
if(jsonvariableP12){
tableLengthPage2=jsonvariableP12.results.length;
if(tableLengthPage2){
generateSecondpage();}
}
	//tableLengthPage2=jsonvariableP2.results.length;
	if(jsonvariableP13)
	{
	tableLengthPage3=jsonvariableP13.results.length;
	if(tableLengthPage3){
	generateThirdpage();}
}
function getNextPage1() {
	if($("#forTable1").hasClass("forTableClass"))
	{
		$("#forTable1").removeClass("forTableClass");
	}
	document.getElementById('forTable').style.display='none';
	document.getElementById('forTable1').style.display='block';
	document.getElementById('forTable2').style.display='none';
}
function getNextPage2() {
	if($("#forTable2").hasClass("forTableClass"))
	{
		$("#forTable2").removeClass("forTableClass");
	}
	document.getElementById('forTable').style.display='none';
	document.getElementById('forTable1').style.display='none';
	document.getElementById('forTable2').style.display='block';
}
favTable=[];
function addTofavorites(obj,placeid,curr) {
console.log(placeid);
console.log(obj);
//localStorage.clear();
//console.log(curr);
if($(curr).find($(".fa")).hasClass('fa-star-o'))
{
console.log("in");
$(curr).find($(".fa")).css({"color":"#FFD700"}).removeClass("fa-star-o").addClass("fa-star");
//favTable[placeid]= favoriteList[obj];
localStorage.setItem(placeid,(JSON.stringify(obj)));
var value1=localStorage.getItem(placeid);
console.log(localStorage);
console.log(value1);
}
else {
	console.log("remove from favorites");

$(curr).find($(".fa")).css({"color":"black"}).removeClass('fa-star').addClass('fa-star-o');
if(placeid in localStorage ){
localStorage.removeItem(placeid);
}
console.log(localStorage);
}
}
function displayFavorites() {
var favoritesprint;
var flag=4;
displaytemp=""; favoritesArray=[];placeArray1=[];table4id=[];
if(localStorage.length == 0) {
var printContent="<div class='panel panel-warning'><div class='panel-heading'>No Records</div></div>";
}
else{
var printContent="<table class='table'>";
printContent+="<thead><tr><th scope='col'>#</th><th scope='col'>Category</th><th scope='col'>Name</th><th scope='col'>Address</th><th scope='col'>Favorite</th><th scope='col'>Details</th></tr></thead><tbody>";
var x=localStorage.length;
var iterateLength;
if(x>20) {
iterateLength=20;
}
else
{
	iterateLength=x;
}
for(i=0;i<iterateLength;i++) {
	var num=i+1;

	console.log(localStorage.key(i));
		var key1=(localStorage.getItem(localStorage.key(i)));
		var printData=JSON.parse(key1);
		var name=printData["name"];
		var vicinity=printData["vicinity"];
		//placeArray1[i]=printData["place_id"];
		console.log(placeArray1);
		favoritesArray[i]=key1;
		table4id[i]="table4"+i;
		displaytemp+=name+":"+vicinity;
		printContent+="<tr id='"+table4id[i]+"'><td>"+num+"</td><td><img id='iconsize' src='"+printData["icon"]+"'></td>";
		printContent+="<td>"+name+"</td>";
		printContent+="<td>"+vicinity+"</td>";
		printContent+="<td><button class='btn btn-default' onclick='deletefavorites(localStorage.key("+i+"),this)'><i class='fa fa-trash' id='favid'"+i+"'></i></button></td><td><button type='button' onclick='getDetails(placeArray1["+i+"],flag,table4id[i])' class='btn btn-default'><i class='fa fa-chevron-right' id='detailidp1"+i+"' ></i></button></td></tr>";
	num++;
	}

	printContent+="</tbody></table>";
	if(x>20) {
	printContent+="<button class='btn btn-default' onclick='displayFav()'>next</button>";
	}
}	document.getElementById('forTable').style.display='none';
	document.getElementById('forTable1').style.display='none';
	document.getElementById('forTable2').style.display='none';
	document.getElementById('forTabs').style.display='none';
	document.getElementById('forTableNext').style.display='block';
	document.getElementById('forTableNext').innerHTML=printContent;
}
if(localStorage.length > 20) {
	displayFavSecond();
}
function deletefavorites(placeidx,obj) {
	console.log(placeidx);
		localStorage.removeItem(placeidx);
	$(obj).parent().remove();
	displayFavorites();
	console.log(localStorage);
}
function displayFavSecond() {

}
function displayFav() {

}


function printTableAgain(){
	document.getElementById('forTable').style.display='block';
	document.getElementById('forTableNext').style.display='none';
}
var start_lat=<?php echo json_encode($curr_lat) ?>;
var start_long=<?php echo json_encode($curr_long) ?>;

	var directionsService=new google.maps.DirectionsService;
	var directionsDisplay=new google.maps.DirectionsRenderer;
function getMap() {
	var fromAddress=<?php print json_encode($formatted_address) ?>;
	var formForMap="<form><div class='form-row'><div class='col'><label for='fromaddress'>From</label></div><div class='col'><label for='toaddress'>To</label></div><div class='col'><label for='travelmode'>Travel Mode</label></div><div class='col'><label class='sr-only'>button</label></div></div>";
	formForMap+="<div class='form-row'><div class='col'><input type='text' class='form-control' id='fromaddress' placeholder='My Location' value='current_location' name='fromaddress' onclick='enableAutoComplete()'></div><div class='col'><input type='text' class='form-control' id='toaddress' placeholder='Destination Address' name='toaddress' value='' readonly='readonly'></div><div class='col'><select class='form-control' id='mode'><option value='DRIVING'>Driving</option><option value='BICYCLING'>Bicycling</option><option value='TRANSIT'>Transit</option><option value='WALKING'>Walking</option></select></div><div class='col'><input class='btn btn-primary' type='button' value='Get Directions' id='getDirections' onclick='calculateAndDisplayRoute()'></div></div><br/><div id='pegmap'><img id='pegman' onclick='toggleStreetView()' src='http://cs-server.usc.edu:45678/hw/hw8/images/Pegman.png'/></form><div class='iframe-container' id='mapid'></div></div><p id='directionsDiv'></p>";
	document.getElementById('mapDiv').innerHTML=formForMap;

	addMap();

}
 var map;
 var marker;
	 var origin1;
	 var destination1;
	 var toAddress;
	 var destName;
	 var geocoder;
	 var orLat,orLng;
	 var panorama;
function enableAutoComplete() {
  var input=document.getElementById('fromaddress');
  autocomplete=new google.maps.places.Autocomplete(input);
}
  
function addMap() {
	
	var origin;
	var latitude,longitude;
	var destinationx;
	 	document.getElementById('toaddress').value=toAddress;
    var tempxx=document.getElementById('locInput').value;
	 	//alert(document.getElementById('fromaddress').value);
	 	if(document.getElementById('fromaddress').value == 'current_location' || document.getElementById('fromaddress').value == 'My location')
	{
	    origin1={lat: parseFloat(start_lat), lng:parseFloat(start_long)};
	} 
	else if(document.getElementById('locInput')!="") {
    origin1=tempxx;
  }
  else
	{
		origin1=document.getElementById('fromaddress').value;

	}
	 	var map=new google.maps.Map(document.getElementById('mapid'), {
		center: origin1,
		zoom: 7
		//disableDefaultUI:true
	});
	directionsDisplay.setMap(map);

	 	//alert("to addresss.." + document.getElementById('toaddress').value)
	 	geocoder=new google.maps.Geocoder();
	 	geocoder.geocode( {'address':toAddress}, function(results,status){
	 		if(status == google.maps.GeocoderStatus.OK) {
	 			latitude=results[0].geometry.location.lat();
	 			longitude=results[0].geometry.location.lng();
	 			destination1={lat:latitude,lng:longitude};
	 			 marker = new google.maps.Marker({
				position: destination1,
				map: map
				});
	 			 marker.setMap(map);
	 			panorama=map.getStreetView();
				panorama.setPosition(destination1);
				panorama.setPov(/** @type {google.maps.StreetViewPov} */({
          		heading: 34,
          		pitch: 10
       			 }));
	 			//alert(latitude+":"+longitude);
	 		}
	 	});
	 	
}
function toggleStreetView() {
	var toggle=panorama.getVisible();
	if(toggle == false) {
		panorama.setVisible(true);
		//var mapDisplay="<img id='mapImg' src='http://cs-server.usc.edu:45678/hw/hw8/images/Map.png'/>";
		document.getElementById('pegman').src='http://cs-server.usc.edu:45678/hw/hw8/images/Map.png';
		//document.getElementById('pegman').style.display='none';
		//document.getElementById('mapsign').style.display='block';
	}else {

		

		//var mapDisplay="<img id='pegman' src= 'http://cs-server.usc.edu:45678/hw/hw8/images/Pegman.png'/>";
		document.getElementById('pegman').src='http://cs-server.usc.edu:45678/hw/hw8/images/Pegman.png';
			panorama.setVisible(false);
	}
}
function calculateAndDisplayRoute() {
	if(document.getElementById('fromaddress').value == 'current_location' || document.getElementById('fromaddress').value == 'My location')
	{
	    origin1={lat: parseFloat(start_lat), lng:parseFloat(start_long)};
	}
	else 
	{
		origin1=document.getElementById('fromaddress').value;


	}var selectedMode= document.getElementById('mode').value;
geocoder=new google.maps.Geocoder();
geocoder.geocode({'address':toAddress}, function(results,status) {
	if(status == google.maps.GeocoderStatus.OK) {
		latitude=results[0].geometry.location.lat();
		longitude=results[0].geometry.location.lng();
		destination1={lat:latitude,lng:longitude};
		directionsService.route({
	origin: origin1,
	destination:destination1,
	provideRouteAlternatives: true,
	travelMode:google.maps.TravelMode[selectedMode]
},function(response,status) {
	if(status == 'OK') {
		directionsDisplay.setDirections(response);
		directionsDisplay.setPanel(document.getElementById('directionsDiv'));
	}
	else {
		window.alert('Directions request failed due to' + status);
	}
});

	}
});
}
var flag=1;
var printReview;
function pageTabs(printPlacetable,printPhotos,printReview,printReviewHead,address,sendPlace,flag,highlightnum,rating1,placevariable,favList,google1,website1) {
	document.getElementById("forTable").style.display='none';
	document.getElementById("forTable1").style.display='none';
	document.getElementById("forTable2").style.display='none';
	$('#forTabs').addClass('slideRight');
	document.getElementById('forTabs').style.display='block';
	toAddress = address;
	destName = sendPlace;
	favsend=favList;
	googlepg=google1;
	websitepg=website1;
	 placevariable1=placevariable;
	console.log("placevariable"+ placevariable1);
	//console.log("favlist"+ favList);
	console.log("high"+highlightnum);
	$('#myTabs').addClass('forTabsClass');
	var buttonline="<div class='container'><button type='button' class='btn btn-default btn-lg' id='backtolist'><span class='glyphicon glyphicon-chevron-left'></span>List</button><h3>"+destName+"</h3><div class='float-right'><button class='btn btn-default' onclick='addTofavorites(favsend,placevariable1,this)'><i class='fa fa-star-o' style='font-size:24px;'></i></button><button class='btn btn-default' onclick='tweetMessage(destName,toAddress,googlepg,websitepg)'><img src='http://cs-server.usc.edu:45678/hw/hw8/images/Twitter.png' style='width:25px;height:25px;'/></button></div></div>";
var tabString="<ul class='nav nav-tabs justify-content-end' role='tablist'><li class='nav-item'><a href='#tempdiv' class='nav-link active' role='tab' data-toggle='tab'>Info</a></li><li class='nav-item'><a href='#divForImages' class='nav-link' role='tab' data-toggle='tab'>Photos</a></li><li class='nav-item'><a href='#mapDiv' class='nav-link' role='tab' data-toggle='tab'>Maps</a></li><li class='nav-item'><a href='#divForReviews' class='nav-link' role='tab' data-toggle='tab'>Reviews</a></li></ul>";
	tabString+="<div class='tab-content'><div role='tabpanel' class='tab-pane fade in active' id='tempdiv'></div><div role='tabpanel' class='tab-pane fade' id='divForImages'></div><div role='tabpanel' class='tab-pane fade' id='mapDiv'></div><div role='tabpanel' class='tab-pane fade' id='divForReviews'><div id='selectOptions'></div><div><div id='yelppart'></div><div id='reviewsDiv'></div></div></div>";
	document.getElementById("forTabs").innerHTML=buttonline;
	 document.getElementById("forTabs").innerHTML+=tabString;
	 document.getElementById('selectOptions').innerHTML=printReviewHead;

	 document.getElementById('reviewsDiv').innerHTML=printReview;
	 //document.getElementById('yelppart').innerHTML="";
	 console.log(printYelpReview);
	 if(document.getElementById('reviewType').value == 'googleReviews')
	 {
	 	document.getElementById('reviewsDiv').innerHTML=printReview;
	 }
	
	document.getElementById('tempdiv').innerHTML=printPlacetable;
	//dom is ready 
	$('#rateYoid').rateYo({
		rating:(rating1/Math.ceil(rating1))*100 +"%",
		numStars:Math.ceil(rating1),
		normalFill:"transparent",
		readOnly:true
	});
	document.getElementById('divForImages').innerHTML=printPhotos;
	
//document.getElementById('yelppart').innerHTML="";

document.getElementById('reviewsDiv').innerHTML=printReview;

//document.getElementById('highlightnum').style.backgroundColor='#EFB360';
document.getElementById('backtolist').addEventListener('click',function() {
backtolist(flag,highlightnum,placevariable1,favsend);

});
 	getMap();
 	//makeAjaxRequest(toAddress,destName);
}

$("#google1").on("click", function() {
  //alert($(this).val());

  document.getElementById("reviewsDiv").innerHTML = printReview;

});

function tweetMessage(name,address,google1,website1) {
	if(website1){
	window.open("https://twitter.com/intent/tweet?text="+encodeURIComponent("check out " +name + " located at"+address+".website1:")+ "&url="+encodeURIComponent(website1)+"&hashtags=TravelAndEntertainmentSearch","_blank","height=400,width=400");
}
else {
	window.open("https://twitter.com/intent/tweet?text="+encodeURIComponent("check out " +name + " located at"+address+".website1:")+ "&url="+encodeURIComponent(google1)+"&hashtags=TravelAndEntertainmentSearch","_blank","height=400,width=400");
}

}

 function backtolist(flag,highlightnum,placevariable1,favsend) {
if(flag==1) {
	//alert(highlightnum);
	document.getElementById('forTable').style.display='block';
	//document.getElementById('forTable').style.visibility='hidden';
	$('#forTable').addClass("forTableClass");
	document.getElementById('forTabs').style.display='none';

	id = "#" + highlightnum;
	document.getElementById(highlightnum).style.backgroundColor="#FFD700";
	document.getElementById(highlightnum).onmouseover=function(){

		this.style.backgroundColor = "#FFD700";
	}
	//document.getElementById(highlightnum).o
	
	document.getElementById(highlightnum).onmouseout=function(){
		this.style.backgroundColor="white";
	}
document.getElementById('detailsid').addEventListener('click',function(){
getDetails(placevariable1,flag,highlightnum,favsend);
});

 }

 else if(flag==2) {
 	
 	document.getElementById('forTable1').style.display="block";
 	$('#forTable1').addClass("forTableClass");
 	document.getElementById('forTabs').style.display='none';
 	id = "#" + highlightnum;
	document.getElementById(highlightnum).style.backgroundColor="#FFD700";
	document.getElementById(highlightnum).onmouseover=function(){

		this.style.backgroundColor = "#FFD700";
	}
	//document.getElementById(highlightnum).o
	
	document.getElementById(highlightnum).onmouseout=function(){
		this.style.backgroundColor="white";
	}
 	document.getElementById('detailsid2').addEventListener('click',function(){
	getDetails(placevariable1,flag,highlightnum,favsend);
});
 }
 else if(flag==3) {
 	document.getElementById('forTable2').style.display="block";
 	id = "#" + highlightnum;
 	$('#forTable2').addClass("forTableClass");
	document.getElementById(highlightnum).style.backgroundColor="#FFD700";
	document.getElementById(highlightnum).onmouseover=function(){

		this.style.backgroundColor = "#FFD700";
	}
	//document.getElementById(highlightnum).o
	
	document.getElementById(highlightnum).onmouseout=function(){
		this.style.backgroundColor="white";
	}
document.getElementById('detailsid3').addEventListener('click',function(){
	getDetails(placevariable1,flag,highlightnum,favsend);
});
}

}
	var latitude,longitude;
	function getCurrentLoc() {
		document.getElementById('locInput').disabled=true;
	var jsondoc;
	var urlForIp="http://ip-api.com/json";
	$.ajax({
   url : "http://ip-api.com/json",
   success : function(result){
	latitude=result.lat;
	longitude=result.lon;
	console.log(latitude);
	console.log(longitude);
	var myform=document.getElementById("form1");
	document.getElementById("latitude").value=latitude;
	document.getElementById("longitude").value=longitude;
//console.log(lat,lon);
   },
   error : function (reason, xhr){
    console.log("error in processing your request", reason);
   }
});
} 
function getPlaceDetails() {

alert("yes");

}

addressCount=0;
var tosend;
var reviews1="";
var datayelp;

function getDetails(placevariable,flag,highlightnum,favList) {
 tosend=placevariable;
 var favitem=favList;
 document.getElementById('detailsid').disabled=false;
  document.getElementById('detailsid2').disabled=false;
   document.getElementById('detailsid3').disabled=false;
	var map=new google.maps.Map(document.getElementById('map'), {
	center: {lat: latitude, lng: longitude},
	zoom:12
});
var request={placeId: placevariable};
service = new google.maps.places.PlacesService(map);
service.getDetails({
	placeId : placevariable
},
	function(place,status){
		//alert(place);
		var photos1="";
	
	if(status == google.maps.places.PlacesServiceStatus.OK) {
	 address=place.formatted_address;
	 sendPlace=place.name;
	 document.getElementById("addresstoPhp").value = address;
	 phonenumber=place.international_phone_number;
	 var x=place.price_level;
	 	
	 if(x==1) pricelevel="$";
	if(x==2) pricelevel="$$";
	if(x==3) pricelevel="$$$";
	if(x==4) pricelevel="$$$$";
	if(x==5) pricelevel="$$$$$";

	 var rating1=place.rating;
	 googlepage=place.url;
	 website=place.website;
	  photos1=place.photos;//To display photos of the place
	  reviews1=place.reviews;
	  var openclose;
	  var openhours=new Object;
	  var sortedhours=new Object;
	  var order=[];
	   var opentime;
	  weekday=[];
	  str=[];
	  //reviews1 is an array storing reviews of the place
	 if(place.opening_hours.open_now == true)
	 {
	 	openclose="Open now: ";
	 
	 if(place.opening_hours.weekday_text)
	 {
	 	var utcoffset=place.utc_offset;
	 	console.log(utcoffset);
	 	if(utcoffset)
	 	{
	 var localtime=moment().utcOffset(utcoffset).format("dddd");
	//var localtime="Thursday";
	 console.log(localtime);
	 		 str=place.opening_hours.weekday_text;
	 		//console.log(str);
	 		for(var f=0;f<str.length;f++) {
	 			openhours[str[f].substr(0,str[f].indexOf(':'))]=str[f].substr(str[f].indexOf(':')+1);
	 			order[f]=str[f].substr(0,str[f].indexOf(':'));
	 			}
	 		}
	 		for(var key in openhours) {
	 			if(localtime == key){
	 				console.log(openhours[key]);
	 				openclose+=" "+openhours[key];
	 			}
	 		}
	 		console.log(openhours);

	 		for(var key in openhours) {
	 			if(localtime == key){
	 				break;
	 			}
	 			else 
	 			{
	 				sortedhours[key]=openhours[key];
	 				delete(openhours[key]);
	 			}

	 		}
	 		sortedhours=Object.assign(openhours,sortedhours);

	 		
	 		console.log(sortedhours);
	 		openclose+="<div class='container' data-toggle='modal' data-target='#myModal'><a href='#myModal'>Daily open hours</a></div>";
	 		openclose+="<div class='modal fade' id='myModal'><div class='modal-dialog'><div class='modal-content'>";
	 		openclose+="<div class='modal-header'><h4 class='modal-title'>Open Hours</h4> <button type='button' class='close' data-dismiss='modal'>&times;</button></div><div class='modal-body'><table class='table unique'><tbody>";
	 		for(var key in openhours) {
	 			if(localtime == key){
	 				openclose+="<tr><td><b>"+key+"</b></td><td><b>"+openhours[key]+"</b></td></tr>";
	 			}
	 			else {
	 				openclose+="<tr><td>"+key+"</td><td>"+openhours[key]+"</td></tr>";
	 			}
	 			
	 		}
	 	}
	}
	 else
	 {
	 	openclose="Closed now";
	 }
	 if(place.reviews){
	 var reviewsArrLength=reviews1.length;
	}
	 console.log(place.reviews);
	 console.log(place.photos);
	 if(place.photos){
	 var photoArrLength=photos1.length;
	}
	printPhotos="";
	printPhotosLine2="";
	printPhotosLine3="";
	 if(place.photos) {
	 	console.log(place.photos);
	for(var i=0;i<photoArrLength;i++) {
	 var imageI=photos1[i].getUrl({'maxWidth':photos1[i].width,'maxHeight':photos1[i].height});
		 printPhotos+="<img src='"+imageI+"' onclick='window.open(this.src)'  class='img-responsive img-thumbnail'/>"; 
	}
}
else
{
	printPhotos+= "<div class='panel panel-warning'><div class='panel-heading'>No Records</div></div>";
}
	printReview="";
	printYelpReview="";
	var printReviewHead;
	var yelpJSON="";
	printReviewHead="<select class='selectpicker' data-style='btn-primary' id='reviewType'><option value='googleReviews' id='google1' onclick='sendGoogleData()'>Google Reviews</option><option value='yelpReviews' onclick='sendYelpData(yelpJSON)'>Yelp Reviews</option></select>";
	printReviewHead+="<select class='selectpicker data-style='btn-primary' onchange='changeReviewOrder(reviews1,yelpJSON)' id='sortReviews' data-width='fit'><option value='Default Order' selected>Default Order</option><option value='Highest Rating'>Highest Rating</option><option value='Lowest Rating'>Lowest Rating</option><option value='Most Recent'>Most Recent</option><option value='Least Recent'>Least Recent</option></select><br/>";
	if(place.reviews) {
		for(var j=0;j<reviewsArrLength;j++) {

			var profilePhoto=reviews1[j].profile_photo_url;
			var authorName=reviews1[j].author_name;
			var authorURL=reviews1[j].author_url;
			var rating=reviews1[j].rating;
			var reviewText=reviews1[j].text;
			var timesec=reviews1[j].time*1000;
			var time=new Date(timesec);
			printReview+="<a href='"+authorURL+" target='_blank'><img src='"+profilePhoto+"' class='img-circle' width='70px' height='70px' style='float:left'/></a>";
			printReview+="<a href='"+authorURL+" target='_blank'>"+authorName+"</a><br/>";
			for(var stars=1;stars<=rating;stars++) {
				printReview+="<span class='fa fa-star checked'></span>";
			}
			printReview+=time+"<br/><p>"+reviewText+"</p><br/>";
		}

	 }
	 else
	 {
	 	printReview+="<div class='panel panel-warning'><div class='panel-heading'>No Records</div></div>";

	 }
 //to display maps 
	 Hours=0;
	printPlacetable="<table class='table table-striped'>";
	printPlacetable+="<tr><th>Address</th><td>"+address+"</td></tr>";
	if(phonenumber!="" || phonenumber!=null)
	{
	printPlacetable+="<tr><th>Phone Number</th><td>"+phonenumber+"</td></tr>";
}if(x){
	printPlacetable+="<tr><th>Price Level</th><td>"+pricelevel+"</td></tr>";
}
if(rating1)
{
	printPlacetable+="<tr><th>Rating</th><td>"+rating1+"<span id='rateYoid'></span></td></tr>";
}if(googlepage)
{
	printPlacetable+="<tr><th>Google Page</th><td><a href='"+googlepage+"'>"+googlepage+"</a></td></tr>";
	}
	if(website){
	printPlacetable+="<tr><th>Website</th><td><a href='"+website+"'>"+website+"</a></td></tr>";
	}
	if(place.opening_hours){
	printPlacetable+="<tr><th>Hours</th><td>"+openclose+"</td></tr>";}
	printPlacetable+="</table>";

	pageTabs(printPlacetable,printPhotos,printReview,printReviewHead,address,sendPlace,flag,highlightnum,rating1,tosend,favitem,googlepage,website);
	makeAjaxRequest(address,sendPlace);	
}
});
}

function makeAjaxRequest(toAddress,destName) {
    var yelpReturn;
	var addressSplit = toAddress;
	var addrArray=addressSplit.split(",");
	var address=addrArray[0];
	var cityx=addrArray[1];
	var statex=addrArray[2].trim().split(" ")[0];
	var countryx=addrArray[3];
	console.log(addrArray);
	console.log(statex);
	$.ajax({
		headers: {Accept:"application/json"},
	 	type:"GET",
	 	url:"indexhw8.php",
	 	data: {name:destName,toaddress:address,citya:cityx,statea:statex,countrya:countryx},
	 	contentType: "application/json; charset=utf-8",
	 	success: function(response) {

	 	var json=$.parseJSON(response);
	 	yelpJSON = json;
	 	//datayelp=json;
	 	//sendYelpData(json);
	 	//return yelpReturn;
	 	},

	 	error: function(error){

	 		console.log("in error");
	 	}


	 });
	
}
var printYelpReview;
function sendYelpData(json){
	if(json!="no reviews found"){
		printYelpReview="";
	var yelplength=json.reviews.length;
	 	for(var i=0;i<yelplength;i++) {
	 		var rating=json.reviews[i].rating;
	 		var username=json.reviews[i].user.name;
	 		var userImage=json.reviews[i].user.image_url;
	 		var timecreated=json.reviews[i].time_created;
	 		var reviewtext=json.reviews[i].text;
	 		var url=json.reviews[i].url;
	 		printYelpReview+="<a href='"+url+"' target='_blank'><img src='"+userImage+"' class='img-circle' width='70px' height='70px' style='float:left'/></a>";
			printYelpReview+="<a href='"+url+" target='_blank'>"+username+"</a><br/>";
			for(var stars=1;stars<=rating;stars++) {
				printYelpReview+="<span class='fa fa-star checked'></span>";
			}
			printYelpReview+=timecreated+"<br/><p>"+reviewtext+"</p><br/>";	
	 	}

	 		document.getElementById('reviewsDiv').innerHTML=printYelpReview;
	 		
}
else
{
	document.getElementById('reviewsDiv').innerHTML="<div class='panel panel-warning'><div class='panel-heading'>No Records</div></div>";
}
	 		
	 	
}
function sendGoogleData(){
	//console.log(printReview);
	document.getElementById('reviewsDiv').innerHTML=printReview;
}

function changeReviewOrder(reviews,yelpjson) {
	var printReview1="";
	var arrlength=reviews.length;
	if(yelpjson){
		var arr2length=yelpjson.reviews.length;
	}
	var ratingArr;
	var sortOrder=document.getElementById('sortReviews').value;
	var reviewType=document.getElementById('reviewType').value;
	if(sortOrder == 'Default Order' && yelpjson && reviewType=='yelpReviews') {
		printReview1+=printYelpReview;
	}
	if(sortOrder == 'Default Order' && arrlength!=0 && reviewType == 'googleReviews') {
		printReview1+=printReview;
	}
	if(sortOrder == 'Lowest Rating' && yelpjson && reviewType=='yelpReviews') {
		yelpjson.reviews.sort(function(a,b){
			return a.rating-b.rating
		})
		var yelplength=yelpjson.reviews.length;
	 	for(var i=0;i<yelplength;i++) {
	 		var rating=yelpjson.reviews[i].rating;
	 		var username=yelpjson.reviews[i].user.name;
	 		var userImage=yelpjson.reviews[i].user.image_url;
	 		var timecreated=yelpjson.reviews[i].time_created;
	 		var reviewtext=yelpjson.reviews[i].text;
	 		var url=yelpjson.reviews[i].url;
	 		printReview1+="<a href='"+url+"' target='_blank'><img src='"+userImage+"' class='img-circle' width='70px' height='70px' style='float:left'/></a>";
			printReview1+="<a href='"+url+" target='_blank'>"+username+"</a><br/>";
			for(var stars=1;stars<=rating;stars++) {
				printReview1+="<span class='fa fa-star checked'></span>";
			}
			printReview1+=timecreated+"<br/><p>"+reviewtext+"</p><br/>";	
	 	}
	}
	if(sortOrder == 'Highest Rating' && yelpjson && reviewType == 'yelpReviews') {
		yelpjson.reviews.sort(function(a,b){
			return b.rating-a.rating
		})
		var yelplength=yelpjson.reviews.length;
	 	for(var i=0;i<yelplength;i++) {
	 		var rating=yelpjson.reviews[i].rating;
	 		var username=yelpjson.reviews[i].user.name;
	 		var userImage=yelpjson.reviews[i].user.image_url;
	 		var timecreated=yelpjson.reviews[i].time_created;
	 		var reviewtext=yelpjson.reviews[i].text;
	 		var url=yelpjson.reviews[i].url;
	 		printReview1+="<a href='"+url+"' target='_blank'><img src='"+userImage+"' class='img-circle' width='70px' height='70px' style='float:left'/></a>";
			printReview1+="<a href='"+url+" target='_blank'>"+username+"</a><br/>";
			for(var stars=1;stars<=rating;stars++) {
				printReview1+="<span class='fa fa-star checked'></span>";
			}
			printReview1+=timecreated+"<br/><p>"+reviewtext+"</p><br/>";	
	 	}

	}
	//document.getElementById('sortReviews').selected=true;
	if(sortOrder == 'Lowest Rating' && arrlength!=0 && reviewType == 'googleReviews') {
		reviews.sort(function(a,b) {
			return a.rating-b.rating
		})
	
	for(i=0;i<arrlength;i++) {
		var profilePhoto=reviews[i].profile_photo_url;
			var authorName=reviews[i].author_name;
			var authorURL=reviews[i].author_url;
			var rating=reviews[i].rating;
			var reviewText=reviews[i].text;
			var timesec=reviews[i].time*1000;
			var time=new Date(timesec);
			//time.setUTCSeconds(timesec);
			printReview1+="<img src='"+profilePhoto+"' class='img-circle' width='70px' height='70px' style='float:left'/>";
			printReview1+="<a href='"+authorURL+"'>"+authorName+"</a><br/>";
			for(var stars=1;stars<=rating;stars++) {
				printReview1+="<span class='fa fa-star checked'></span>";
			}
			printReview1+=time+"<br/><p>"+reviewText+"</p><br/>";
		}
	}
	

	if(sortOrder == 'Highest Rating' && arrlength!=0 && reviewType == 'googleReviews') {
		reviews.sort(function(a,b) {
			return b.rating-a.rating
		})

	for(i=0;i<arrlength;i++) {
		var profilePhoto=reviews[i].profile_photo_url;
			var authorName=reviews[i].author_name;
			var authorURL=reviews[i].author_url;
			var rating=reviews[i].rating;
			var reviewText=reviews[i].text;
			var timesec=reviews[i].time*1000;
			var time=new Date(timesec);
			//time.setUTCSeconds(timesec);
			printReview1+="<img src='"+profilePhoto+"' class='img-circle' width='70px' height='70px' style='float:left'/>";
			printReview1+="<a href='"+authorURL+"'>"+authorName+"</a><br/>";
			for(var stars=1;stars<=rating;stars++) {
				printReview1+="<span class='fa fa-star checked'></span>";
			}
			printReview1+=time+"<br/><p>"+reviewText+"</p><br/>";
		}
	}
	
	if(sortOrder == 'Most Recent' && arrlength!=0 && reviewType == 'googleReviews') {
		reviews.sort(function(a,b) {
			return new Date(b.time*1000) - new Date(a.time*1000)
		})
		for(i=0;i<arrlength;i++) {
		var profilePhoto=reviews[i].profile_photo_url;
			var authorName=reviews[i].author_name;
			var authorURL=reviews[i].author_url;
			var rating=reviews[i].rating;
			var reviewText=reviews[i].text;
			var timesec=reviews[i].time*1000;
			var time=new Date(timesec);
			printReview1+="<img src='"+profilePhoto+"' class='img-circle' width='70px' height='70px' style='float:left'/>";
			printReview1+="<a href='"+authorURL+"'>"+authorName+"</a><br/>";
			for(var stars=1;stars<=rating;stars++) {
				printReview1+="<span class='fa fa-star checked'></span>";
			}
			printReview1+=time+"<br/><p>"+reviewText+"</p><br/>";
		}	
	}
	if(sortOrder == 'Most Recent' && yelpjson && reviewType == 'yelpReviews') {
		yelpjson.reviews.sort(function(a,b) {
			return new Date(b.time_created) - new Date(a.time_created)
		})
		var yelplength=yelpjson.reviews.length;
	 	for(var i=0;i<yelplength;i++) {
	 		var rating=yelpjson.reviews[i].rating;
	 		var username=yelpjson.reviews[i].user.name;
	 		var userImage=yelpjson.reviews[i].user.image_url;
	 		var timecreated=yelpjson.reviews[i].time_created;
	 		var reviewtext=yelpjson.reviews[i].text;
	 		var url=yelpjson.reviews[i].url;
	 		printReview1+="<a href='"+url+"' target='_blank'><img src='"+userImage+"' class='img-circle' width='70px' height='70px' style='float:left'/></a>";
			printReview1+="<a href='"+url+" target='_blank'>"+username+"</a><br/>";
			for(var stars=1;stars<=rating;stars++) {
				printReview1+="<span class='fa fa-star checked'></span>";
			}
			printReview1+=timecreated+"<br/><p>"+reviewtext+"</p><br/>";	
	 	}
	}
	if(sortOrder == 'Least Recent' && yelpjson && reviewType=='yelpReviews') {
		yelpjson.reviews.sort(function(a,b) {
			return new Date(a.time_created) - new Date(b.time_created)
		})
		var yelplength=yelpjson.reviews.length;
	 	for(var i=0;i<yelplength;i++) {
	 		var rating=yelpjson.reviews[i].rating;
	 		var username=yelpjson.reviews[i].user.name;
	 		var userImage=yelpjson.reviews[i].user.image_url;
	 		var timecreated=yelpjson.reviews[i].time_created;
	 		var reviewtext=yelpjson.reviews[i].text;
	 		var url=yelpjson.reviews[i].url;
	 		printReview1+="<a href='"+url+"' target='_blank'><img src='"+userImage+"' class='img-circle' width='70px' height='70px' style='float:left'/></a>";
			printReview1+="<a href='"+url+" target='_blank'>"+username+"</a><br/>";
			for(var stars=1;stars<=rating;stars++) {
				printReview1+="<span class='fa fa-star checked'></span>";
			}
			printReview1+=timecreated+"<br/><p>"+reviewtext+"</p><br/>";	
	 	}
	}
	if(sortOrder == 'Least Recent' && arrlength!=0 && reviewType=='googleReviews') {
		reviews.sort(function(a,b) {
			return new Date(a.time*1000) - new Date(b.time*1000)
		})
		for(i=0;i<arrlength;i++) {
		var profilePhoto=reviews[i].profile_photo_url;
			var authorName=reviews[i].author_name;
			var authorURL=reviews[i].author_url;
			var rating=reviews[i].rating;
			var reviewText=reviews[i].text;
			var timesec=reviews[i].time*1000;
			var time=new Date(timesec);
			printReview1+="<img src='"+profilePhoto+"' class='img-circle' width='70px' height='70px' style='float:left'/>";
			printReview1+="<a href='"+authorURL+"'>"+authorName+"</a><br/>";
			for(var stars=1;stars<=rating;stars++) {
				printReview1+="<span class='fa fa-star checked'></span>";
			}
			printReview1+=time+"<br/><p>"+reviewText+"</p><br/>";
		}	
	}

	document.getElementById('reviewsDiv').innerHTML=printReview1;
}



</script>
<!--Always put this at the end -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>
</html>