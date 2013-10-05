<?php

require_once 'head.php';

function skillLevels($range, $name, $modifier, $initMod, $initDesc, $modType = '%', $override = null){
    global $ghSkill, $director, $warfare, $capSkill, $ventureSkill;
    echo "<select class='span1' name='".$name."' data-calc='".$name."Bonus' data-modifier='".$modifier."'>";
    foreach ($range AS $i) {
        echo "  <option value='".$i."' ".($override !== null ? "data-override='".$override[$i-1]."' ":null). (${$name} == $i ? ' selected':null)." />".romanNumerals($i)."\n"; }
    echo "</select> <small id='".$name."Bonus'><span>".$initMod."</span>".$modType." ".$initDesc."</small>";
}
?>
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
            "sDom": '<"top">rt<"bottom"flp><"clear">',
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
        
        /*
            test capital ship selection, depending on which one, create data-modifier 
            on ship skill selection. 
        */
        $('#capShip').bind("change", function(){
            var selected = $(this).find('option:selected');
            $('#capSkill').data("modifier", selected.data('multiplier'));
            
            // Update UI
            $("#capSkill").trigger('change');
        });

        // !- Bind checkboxes to show more modifiers
        $('input[type="checkbox"]').bind("change", function(){
            if (typeof $(this).data('toggle') != 'undefined') {
                if ($(this).is(":checked")) {
                    $("#"+$(this).data('toggle')).show(); }
                else {
                    $("#"+$(this).data('toggle')).hide();
                }
            }
        }).trigger('change');
        
        // !- Selection modifier calculations
        $("select").bind("change", function(){
            var selected = $(this).find('option:selected');
            if (typeof selected.data('override') != 'undefined'){
                var update = parseFloat(selected.data('override')); }
            else {
                var modifier = parseFloat($(this).data('modifier')); 
                var update = $(this).val() * modifier; }
                
            $("#"+$(this).data('calc')+" > span").text(update);
        }).trigger('change');
    });
</script>

