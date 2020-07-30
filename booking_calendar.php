<?php
    if (!empty($_POST)) {
        
        $arrival_date = $_POST['arrival_date'] ?? '';
        $guest_number = $_POST['guest_number'];
      
        //get data from json file
        $filename = 'Code-Test-Input.json';
        
        if (file_exists($filename)) {
            $string = file_get_contents($filename);
            $json_abj = json_decode($string, true);
            
            $rooms = []; // rooms match the date and availablibity
            $available_rooms = []; //rooms can be booked
              
            // First, find available rooms by arrival date, 
            // arrival date must be between start_date and end_date
            // And have to be available
            foreach ($json_abj as $key => $value){
                if ($key == 'availability') {
                    foreach ($value as $k => $v){
                        
                        $start_date = $v['start_date'];
                        $end_date = $v['end_date'];
                        $available =  $v['available'];
                    
                        if (strtotime($arrival_date) >= strtotime($start_date) &&
                            strtotime($arrival_date) <= strtotime($end_date) && $available) {
                            array_push($rooms, $v);
                        }
                    }
                }
            }
            
            //Second, match guust numbers
            foreach ($json_abj as $key => $value){
                if ($key == 'rooms') {
                    foreach ($value as $k => $v){
                        foreach ($rooms as $k1 => $v1) {
                            if ($v1['room_name'] == $v['name'] && 
                                $v['maxguests'] == $guest_number) {
                               
                                $v1['tax-inclusive'] = $v['tax-inclusive'];
                                array_push($available_rooms, $v1);
                            }
                        } 
                    }
                }
            }
            
            //Third, find rates
            foreach ($json_abj as $key => $value){
                if ($key == 'rates') {
                    foreach ($value as $k => $v){
                        $rate_start_date = $v['start_date'];
                        $rate_end_date = $v['end_date'];
                        foreach ($available_rooms as $k1 => $v1) {
                            if (strtotime($arrival_date) >= strtotime($rate_start_date) &&
                                strtotime($arrival_date) <= strtotime($rate_end_date) &&
                                $v1['room_name'] == $v['room_name']) {
                                $available_rooms[$k1]['rate'] = $v['rate'];
                            }  
                        }
                    }
                }
            }
            
            //Fourth, find property information
             foreach ($json_abj as $key => $value){
                if ($key == 'property') {
                    foreach ($available_rooms as $k1 => $v1) {
                        $available_rooms[$k1]['property'] = $value['name'];
                        $available_rooms[$k1]['currency'] = $value['currency'];
                        $available_rooms[$k1]['tax'] = $value['tax'];
                        //$available_rooms[$k1]['tax-inclusive'] = $value['tax-inclusive'];
                         $available_rooms[$k1]['arrival_date'] = $arrival_date;
                    }
                }
            }
            //echo '<pre>';var_dump($available_rooms);die;
        } else {
            die("Your input file: $filename do not exist!");
        }
    } else {
      // redirect back to index page
      header('location: ./index.php');
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title>Booking Calendar</title>
        
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <!-- Popper JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script> 
	<script src="https://momentjs.com/downloads/moment.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
	 <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script> 
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.0.9/datepicker.min.css">
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        
        <!-- customized css -->
        <link rel="stylesheet" href="./bms.css">
</head>
<body>
<div class="container theme-showcase">
    <h1>Booking Calendar</h1>
    <div class="row" id="holder"></div>
    <div class="row" id="cart" ></div>
</div>
<!-- Better to use Bootstrap Full calendar -->
<script type="text/tmpl" id="tmpl">
  {{ 
  var date = date || new Date(),
      month = date.getMonth(), 
      year = date.getFullYear(), 
      first = new Date(year, month, 1), 
      last = new Date(year, month + 1, 0),
      startingDay = first.getDay(), 
      thedate = new Date(year, month, 1 - startingDay),
      dayclass = lastmonthcss,
      today = new Date(),
      i, j; 
  
    thedate = new Date(date);
    thedate.setDate(date.getDate() - date.getDay());
    first = new Date(thedate);
    last = new Date(thedate);
    last.setDate(last.getDate()+6);
  
  }}
  <table class="calendar-table table table-condensed table-tight borderless" style="width: 100%;height:100px">
    <thead>
      <tr>
        <td colspan="7" style="text-align: center">
          <table style="white-space: nowrap; width: 100%;" class="borderless">
            <tr>
              <td width="50%" align="center">
                <span class="btn-group">
                  <button class="js-cal-prev btn btn-default" title="Previous"><i class="fa fa-arrow-left" aria-hidden="true"></i></button>
                  <button class="js-cal-next btn btn-default" title="Next"><i class="fa fa-arrow-right" aria-hidden="true"></i></button>
                </span>
                <button class="js-cal-option btn btn-default {{: first.toDateInt() <= today.toDateInt() && today.toDateInt() <= last.toDateInt() ? 'active':'' }}" data-date="{{: today.toISOString()}}" data-mode="week"><strong>TODAY</strong></button>
              </td>
              <td>
                <span class="btn-group btn-group-lg">
                  {{ if (mode !== 'day') { }}
                    {{ if (mode === 'month') { }}<button class="js-cal-option btn btn-link" data-mode="year">{{: months[month] }}</button>{{ } }}
                    {{ if (mode ==='week') { }}
                      <button class="btn ">{{: shortMonths[first.getMonth()] }} {{: first.getDate() }} - {{: shortMonths[last.getMonth()] }} {{: last.getDate() }}</button>
                    {{ } }}
                    <button class="js-cal-years btn ">{{: year}}</button> 
                  {{ } else { }}
                    <button class="btn btn-link disabled">{{: date.toDateString() }}</button> 
                  {{ } }}
                </span>
              </td>
              
            </tr>
          </table>
          
        </td>
      </tr>
    </thead>
    {{ if (mode ==='year') {
      month = 0;
    }}
    <tbody>
      {{ for (j = 0; j < 3; j++) { }}
      <tr>
        {{ for (i = 0; i < 4; i++) { }}
        <td class="calendar-month month-{{:month}} js-cal-option" data-date="{{: new Date(year, month, 1).toISOString() }}" data-mode="week">
          {{: months[month] }}
          {{ month++;}}
        </td>
        {{ } }}
      </tr>
      {{ } }}
    </tbody>
    {{ } }}
    {{ if (mode ==='month' || mode ==='week') { }}
    <!--<thead>-->
    <!--  <tr class="c-weeks">-->
    <!--    {{ for (i = 0; i < 7; i++) { }}-->
    <!--      <th class="c-name" style="background-color: #ddd">-->
    <!--        <strong>{{: days[i] }}</strong>-->
    <!--      </th>-->
    <!--    {{ } }}-->
    <!--  </tr>-->
    <!--</thead>-->
    <tbody>
      {{ for (j = 0; j < 6 && (j < 1 || mode === 'month'); j++) { }}
     
        {{ for (i = 0; i < 7; i++) { }}
         <tr>
           <td class="c-name"  style="height: 50px;width: 120px;background-color: #ddd;vertical-align: middle;">
            <strong>{{: days[i] }}</strong>
          </td>
        {{ if (thedate > last) { dayclass = nextmonthcss; } else if (thedate >= first) { dayclass = thismonthcss; } }}
        <td  valign="middle" class="calendar-day {{: dayclass }} {{: thedate.toDateCssClass() }} {{: date.toDateCssClass() === thedate.toDateCssClass() ? 'selected':'' }} {{: daycss[i] }} js-cal-option" data-date="{{: thedate.toISOString() }}">
          <div class="date"><strong>{{: thedate.getDate() }}</strong></div>
          {{ thedate.setDate(thedate.getDate() + 1);}}
        </td>
         </tr>
        {{ } }}
      {{ } }}
    </tbody>
    {{ } }}
 
  </table>
</script>
<script>
    var $currentPopover = null;
  $(document).on('shown.bs.popover', function (ev) {
    var $target = $(ev.target);
    if ($currentPopover && ($currentPopover.get(0) != $target.get(0))) {
      $currentPopover.popover('toggle');
    }
    $currentPopover = $target;
  }).on('hidden.bs.popover', function (ev) {
    var $target = $(ev.target);
    if ($currentPopover && ($currentPopover.get(0) == $target.get(0))) {
      $currentPopover = null;
    }
  });


//quicktmpl is a simple template language I threw together a while ago; it is not remotely secure to xss and probably has plenty of bugs that I haven't considered, but it basically works
//the design is a function I read in a blog post by John Resig (http://ejohn.org/blog/javascript-micro-templating/) and it is intended to be loosely translateable to a more comprehensive template language like mustache easily
$.extend({
    quicktmpl: function (template) {return new Function("obj","var p=[],print=function(){p.push.apply(p,arguments);};with(obj){p.push('"+template.replace(/[\r\t\n]/g," ").split("{{").join("\t").replace(/((^|\}\})[^\t]*)'/g,"$1\r").replace(/\t:(.*?)\}\}/g,"',$1,'").split("\t").join("');").split("}}").join("p.push('").split("\r").join("\\'")+"');}return p.join('');")}
});

