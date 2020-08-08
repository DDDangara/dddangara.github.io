// Global variable definitions
// DB column numbers
var OPT_ID = 0;
var OPT_TITLE = 1;
var OPT_VOTES = 2;

var votedID;
var idvote = 0;

$(document).ready(function(){
  $("#poll").submit(formProcess); // setup the submit handler
  
  if ($("#poll-results").length > 0 ) {
    animateResults();
  }
  if (document.getElementById('idvote')) 
  {
   idvote=document.getElementById('idvote').value;
  }   
  if ($.cookie('vote_id_'+idvote)) {
    $("#poll-container").empty();
    votedID = $.cookie('vote_id_'+idvote);
    $.getJSON("poll.php?vote=none",loadResults);
  }
});

function formProcess(event){
  event.preventDefault();
  
  var id = $("input[@name='poll']:checked").attr("value");
  id = id.replace("opt",'');
  idvote=document.getElementById('idvote').value;
  $("#poll-container").fadeOut("slow",function(){
    $(this).empty();
    
    votedID = id;
    $.getJSON("poll.php?vote="+id+'&idvote='+idvote,loadResults);
    
    $.cookie('vote_id_'+idvote, id, {expires: 365, path: '/'});
    });
}

function animateResults(){
  $("#poll-results div").each(function(){
      var percentage = $(this).next().text();
      $(this).css({width: "0%"}).animate({
				width: percentage}, 'slow');
  });
}

function loadResults(data) {
  var total_votes = 0;
  var percent;
  
  for (id in data) {
    total_votes = total_votes+parseInt(data[id][OPT_VOTES]);
  }
  
  var results_html = "<div id='poll-results'>\n<dl class='graph'>\n";
  for (id in data) {
    percent=0; 
    if (data[id][OPT_VOTES]>0) 
      percent = Math.round((parseInt(data[id][OPT_VOTES])/parseInt(total_votes))*100);
    if (data[id][OPT_ID] !== votedID) {
      results_html = results_html+"<dd  style='float:left;' class='bar-container'><div id='bar"+data[id][OPT_ID]+"'style='width:0%; float:left; '><nobr><b>"+data[id][OPT_TITLE]+"</b></nobr></div><strong>"+percent+"%</strong></dd>\n";
    } else {
      results_html = results_html+"<dd class='bar-container' style='float:left;'><div id='bar"+data[id][OPT_ID]+"'style='width:0%;background-color:#FD5300; float:left;'><nobr><b>"+data[id][OPT_TITLE]+"</b></nobr></div><strong>"+percent+"%</strong></dd>\n";
    }
  }
  
  results_html = results_html+"</dl><p style='clear: both;'>Total Votes: "+total_votes+"</p></div>\n";
  
  $("#poll-container").append(results_html).fadeIn("slow",function(){
    animateResults();});
}