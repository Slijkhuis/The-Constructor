<?php
if(!isset($onserver)) { die('Restricted access'); }
?>
<div id="the_constructor_tutorial">
	
	<div id="tut_title">
		<h1>Tutorial</h1>
		<a href="javascript:" id="tut_stop" title="Stop tutorial">Stop tutorial</a>
	</div>
	
	<div id="tutsteps">
		<div class="tutstep" id="tutstep1">
			<h4>Step 1: E-mail address</h4>
			<p>Enter an e-mail address. This address will be used to send the results too.</p>
		</div>
		
		<div class="tutstep" id="tutstep2">
			<h4>Step 2: Entering bricks / TUs</h4>
			<p>
				Now it is time to fill in the Transcription Units (TUs) of your system. To locate the TU's in your systems you need to search for the promoters, each time a new promoter is found you start with a new TU. The following system consists out two TU's.
				<br><br>
				<b>System 1:</b> BBa_R0062 BBa_B0034 BBa_E1010 BBa_B0010 BBa_B0012 BBa_R0062 BBa_B0034 BBa_C0062 BBa_B0034 BBa_C0061 BBa_B0010 BBa_B0012
				<br><br>
				<b>TU1:</b>  BBa_R0062 BBa_B0034 BBa_E1010 BBa_B0010 BBa_B0012
				<br><br>
				<b>TU2:</b> BBa_R0062 BBa_B0034 BBa_C0062 BBa_B0034 BBa_C0061 BBa_B0010 BBa_B0012
				<br><br>
				Make sure the brick names are as following:
				<ul>
					<li>First BBa_ followed by the brick ID, example: BBa_C0062.</li>
					<li>Secondly, the parts are separated by 1 space bar. Example: BBa_R0063 BBa_B0034.</li>
					<li>Thirdly, if you wish to include a brick that is not yet part of the Registry (e.g. a brick that is to be developed still), use BBa_X as brick name and we try our best to clone around it.</li>
					<li>Operonic TUs such as example TU2 are not limited in the number of RBS-CDS pairs between the promoter and terminator.</li>
				</ul>
			<p>Click <a href="javascript:" id="tutloadsamplebricks" title="Load sample data">here</a> to load sample bricks.</p>
			<p>Click <a href="javascript:" id="tutloadsamplebricks2" title="Load sample data">here</a> to load a BBa_X example.</p>
		</div>
		
		<div class="tutstep" id="tutstep3">
			<h4>Step 3: Part Results</h4>
			<p>Multiple boxes can be selected.<p>
			<ul>
				<li>Works: Parts have been tested and confirmed.</li>
				<li>Issues: Parts have been tested but the outcome was not as expected.</li>
				<li>Fails: The parts have been tested but did not manage the desired results.</li>
				<li>None: No information was present about the state of the BioBrick.</li>
			</ul>
		</div>
		
		<div class="tutstep" id="tutstep4">
			<h4>Step 4: Part Quality</h4>
			<p>Selection is based upon the preferred quality of the biobrick.</p>
			<ul>
				<li>Confirmed: Parts have been tested and confirmed.</li>
				<li>Partially confirmed: The biobrick partially works.</li>
				<li>Questionable: The biobrick did not pass all quality checks.</li>
				<li>Long part: The part was too long to be properly sequenced.</li>
				<li>Bad sequencing: Sequencing quality of the biobrick was low.</li>
				<li>None: No information was present about the state of the BioBrick.</li>
			</ul>
		</div>
		
		<div class="tutstep" id="tutstep5">
			<h4>Step 5: Part Status</h4>
			<p><!--Selection changes the parts that are used for the construction of your construct. -->Available is the adviced option to use.</p>
		</div>
		
		<div class="tutstep" id="tutstep6">
			<h4>Step 6: Run</h4>
			<p>If everything is set up... Then you are ready to go!</p>
		</div>
		
		<a href="javascript:" id="tut_prev" title="Previous step">Previous Step</a>
		<a href="javascript:" id="tut_next" title="Next step">Next Step</a>
		
	</div>
	
</div>
