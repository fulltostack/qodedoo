<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div id="colorful" class="maxheight500">
<section style="width: auto;max-width: 41.563em;margin: .5em 0 0 30em;padding: .5em 0;" class="controls">
	<a href="javascript::" title="Toggle css code" class="button button-small icon toggle-code-box"><i class="icon" style="position: absolute;top: 10px;left: -14px;font-size: 25px;">V</i></a>
	<a href="javascript::" title="Randomize the background colors" style="height: 150px!important;" class="button icon randomize"><i class="icon" style="position: absolute;top: 35px;">R</i></a>
	<a href="javascript::" title="Toggle settings" class="button button-small icon toggle-settings-box"><i class="icon" style="position: absolute;top: 13px;left: -10px;font-size: 20px;">S</i></a>
</section>
<section style="width: auto;max-width: 41.563em;margin: auto auto;padding: 4.2em 0;" class="settings">
	<p style="color: white;text-align: center;">Change hue, saturation and lightness for each layer.</p>
	<div class="box">
		<ul>
			<li><b>Layer 4:</b> hue <input id="layer-3-hue" type="range" min="0" max="359" step="1">
				saturation <input id="layer-3-saturation" type="range" min="0" max="100" step="1">
				lightness <input id="layer-3-lightness" type="range" min="0" max="100" step="1">
			</li>
			<li><b>Layer 3:</b> hue <input id="layer-2-hue" type="range" min="0" max="359" step="1"> 
				saturation <input id="layer-2-saturation" type="range" min="0" max="100" step="1"> 
				lightness <input id="layer-2-lightness" type="range" min="0" max="100" step="1">
			</li>
			<li><b>Layer 2:</b> hue <input id="layer-1-hue" type="range" min="0" max="359" step="1"> 
				saturation <input id="layer-1-saturation" type="range" min="0" max="100" step="1"> 
				lightness <input id="layer-1-lightness" type="range" min="0" max="100" step="1">
			</li>
			<li><b>Layer 1:</b> hue <input id="layer-0-hue" type="range" min="0" max="359" step="1"> 
				saturation <input id="layer-0-saturation" type="range" min="0" max="100" step="1"> 
				lightness <input id="layer-0-lightness" type="range" min="0" max="100" step="1">
			</li>
		</ul>
	</div>
</section>
<section style="width: auto;max-width: 41.563em;margin: auto auto;padding: 2.4em 0px;display: block;" class="code">
<p style="color: white;text-align: center;">This is your CSS Code.</p>
	<div class="box">
		<textarea id="generated-css-code" name="nullbgv" wrap="off"></textarea>
	</div>
</section>

<script src="//code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<script src="<?php echo plugins_url( 'js/script.js', dirname(__FILE__) ); ?>"></script>
<script src="<?php echo plugins_url( 'js/colorful-background-css-generator.min.js', dirname(__FILE__) ); ?>"></script>
</div>