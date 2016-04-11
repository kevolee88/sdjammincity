var Noise = Noise || {};

Noise.Spectrum = ( function( $ )
{
	// Create the audio context
	var context, fillColor = '#fff';
	window.AudioContext = window.AudioContext||window.webkitAudioContext;

	if ( typeof webkitAudioContext == 'undefined' )
	{
		return {
			settings: {},
			init: function(){}
		};
	}

	context = new AudioContext();

	// Setup an analyzer
	var analyser = context.createAnalyser();
	analyser.smoothingTimeConstant = 0.3;
	analyser.fftSize = 512;
	analyser.connect( context.destination );

	// Setup a Javascript node
	var javascriptNode = context.createScriptProcessor( 2048, 1, 1 );
	javascriptNode.connect( context.destination );

	// Draw variables
	var canvas, canvasContext,
		barWidth = 10,
		barSpacing = 2;

	// Draw the spectrum
	function update()
	{
		// get the average for the first channel
		var data = new Uint8Array( analyser.frequencyBinCount );
		analyser.getByteFrequencyData( data );

		// clear the current state
		canvasContext.clearRect( 0, 0, canvas.width, canvas.height );

		// set the fill style
		// canvasContext.fillStyle = '#fff';
		canvasContext.fillStyle = fillColor;

		var numBars = Math.floor( canvas.width / barWidth ),
			binSize = Math.floor( data.length / numBars ),
			sum, i, j, average, scale;
		for ( i = 0; i < numBars; ++i )
		{
			sum = 0;
			for ( j = 0; j < binSize; ++j )
			{
				sum += data[( i * binSize ) + j];
			}

			// Calculate the average frequency of the samples in the bin
			average = sum / binSize;

			// Draw the bars on the canvas
			scale = ( average / 256 ) * canvas.height;
			canvasContext.fillRect( i * barWidth, canvas.height, barWidth - barSpacing, -scale );
		}
	}

	// Returned object
	var spectrum = {
		settings: {
			canvas: 'visualization',
			audio: 'extended-player',
			fill: fillColor
		},
		init: function( settings )
		{
			if ( typeof window.webkitAudioContext === 'undefined' )
				return;
			var settings = $.extend( {}, spectrum.settings, settings );
			var source = context.createMediaElementSource( document.getElementById( settings.audio ) );
			source.connect( analyser );

			// Get the canvas and canvas context
			canvas = document.getElementById( settings.canvas );
			canvasContext = canvas.getContext( '2d' );

			// Display spectrum
			fillColor = settings.fill;
			javascriptNode.onaudioprocess = update;
		}
	};

	return spectrum;
}( jQuery ) );
