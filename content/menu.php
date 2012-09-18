<?php
if(!isset($onserver)) { die('Restricted access'); }
?>

<?php if($page=='home'): ?>
<a href="javascript:" id="tut_start" title="Start Tutorial: The Constructor">Tutorial</a>
<?php else: ?>
<a href="/" title="Back to The Constructor">Home</a>
<?php endif; ?>

<a href="/about" id="why" title="When and why should I use The Constructor?">What is this?</a>
<a href="/contact" title="Contact the Wageningen iGEM Team">Contact</a>