$.extend(Date.prototype, {
  //provides a string that is _year_month_day, intended to be widely usable as a css class
  toDateCssClass:  function () { 
    return '_' + this.getFullYear() + '_' + (this.getMonth() + 1) + '_' + this.getDate(); 
  },
  //this generates a number useful for comparing two dates; 
  toDateInt: function () { 
    return ((this.getFullYear()*12) + this.getMonth())*32 + this.getDate(); 
  },
  toTimeString: function() {
    var hours = this.getHours(),
        minutes = this.getMinutes(),
        hour = (hours > 12) ? (hours - 12) : hours,
        ampm = (hours >= 12) ? ' pm' : ' am';
    if (hours === 0 && minutes===0) { return ''; }
    if (minutes > 0) {
      return hour + ':' + minutes + ampm;
    }
    return hour + ampm;
  }
});

(function ($) {

  //t here is a function which gets passed an options object and returns a string of html. I am using quicktmpl to create it based on the template located over in the html block
  var t = $.quicktmpl($('#tmpl').get(0).innerHTML);
  
  function calendar($el, options) {
    //actions aren't currently in the template, but could be added easily...
    $el.on('click', '.js-cal-prev', function () {
      switch(options.mode) {
      case 'year': options.date.setFullYear(options.date.getFullYear() - 1); break;
      case 'month': options.date.setMonth(options.date.getMonth() - 1); break;
      case 'week': options.date.setDate(options.date.getDate() - 7); break;
      case 'day':  options.date.setDate(options.date.getDate() - 1); break;
      }
      draw();
    }).on('click', '.js-cal-next', function () {
      switch(options.mode) {
      case 'year': options.date.setFullYear(options.date.getFullYear() + 1); break;
      case 'month': options.date.setMonth(options.date.getMonth() + 1); break;
      case 'week': options.date.setDate(options.date.getDate() + 7); break;
      case 'day':  options.date.setDate(options.date.getDate() + 1); break;
      }
      draw();
    }).on('click', '.js-cal-option', function () {
      var $t = $(this), o = $t.data();
      if (o.date) { o.date = new Date(o.date); }
      $.extend(options, o);
      draw();
    }).on('click', '.js-cal-years', function () {
      var $t = $(this), 
          haspop = $t.data('popover'),
          s = '', 
          y = options.date.getFullYear() - 2, 
          l = y + 5;
      if (haspop) { return true; }
      for (; y < l; y++) {
        s += '<button type="button" class="btn btn-default btn-lg btn-block js-cal-option" data-date="' + (new Date(y, 1, 1)).toISOString() + '" data-mode="year">'+y + '</button>';
      }
      $t.popover({content: s, html: true, placement: 'auto top'}).popover('toggle');
      return false;
    }).on('click', '.event', function () {
    
        var $t = $(this);
        room_data = $t.attr('title');

        //get selected date
        var selected_date_utc = new Date($(this).parent('td').attr('data-date'));
        var d = selected_date_utc.getDate();
        var m =  selected_date_utc.getMonth();
        m += 1;  // JavaScript months are 0-11
        var y = selected_date_utc.getFullYear();
        var selected_date = y + '-' + (m < 10 ? '0' + m : '' + m) + '-' + (d < 10 ? '0' + d : '' + d);
      
        //add cart
        var queryString;
        queryString = 'action=add&data=' + room_data + ',booking_date:' + selected_date ;
        jQuery.ajax({
              url: "./ajax_action.php",
              data:queryString,
              type: "POST",
              success:function(data){
                $('#cart').html(data);
              }
        });
      return false;
    });
    function dayAddEvent(index, event) {
      if (!!event.allDay) {
        monthAddEvent(index, event);
        return;
      }
      var $event = $('<div/>', {'class': 'event', text: event.title, title: event.title, 'data-index': index}),
          start = event.start,
          end = event.end || start,
          time = event.start.toTimeString(),
          hour = start.getHours(),
          timeclass = '.time-22-0',
          startint = start.toDateInt(),
          dateint = options.date.toDateInt(),
          endint = end.toDateInt();
      if (startint > dateint || endint < dateint) { return; }
      
      if (!!time) {
        $event.html('<strong>' + time + '</strong> ' + $event.html());
      }
      $event.toggleClass('begin', startint === dateint);
      $event.toggleClass('end', endint === dateint);
      
      //$(timeclass).append($event);
    }
    
    function monthAddEvent(index, event) {
      //only show room name on calendar 
      var display_arr = event.title.split(",");
      var $event = $('<div/>', {'class': 'event', text: event.text, title: event.title, 'data-index': index}),
          e = new Date(event.start),
          dateclass = e.toDateCssClass(),
          day = $('.' + e.toDateCssClass()),
          empty = $('<div/>', {'class':'clear event', html:' '}), 
          numbevents = 0, 
          time = event.start.toTimeString(),
          endday = event.end && $('.' + event.end.toDateCssClass()).length > 0,
          checkanyway = new Date(e.getFullYear(), e.getMonth(), e.getDate()+40),
          existing,
          i;
      $event.toggleClass('all-day', !!event.allDay);
      if (!event.end) {
        $event.addClass('begin end');
        $('.' + event.start.toDateCssClass()).append($event);
        return;
      }
            
      while (e <= event.end && (day.length || endday || options.date < checkanyway)) {
        if(day.length) { 
          existing = day.find('.event').length;
          numbevents = Math.max(numbevents, existing);
          for(i = 0; i < numbevents - existing; i++) {
            day.append(empty.clone());
          }
          day.append(
            $event.
            toggleClass('begin', dateclass === event.start.toDateCssClass()).
            toggleClass('end', dateclass === event.end.toDateCssClass())
          );
          $event = $event.clone();
          $event.html(' ');
        }
        e.setDate(e.getDate() + 1);
        dateclass = e.toDateCssClass();
        day = $('.' + dateclass);
      }
    }
    function yearAddEvents(events, year) {
      var counts = [0,0,0,0,0,0,0,0,0,0,0,0];
      $.each(events, function (i, v) {
        if (v.start.getFullYear() === year) {
            counts[v.start.getMonth()]++;
        }
      });
      $.each(counts, function (i, v) {
        if (v!==0) {
            $('.month-'+i).append('<span class="badge">'+v+'</span>');
        }
      });
    }
    
    function draw() {
      $el.html(t(options));
      //potential optimization (untested), this object could be keyed into a dictionary on the dateclass string; the object would need to be reset and the first entry would have to be made here
      $('.' + (new Date()).toDateCssClass()).addClass('today');
      if (options.data && options.data.length) {
        if (options.mode === 'year') {
            yearAddEvents(options.data, options.date.getFullYear());
        } else if (options.mode === 'month' || options.mode === 'week') {
            $.each(options.data, monthAddEvent);
        } else {
            $.each(options.data, dayAddEvent);
        }
      }
    }
    
    draw();    
  }
  
  ;(function (defaults, $, window, document) {
    $.extend({
      calendar: function (options) {
        return $.extend(defaults, options);
      }
    }).fn.extend({
      calendar: function (options) {
        options = $.extend({}, defaults, options);
        return $(this).each(function () {
          var $this = $(this);
          calendar($this, options);
        });
      }
    });
  })({
    days: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
    months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
    shortMonths: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    date: (new Date()),
        daycss: ["c-sunday", "", "", "", "", "", "c-saturday"],
        todayname: "Today",
        thismonthcss: "current",
        lastmonthcss: "outside",
        nextmonthcss: "outside",
    mode: "week",
    data: []
  }, jQuery, window, document);
    
})(jQuery);

var data = [];

  //add data 
  <?php foreach ($available_rooms as $key => $value)  { ?>
    <?php 
        //add all informaton to calendar
        $title  = 'room_name:'.$value["room_name"] ;
        $title .= ',property:'. ucfirst($value['property']);
        $title .= ',rate:' . $value['rate'];
        $title .= ',currency:' . $value['currency'];
        $title .= ',tax:' . $value['tax'];
        $title .= ',tax_inclusive:' . $value['tax-inclusive'];
        
        $display_text = ucfirst($value['property']) . ', ' .$value["room_name"] ;
    ?>            
    data.push({ 
        title: '<?php echo $title?>', 
        start: new Date('<?php print_r($value["start_date"])?>'), 
        end: new Date('<?php print_r($value["end_date"])?>'), 
        allDay: true, 
        text: '<?php echo $display_text ?>'  });
  <?php } ?>
//data.push({ title: 'Cottage two', start: new Date(2020, 6, 29), end: new Date(2020, 6, 29), allDay: true, text: ''  });
 
    data.sort(function(a,b) { return (+a.start) - (+b.start); });
//data must be sorted by start date
//Actually do everything
$('#holder').calendar({
  data: data
});
</script>