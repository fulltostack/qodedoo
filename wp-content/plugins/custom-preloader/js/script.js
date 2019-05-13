/*! 
The MIT License (MIT) 
 
Copyright (c) 2015 webcore-it @WebCoreIT
 
Permission is hereby granted, free of charge, to any person obtaining a copy 
of this software and associated documentation files (the "Software"), to deal 
in the Software without restriction, including without limitation the rights 
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell 
copies of the Software, and to permit persons to whom the Software is 
furnished to do so, subject to the following conditions: 
 
The above copyright notice and this permission notice shall be included in all 
copies or substantial portions of the Software. 
 
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE 
SOFTWARE. 
*/ 
$(document).ready(function () {

	/* VARIABLES
	======================================================================= */

	// The Generator
	var generator = new ColorfulBackgroundGenerator();

	// Get all elements for later use.
	var eleCodeBox = $('section.code');
	var eleCodeField = $('#generated-css-code');
	var eleCodeFieldBGGC = $('#bg_gradient_code');
	var eleRandomize = $('.randomize');
	var btnToggleCodeBox = $('.toggle-code-box');
	var btnToggleSettings = $('.button.toggle-settings-box');
	var eleSettingsBox = $('section.settings');
	var eleSettingsSliders = $('section.settings input');


	/* BINDINGS
	======================================================================= */

	/**
	 * Toggle the code box.
	 */
	btnToggleCodeBox.click(function () {
		$(this).blur();
		if (!eleCodeBox.is(':visible') && eleSettingsBox.is(':visible') && $(window).height() < 850) {
			eleSettingsBox.hide();
		}
		eleCodeBox.toggle();
		return;
	});

	/**
	 * Toggle the settings box.
	 */
	btnToggleSettings.click(function () {
		$(this).blur();
		if (!eleSettingsBox.is(':visible') && eleCodeBox.is(':visible') && $(window).height() < 850) {
			eleCodeBox.hide();
		}
		eleSettingsBox.toggle();
		return;
	});

	/**
	 * Randomly change the color of the background.
	 */
	eleRandomize.click(function () {
		changeColorRandomly();
		$(this).blur();
		return;
	});

	/**
	 * Update the background and the code box content when changing the sliders
	 */
	eleSettingsSliders.on('change input', function () {
		changeColor($(this));
	});

	/**
	 * Select the code to make it easyer to copy to clipboard.
	 */
	eleCodeField.on('click', function () {
		this.select();
	});		eleCodeFieldBGGC.on('click', function () {
		this.select();
	});

	/**
	 * Catch the resize events and check the visibility of the objects.
	 */
	$(window).on('resize', function () {
		refreshVisabilityOfPageObjects();
	});


	/* FUNCTIONS
	======================================================================= */
	/**
	 * Inits the page.
	 */
	function init() {
		if ($(window).width() > 800 && $(window).height() > 800) {
			eleCodeBox.show();
		}
		// Add 4 default Layers
		var randomNumber = Math.ceil(Math.random() * 20);

		generator.addLayer(new ColorfulBackgroundLayer(315, 35, 95, 55, 100));
		generator.addLayer(new ColorfulBackgroundLayer(225, 140, 90, 50, 10, 80));
		generator.addLayer(new ColorfulBackgroundLayer(135, 225, 95, 50, 10, 80));
		generator.addLayer(new ColorfulBackgroundLayer(45, 340, 100, 55, 0, 70));

		updateWebsiteElements();
	}

	/**
	 * Check the size of the page and only show elements which fit on it.
	 */
	function refreshVisabilityOfPageObjects() {
		// codebox is hidden and settingsbox is visible and window is taller than 500px
		if (!eleCodeBox.is(':visible') && eleSettingsBox.is(':visible') && $(window).height() < 500) {
			eleSettingsBox.hide();
		}

		// settingsbox is hidden and codebox is visible and window is taller than 700px
		if (!eleSettingsBox.is(':visible') && eleCodeBox.is(':visible') && $(window).height() < 600) {
			eleCodeBox.hide();
		}

		if (eleSettingsBox.is(':visible') && eleCodeBox.is(':visible')) {
			if ($(window).height() < 850 && $(window).height() >= 500) {
				eleSettingsBox.hide();
			}
			if ($(window).height() < 500) {
				eleSettingsBox.hide();
				eleCodeBox.hide();
			}
		}
	}

	/**
	 * Walks through all layers and changes the hue, lightness and saturation randomly.
	 */
	function changeColorRandomly() {
		for (var i = generator.getNumberOfLayers() - 1; i >= 0; i--) {
			generator.getLayerByIndex(i).hue = Math.ceil(Math.random() * 359);
			generator.getLayerByIndex(i).saturation = Math.ceil(Math.random() * 10) + 90;
			generator.getLayerByIndex(i).lightness = Math.ceil(Math.random() * 10) + 40;
		}

		updateWebsiteElements();
	}

	/**
	 * Change the color of a layer.
	 *
	 * @param  {Object} element
	 * @return {Boolean}
	 */
	function changeColor(element) {
		if (element === undefined) {
			return false;
		}

		var sliderId = element.attr('id').split('-');
		if (sliderId[0] !== 'layer') {
			return false;
		}

		var layer = generator.getLayerByIndex(sliderId[1]);

		switch (sliderId[2]) {
		case 'hue':
			layer.hue = element.val();
			break;
		case 'lightness':
			layer.lightness = element.val();
			break;
		case 'saturation':
			layer.saturation = element.val();
			break;
		}

		updateWebsiteElements();
	}

	/**
	 * Updates the settings sliders, the background and the code box.
	 */
	function updateWebsiteElements() {
		for (var i = generator.getNumberOfLayers() - 1; i >= 0; i--) {
			$('#layer-' + i + '-hue').val(generator.getLayerByIndex(i).hue);
			$('#layer-' + i + '-lightness').val(generator.getLayerByIndex(i).lightness);
			$('#layer-' + i + '-saturation').val(generator.getLayerByIndex(i).saturation);
		}

		// Assign generated style to the body
		generator.assignStyleToElementId('colorful');

		// Update output code
		eleCodeField.text(generator.getCSSAsText());
	}


	// Call init script.
	init();

});