<div class='wrapper'>
<h1 style='text-align: center;'><?php echo $title; ?> Gas Sites</h1>
<hr />
<form method='post' action='<?php echo BASE_PATH; ?>submit'>
    <input type='hidden' name='redirect' value='<?php echo strtolower($title); ?>' />
    <label>What level is your Gas Cloud Harvesting skill at?</label>
    
    <?php skillLevels(range(1,5), 'ghSkill', 1, 1, "Gas Harvester(s)", null); ?>
    <span class="help-block"><small>This assumes you're using as many gas harvesters that your level allows for and, if level V, will be using 5 GH IIs</small></span>
    
    <label class='checkbox'>
        <input type='checkbox' class='toggle' name='boolLinks' id='linkToggle' value='true' data-toggle='links' <?php echo ($boolLinks != 0 ? ' checked':null);?>>
        Pimp My Fleet <small class='desc'>Mining Foreman Links, Capital Boosts, Mindlink</small>
    </label>
    <fieldset id='links'>
        <label>Mining Foreman Link - Laser Optimization?</label>
        <select class='span2' name='linkTech' data-calc='linkBonus'>
            <option value='0' data-override='0'<?php echo ($linkTech == 0 ? ' selected':null);?> />None
            <option value='1' data-override='-2'<?php echo ($linkTech == 1 ? ' selected':null);?> />Tech 1
            <option value='2' data-override='-2.5'<?php echo ($linkTech == 2 ? ' selected':null);?> />Tech 2
        </select><small id='linkBonus'><span>0</span>% Base Duration</small>
        
        <!-- if we're using links, than Mining Director needs to be at least 1 (hence no `zero` option) -->
        <label>Links: Mining Director skill level</label>
        <?php skillLevels(range(1,5), 'director', 20, 20, "Effectiveness to Mining Foreman Links"); ?>
        
        <label>Links: Warfare Link Specialist skill level</label>
        <?php skillLevels(range(0,5), 'warfare', 10, 10, "Effectiveness to Mining Foreman Links"); ?>
        
        <label class='checkbox'>
            <input type='checkbox' name='boolMindlink' id='mindlink' value='true' <?php echo ($boolMindlink != 0 ? ' checked':null);?>>
            Mining Foreman Mindlink <small>25% Effectiveness to Mining Foreman Links</small>
        </label>
        
        <label class='checkbox'>
            <input type='checkbox' class='toggle' name='capital' id='capitalToggle' value='true' data-toggle='capital' <?php echo ($capShip != 0 ? ' checked':null);?>>
            Capital Industrial Ship <small class='desc'>Orca; Rorqual</small>
        </label>
        <fieldset id='capital'>
            <select class='span2' name='capShip' id='capShip'>
                <option value='1' data-multiplier='3'  <?php echo ($capShip == 1 ? ' selected':null);?> />Orca
                <option value='2' data-multiplier='10' <?php echo ($capShip == 2 ? ' selected':null);?> />Rorqual
            </select>
            
            <label>Respective Ship Skill</label>
            <select class='span1' name='capSkill' id='capSkill' data-calc='capBonus'>
                <option value='1'<?php echo ($capSkill == 1 ? ' selected':null);?> />I
                <option value='2'<?php echo ($capSkill == 2 ? ' selected':null);?> />II
                <option value='3'<?php echo ($capSkill == 3 ? ' selected':null);?> />III
                <option value='4'<?php echo ($capSkill == 4 ? ' selected':null);?> />IV
                <option value='5'<?php echo ($capSkill == 5 ? ' selected':null);?> />V
            </select> <small id='capBonus'>+<span>5</span>% Effectiveness of Mining Foreman Links</small>
        </fieldset>
    </fieldset>
    
    <label class='checkbox'>
        <input type='checkbox' class='toggle' name='implants' id='implantsToggle' value='true' data-toggle='implants' <?php echo ($implants != 0 ? ' checked':null);?>>
        Eifyr and Co. 'Alchemist' Gas Harvesting
    </label>
    <fieldset id='implants'>
        <label class="radio">
            <input type="radio" name="ghImplant" id="ghImplant1" value="1" <?php echo ($implants == 1 || $implants == 0 ? ' checked':null); ?>>GH-801 
            <small>1% reduction to cycle time</small>
        </label>
        <label class="radio">
            <input type="radio" name="ghImplant" id="ghImplant3" value="2" <?php echo ($implants == 2 ? ' checked':null); ?>>GH-803 
            <small>3% reduction to cycle time</small>
        </label>
        <label class="radio">
            <input type="radio" name="ghImplant" id="ghImplant3" value="3" <?php echo ($implants == 3 ? ' checked':null); ?>>GH-805 
            <small>5% reduction to cycle time</small>
        </label>
    </fieldset>
    
    <label class='checkbox'>
        <input type='checkbox' class='toggle' name='boolVenture' id='ventureToggle' value='true' data-toggle='venture-inputs' <?php echo ($ventureSkill != 0 ? ' checked':null);?>>
        I'm using a Venture, yo! <small>100% Bonus to Gas Yield</small>
    </label>
    
    <fieldset id='venture-inputs'>
        <label>What level is your Mining Frigate skill at?</label>
        <?php skillLevels(range(1,5), 'ventureSkill', -5, 5, "Duration"); ?>
        
        <span class="help-block"><small>The Venture has 2 turret slots - assuming all slots are filled with Gas Harvesters or what is allowed by Gas Cloud Harvesting skill, whichever is lower.</small></span>
    </fieldset>
    <br />
    <button class="btn btn-small btn-primary" type='submit'>Submit</button>
    <?php if (DEBUG === true){ ?>
    <input type='hidden' name='debug' value='true' />
    <?php } ?>
</form>

<?php
if (DEBUG == true) { ?>
<div id='debug'>
    <h3>Debug</h3>
    Link base and multipliers:
    <?php var_dump($lBonuses); ?>
    
    Durration modifiers:
    <?php var_dump($dBonuses); ?>
    
    Default variables:
<pre>
Mining Amount: <strong><?php echo $mine_amount; ?></strong> m3 / cycle / module
Default Cycle: <strong><?php echo $duration; ?></strong> seconds / module</pre>
    Resultant data:
<pre>
Mining Amount: <strong><?php echo MINE_AMOUNT; ?></strong> m3 / cycle / module
Cycle Time: <strong><?php echo CYCLE_TIME; ?></strong> seconds / module
# of GH's:  <strong><?php echo LEVEL; ?></strong> (tech <strong><?php echo (MINE_AMOUNT == 10 ? '1':'2') ?>)</strong> harvesters</pre>
    </pre>
</div>
<?php } ?>