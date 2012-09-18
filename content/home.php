<?php
if(!isset($onserver)) { die('Restricted access'); }
?>

<div class="center">
<h1>The Constructor</h1>
	<p>Optimizing cloning strategies since 2011<p>
	<p>Further simplifying this procedure since 2012<p>
</div>

<form name="constructorform" id="constructorform" action="index.html" method="POST">
	
	<fieldset>
		<div class="email">
			<h3><span id="tc_help0" class="tc_help">E-mail (optional)</span></h3>
			<label>E-mail: <input type="text" name="email" id="tc_email" /></label>
		</div>
		
		<div class="fgm">
			<h3><span id="tc_help1" class="tc_help">Transcription Units (TUs)</span></h3>
			<div id="rfc_caution">
				Caution: check RFC compatibility!
				<div id="rfc_caution_hover">
					<p>The Constructor optimizes cloning strategies with Biobricks conform to assembly standard 10, 23 and 25. These standards are compatible with each other and represent the bulk of all available BioBricks. However if you wish to use BioBricks conform to other assembly standards you can still try to optimize your cloning strategy with the constructor since it might be that BioBricks are converted and implemented in superparts that are conform assembly standard 10, 23 or 25. The constructor will give notice if a part isn't available in a superpart with the correct assembly standard. If this is the case it might be worth to redesign your genetic circuit with assembly standard 10, 23 and 25 compatible BioBricks, since this can drastically reduce the number of cloning steps for your genetic circuit.</p>
				</div>
			</div>
			<label>TU 1: <input type="text" name="fgm[]" id="fgm1" /></label>
			<label>TU 2: <input type="text" name="fgm[]" id="fgm2" /></label>
			<label class="hidden">TU 3: <input type="text" name="fgm[]" id="fgm3" /></label>
			<label class="hidden">TU 4: <input type="text" name="fgm[]" id="fgm4" /></label>
			<label class="hidden">TU 5: <input type="text" name="fgm[]" id="fgm5" /></label>
			<label class="hidden">TU 6: <input type="text" name="fgm[]" id="fgm6" /></label>
			<label class="hidden">TU 7: <input type="text" name="fgm[]" id="fgm7" /></label>
			
			<br />
			
			<div class="fgm_controls">
				<div class="fl">
					<a href="javascript:" title="Less" id="removefgm">Less</a>
				</div>
				<div class="fr">
					<a href="javascript:" title="More" id="addfgm">More</a>
				</div>
			</div>
		</div>
		
		<div class="tc_options">
			
			<div class="tc_col fl">
				<h3><span id="tc_help2" class="tc_help">Part Results</span></h3>
				<label><input type="checkbox" name="options1[]" id="options1_1" value="Works" checked /> Works</label>
				<label><input type="checkbox" name="options1[]" id="options1_2" value="Issues" /> Issues</label>
				<label><input type="checkbox" name="options1[]" id="options1_3" value="Fails" /> Fails</label>
				<label><input type="checkbox" name="options1[]" id="options1_4" value="None" /> None</label>
				
				<h3><span id="tc_help3" class="tc_help">Part Status</span></h3>
				<label><input type="checkbox" name="options2[]" id="options2_1" value="Available" checked /> Available</label>
				<label><input type="checkbox" name="options2[]" id="options2_2" value="Deleted" /> Deleted</label>
				<label><input type="checkbox" name="options2[]" id="options2_3" value="Informational" /> Informational</label>
				<label><input type="checkbox" name="options2[]" id="options2_4" value="Missing" /> Missing</label>
				<label><input type="checkbox" name="options2[]" id="options2_5" value="Planning" /> Planning</label>
				<label><input type="checkbox" name="options2[]" id="options2_6" value="Unavailable" /> Unavailable</label>
			</div>
			
			<div class="tc_col fr">
				<h3><span id="tc_help4" class="tc_help">Best Quality</span></h3>
				<label><input type="checkbox" name="options3[]" id="options3_1" value="Confirmed" checked /> Confirmed</label>
				<label><input type="checkbox" name="options3[]" id="options3_2" value="Partially_Confirmed" /> Partially Confirmed</label>
				<label><input type="checkbox" name="options3[]" id="options3_3" value="Questionable" /> Questionable</label>
				<label><input type="checkbox" name="options3[]" id="options3_4" value="Long_Part" /> Long Part</label>
				<label><input type="checkbox" name="options3[]" id="options3_5" value="Bad_Sequencing" /> Bad Sequencing</label>
				<label><input type="checkbox" name="options3[]" id="options3_6" value="None" /> None</label>
			</div>
			
		</div>
		
		
		<div class="run">
			
			<h3><span id="tc_help5" class="tc_help">Construct</span></h3>
			
			<input type="hidden" value="3" name="trials" id="trials" />
			<button id="tc_submit">Go</button>
			
			<pre id='tc_output'>
			</pre>
		</div>
		
	</fieldset>
	
	<div id="help_popup"></div>
	
	<div class="help hidden">
		<div id="tc_help0_text">
			<p>The results will appear on the screen after a while and will be e-mailed to you if you provide an e-mail address here.</p>
		</div>
		<div id="tc_help1_text">
			<p>Transcription Unit (TU). A composition of parts that belongs together in this exact order to fulfill a biological function.</p>
		</div>
		<div id="tc_help2_text">
			<p>Multiple boxes can be selected.<p>
			<ul>
				<li>Works: Part functions as designed.</li>
				<li>Issues: Part didn't function as designed.</li>
				<li>Fails: Part didn't function at all.</li>
				<li>None: Parts functions isn't verified.</li>
			</ul>
		</div>
		<div id="tc_help3_text">
			<p>Part status: For this status available is advised since this designates that the part is available from the registry. However, if you are willing to synthesize a part the others option should be ticked.</p>
		</div>
		<div id="tc_help4_text">
			<p>Selection is based upon the preferred quality of the biobrick. 
				<li>Confirmed: Part passed all quality checks.</li>
				<li>Partially confirmed: Part passed all except one of the quality checks.</li>
				<li>Questionable: Results of one of the quality checks is questionable.</li>
				<li>Long part: Part is too long to be fully sequenced.</li>
				<li>Bad sequencing: Sequencing quality of the brick was to low.</li>
				<li>None: part hasn't been quality checked.</li>

</p>
		</div>
		<div id="tc_help5_text">
			<p>If everything is set up... Then you are ready to go!</p>
		</div>

	</div>
	
</form>
