<html>
<head>
    <title><?php echo $title; ?> Gas Sites</title>
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.0/jquery-ui.min.js"></script>
    <script src="<?php echo BASE_PATH; ?>jquery.dataTables.min.js"></script>

    <style type="text/css">
    .wrapper {
        width: 1200px;
        margin: 1em auto 5em;
    }
    fieldset { margin-left: 2em; }
    select ~ small, input ~ small { margin-left: 1em; color: #3a87ad;}
    </style>
    <script>
    $(document).ready(function() { 
        // Custom DataTable Sorting
        jQuery.extend( jQuery.fn.dataTableExt.oSort, {
            "formatted-num-pre": function ( a ) {
                a = (a==="-") ? 0 : a.replace( /[^\d\-\.]/g, "" );
                return parseFloat( a );
            },
         
            "formatted-num-asc": function ( a, b ) {
                return a - b;
            },
         
            "formatted-num-desc": function ( a, b ) {
                return b - a;
            }
        } );
        <?php if (strtolower($title) != 'wormhole'){ ?>
        $('#siteTable').dataTable({
            "bPaginate": false,
            "bFilter": false,
            "aoColumns": [
                null,
                null,
                null,
                null,
                { "sType": 'formatted-num' },
                { "sType": 'formatted-num' },
                { "sType": 'formatted-num' },
                { "sType": 'formatted-num' },
                { "sType": 'formatted-num' }],
        });
        
        <?php } echo (!isset($_GET['frigate']) ? '$("#venture-inputs").hide();':null); ?>
        $("#ventureToggle").click(function(){
            // If checked
            if ($(this).is(":checked")) {
                $("#venture-inputs").show();
            }
            else {
                $("#venture-inputs").hide();
            }
        });
        
        $("select").bind("change", function(){
            if (typeof $(this).data('modifier') == 'undefined'){
                var selected = $(this).find('option:selected');
                var update = parseFloat(selected.data('override')); }
            else { 
                var modifier = parseFloat($(this).data('modifier')); 
                var update = $(this).val() * modifier; }
                
            $("#"+$(this).data('calc')+" > span").text(update);
        });
        
        $("select").each(function(index){
            if (typeof $(this).data('modifier') == 'undefined'){
                var selected = $(this).find('option:selected');
                var update = parseFloat(selected.data('override')); }
            else { 
                var modifier = parseFloat($(this).data('modifier')); 
                var update = $(this).val() * modifier; }
                
            $("#"+$(this).data('calc')+" > span").text(update);
        });
    });
    </script>
</head>
<body>

<div class='wrapper'>
<h1 style='text-align: center;'><?php echo $title; ?> Gas Sites</h1>
<hr />
<?php
/*
Mining Foreman - 2% bonus to fleet members' mining yield per level.
Mining Director - 100% bonus to effectiveness of Mining Foreman link modules per level after level 2 is trained. - modifies LINK!
t2 link requires mining director 5
Warfare Link Specialist - Boosts effectiveness of all warfare link and mining foreman modules by 10% per level.
Mindlink - replaced mining forman bonus to flat 15%
orca - 3% to link per level
rorqual - 10% to links per level
*/
?>
<form method='post' action='<?php echo BASE_PATH; ?>redirect.php'>
    <input type='hidden' name='redirect' value='<?php echo strtolower($title); ?>' />
    What level is your Gas Cloud Harvesting skill at?<br />
    
    <select class='span1' name='level' data-calc='ghBonus' data-modifier='1'>
        <option value='1'<?php echo @($_GET['level'] == 1 ? ' selected':null);?> />I
        <option value='2'<?php echo @($_GET['level'] == 2 ? ' selected':null);?> />II
        <option value='3'<?php echo @($_GET['level'] == 3 ? ' selected':null);?> />III
        <option value='4'<?php echo @($_GET['level'] == 4 ? ' selected':null);?> />IV
        <option value='5'<?php echo @($_GET['level'] == 5 ? ' selected':null);?> />V
    </select> <small id='ghBonus'><span>1</span> Gas Harvester's</small>
    <span class="help-block"><small>This assumes you're using as many gas harvesters that your level allows for and, if level V, will be using 5 GH IIs</small></span><br />
    Mining Foreman Link - Laser Optimization?<br />
    
    <select class='span2' name='link' data-calc='linkBonus'>
        <option value='0' data-override='0'<?php echo @($_GET['link'] == 0 ? ' selected':null);?> />None
        <option value='1' data-override='-2'<?php echo @($_GET['link'] == 1 ? ' selected':null);?> />Tech 1
        <option value='2' data-override='-2.5'<?php echo @($_GET['link'] == 2 ? ' selected':null);?> />Tech 2
    </select><small id='linkBonus'><span>0</span>% Duration</small><br /><br />
    
    <label class='checkbox'>
        <input type='checkbox' name='venture' id='ventureToggle' value='true'<?php echo (isset($_GET['frigate']) ? ' checked':null);?>>
        I'm using a Venture, yo! <small>100% bonus to gas yield</small>
    </label>
    
    <fieldset id='venture-inputs'><br />
         What level is your Mining Frigate skill at?<br />
    
        <select class='span1' name='frigate' data-calc='frigBonus' data-modifier='5'>
            <option value='1'<?php echo @($_GET['frigate'] == 1 ? ' selected':null);?> />I
            <option value='2'<?php echo @($_GET['frigate'] == 2 ? ' selected':null);?> />II
            <option value='3'<?php echo @($_GET['frigate'] == 3 ? ' selected':null);?> />III
            <option value='4'<?php echo @($_GET['frigate'] == 4 ? ' selected':null);?> />IV
            <option value='5'<?php echo @($_GET['frigate'] == 5 ? ' selected':null);?> />V
        </select> <small id='frigBonus'>-<span>5</span>% Duration</small>
        <span class="help-block"><small>The Venture has 2 turret slots - assuming all slots are filled with Gas Harvesters or what is allowed by Gas Cloud Harvesting skill, whichever is lower.</small></span>
    </fieldset>
    <br />
    <button class="btn btn-small btn-primary" type='submit'>Submit</button>
</form>
