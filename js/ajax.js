$(document).ready(function() {
	
	// Aantal FGM inputs zichtbaar aan het begin.
	var fgm = 2;
	
	// Set amount of trials
	var max_trials = 2;
	$('#trials').val(max_trials);
	
	// Deze functie zet het menu op de goede plek.
	// setMenuLeft(); // Staat nu even uit omdat ik het met CSS ga proberen te doen.
	
	// Actie voor de knop om een FGM input weg te halen.
	$('#removefgm').click(function(){
		if(fgm>1) {
			$('#fgm'+fgm).parent().addClass('hidden');
			$('#fgm'+fgm).val('');
			fgm--;
		} else {
			alert('This program is only usefull for 1 or more TUs.');
		}
	});
	
	// Actie voor de knop om een FGM input toe te voegen.
	$('#addfgm').click(function(){
		if(fgm<7) {
			fgm++;
			$('#fgm'+fgm).parent().removeClass('hidden');
		} else {
			alert('Currently, we do not support more than 7 TUs. Please contact the Wageningen UR iGEM team to get the results for more than 7 TUs.');
		}
	});
	
	// FGM valideren
	$('.fgm input').keyup(function() {
		$(this).parent().removeClass('error');
		
		if($(this).val()!='') {
			var bricks = $(this).val().split(' ');
			var error = false;
			
			for(i in bricks) {
				
				var brick = bricks[i];
				
				if(brick.length < 5) {
					error = true;
					//$('title').html(brick + '<5');
					break;
				} else {
					if(brick.substr(0,4)!="BBa_") {
						error = true;
						//$('title').html(brick + '!=BBa_' + ' ' + brick.substr(1,4));
						break;
					}
				}
			}
			
			if(error) { $(this).parent().addClass('error'); }
		}
	});
	
	
	// Het div'je voor de hulp teksten moet met de muis meegaan
	$(document).mousemove(function(event){
		$('#help_popup').css({
			'top': (event.pageY+10-$('#the_constructor_webapp').position().top) + 'px',
			'left': (event.pageX-125-$('#the_constructor_webapp').offset().left) + 'px'
		});
	});
	
	// Bij het hoveren over een titel van een sectie van The Constructor moet de
	// hulptekst zichtbaar worden. Bij het weggaan met je muis wordt die weer onzichtbaar.
	$('.tc_help').hover(function(){
		$('#help_popup').html($('#' + $(this).attr('id') + '_text').html());
		$('#help_popup').show();
	},function(){
		$('#help_popup').hide();
	});
	
	// De actie voor de 'Go'-knop
	$('#tc_submit').click(function(e){
		// Disable Go-button while program is executing
		$(this).attr('disabled','disabled');
		
		// Temporarily put the output on 'Executing query'
		$('#tc_output').html('Executing query, please wait.');
		
		// Prevent the form-submit reloading the page
		e.preventDefault();
		
		$.ajax({
			type: 'POST',
			url: 'theconstructor.php?rs='+Math.floor(Math.random()*10001),
			data: $('#constructorform').serialize(),
			success: function (data) {
				if(data=='0') {
					$('#tc_output').html('Executing failed, trying again, please wait.');
					// Update the trial-counter
					$('#trials').val($('#trials').val()-1);
					// Try again
					setTimeout(function(){$('#tc_submit').click();},4000);
					return 1;
				} else {
					$('#tc_output').html(data);
					$('#tc_submit').removeAttr('disabled');
					// Reset the number of trials
					$('#trials').val(max_trials);
					return 1;
				}
			}
		});
	});
	
	
	// De tutorial
	
	// Huidige stap
	var tutstep = 1;
	var mintutstep = 1;
	var maxtutstep = 6;
	var offsetIDs = {
		1: '#tc_help0',
		2: '#tc_help1',
		3: '#tc_help2',
		5: '#tc_help3',
		4: '#tc_help4',
		6: '#tc_help5'
	};
	
	// Start tutorial
	$('#tut_start').click(function(){
		$('#the_constructor_webapp, #the_constructor_links').css('float', 'left');
		//setMenuLeft();
		$('#the_constructor_tutorial').show();
		$('#the_constructor_tutorial').css('height', $('#the_constructor_webapp').height()+'px');
		gotoStep(tutstep);
	});
	
	// Stop tutorial
	$('#tut_stop').click(function(){
		$('#the_constructor_tutorial').hide();
		$('#the_constructor_webapp, #the_constructor_links').css('float', 'none');
		//setMenuLeft();
	});
	
	// Berekenen waar het menu moet staan
	function setMenuLeft() {
		
		l1 = $('#the_constructor_webapp').offset().left;
		w1 = $('#the_constructor_webapp').width();
		
		$('#the_constructor_links').css('float', 'left');
		w2 = $('#the_constructor_links').width();
		$('#the_constructor_links').css('float', 'none');
		
		w3 = $('.tc_wrapper').width();
		
		l2 = Math.round(l1 + w1/8 - w2/2);
		
		$('#the_constructor_links').css({'padding-left': l2+'px'});
	}
	
	// Go to step x
	function gotoStep(step) {
		$('.tutstep').hide();
		$('#tutstep'+step).show();
		var pos = $(offsetIDs[step]).position();
		$('#tutsteps').animate({'top': (pos.top-20) + 'px'});
		
		// Show only the buttons that are applicable to the current situation.
		$('#tut_prev').show();
		$('#tut_next').show();
		if(tutstep==mintutstep) { $('#tut_prev').hide(); }
		if(tutstep==maxtutstep) { $('#tut_next').hide(); }
	}
	
	// Next step
	$('#tut_next').click(function(){
		if(tutstep<maxtutstep) { tutstep = tutstep + 1; }
		gotoStep(tutstep);
	});
	
	// Previous step
	$('#tut_prev').click(function(){
		if(tutstep>mintutstep) { tutstep = tutstep - 1; }
		gotoStep(tutstep);
		
	});
	
	// Load sample bricks
	$('#tutloadsamplebricks').click(function(){
		//$('#fgm1').val('BBa_R0063 BBa_B0034 BBa_C0062 BBa_B0010 BBa_B0012');
		$('#fgm1').val('BBa_R0062 BBa_B0034 BBa_E1010 BBa_B0010 BBa_B0012');
		$('#fgm2').val('BBa_R0062 BBa_B0034 BBa_C0062 BBa_B0034 BBa_C0061 BBa_B0010 BBa_B0012');
		$('#options1_4').attr('checked','checked');
	});
		// Load sample bricks with BBa_X
	$('#tutloadsamplebricks2').click(function(){
		$('#fgm1').val('BBa_R0063 BBa_X BBa_B0034 BBa_C0062 BBa_B0010 BBa_B0012');
		$('#options1_4').attr('checked','checked');
	});

});
