TGW = (function() {
	"use strict";

	function draw_chord(chord_view) {
		// extract chord data
		var ascii = $(chord_view).text();
		var chord_data = ascii.trim().split('\n');
		chord_data.forEach(function(item, index, array) {
			array[index] = item.trim().replace('||', '|');
		});
		var needs_padding = chord_data.filter(function(string) { return string[0] == 'X' || string[0] == '0'; }).length > 0;

		// extract head fret number
		var roman_to_indian = { 0: 0, 'I': 1, 'II': 2, 'III': 3, 'IV': 4, 'V': 5, 'VI': 6, 'VII': 7, 'VIII': 8, 'IX': 9, 'X': 10, 'XI': 11, 'XII': 12, 'XIII': 13, 'XIV': 14, 'XV': 15, 'XVI': 16, 'XVII': 17, 'XVIII': 18, 'XIX': 19, 'XX': 20, 'XXI': 21, 'XXII': 22, 'XXIII': 23, 'XXIV': 24 };
		var head_fret_R = chord_data.length == 7 ? chord_data[6] : 0;
		var head_fret = roman_to_indian[head_fret_R];

		var finger_font_size = 22;
		var head_fret_font_size = 20;
		var padding_w = needs_padding ? 60 : 0;
		var padding_h = 40;
		var key_w = 80;
		var key_h = 40;
		var canvas_w = key_w * 5 + padding_w * 1;
		var canvas_h = key_h * 5 + padding_h * 2;
		var neck_w = key_w * 5;
		var neck_h = key_h * 5;

		var canvas = document.createElement('canvas');
		// canvas.style.border="1px solid gray";
		canvas.width=canvas_w;
		canvas.height=canvas_h;
		$(chord_view).html('');
		chord_view.appendChild(canvas);

		var ctx = canvas.getContext("2d");

		function draw_guitar_neck() {

			// draw background
			ctx.fillStyle = "#EEEEEE";
			ctx.strokeStyle="#888888";
			ctx.lineWidth="1";
			ctx.fillRect(0, 0, canvas_w, canvas_h);
			ctx.strokeRect(0.5, 0.5, canvas_w-1, canvas_h-1);
			
			// draw guitar neck
			ctx.fillStyle = "#EEEEAA";
			ctx.fillRect(padding_w, padding_h, neck_w, neck_h);
			ctx.strokeRect(padding_w+0.5, padding_h+0.5, neck_w-1, neck_h-1);
			
			// draw frets
			ctx.strokeStyle="#222222";
			ctx.lineWidth="2";
			ctx.lineCap="round";
			var x = padding_w;
			for(var i=0; i<5; ++i) {
				ctx.beginPath();
				ctx.moveTo(x, padding_h);
				ctx.lineTo(x, padding_h + neck_h);
				ctx.stroke();
				x += neck_w/5;
			}

			// draw strings
			ctx.strokeStyle="#888888";
			ctx.lineWidth="2";
			var y = padding_h;
			for(var i=0; i<6; ++i) {
				ctx.beginPath();
				ctx.moveTo(padding_w, y);
				ctx.lineTo(canvas_w, y);
				ctx.stroke();
				y += neck_h/5;
			}

			// draw nut
			if (head_fret <= 1) {
				ctx.strokeStyle="#222222";
				ctx.lineWidth="8";
				ctx.lineCap="round";
				ctx.beginPath();
				ctx.moveTo(padding_w, padding_h);
				ctx.lineTo(padding_w, padding_h + neck_h);
				ctx.stroke();
			}
		}

		ctx.font = finger_font_size + "px Arial";
		var metrics = ctx.measureText('X');

		function draw_round_number(N, x, y) {
			ctx.font = finger_font_size + "px Arial";
			// circle
			ctx.strokeStyle="#888888";
			ctx.fillStyle="#DDDDDD";
			ctx.lineWidth="2";
			ctx.beginPath();
			ctx.arc(x, y, metrics.width, 0, 2*Math.PI);
			ctx.fill();
			ctx.stroke();
			// text
			ctx.fillStyle="#222222";
			ctx.fillText(N, x, y);
		}

		function draw_round_number2(N, x, y) {
			ctx.font = finger_font_size + "px Arial";
			// circle
			ctx.strokeStyle="#888888";
			ctx.fillStyle="#DDDDDD";
			ctx.lineWidth="2";
			ctx.beginPath();
			ctx.arc(x, y, metrics.width, 0, 2*Math.PI);
			ctx.fill();
			// ctx.stroke();
			// text
			ctx.fillStyle="#222222";
			ctx.fillText(N, x, y);
		}

		function draw_chord_data(chord_data) {

			// draw the guitar neck
			draw_guitar_neck(head_fret);

			// Draw actual chord
			ctx.strokeStyle="#000000";
			ctx.fillStyle="#000000";
			ctx.textAlign="center"
			ctx.textBaseline="middle"
			chord_data.forEach(function(string, index) {
				if (index == 6) {
					ctx.font = head_fret_font_size + "px Verdana";
					// fret number
					ctx.fillText(head_fret_R, padding_w + key_w/2, padding_h + key_h*index - key_h/3);
				} else {
					if (string[0] == 'X') {
						// exclude string
						draw_round_number2("X", padding_w - metrics.width*1.5, padding_h + key_h*index);
					} else
					if (string[0] == '0') {
						// empty string
						draw_round_number2("0", padding_w - metrics.width*1.5, padding_h + key_h*index);
					} else {
						// finger string
						var fret_number = -1;
						for(var i=0; i<string.length; ++i) {
							if (string[i] == '|') {
								++fret_number;
							} else 
							if (string[i].match(/\d/)) {
								draw_round_number(string[i], padding_w + fret_number*key_w + key_w/2, padding_h + key_h*index)
							}
						}
					}
				}
			});
		}

		// draw the actual chord
		draw_chord_data(chord_data);
	}

	function draw_scale(scale_view) {
		// extract chord data
		var ascii = $(scale_view).text();
		var chord_data = ascii.trim().split('\n');
		chord_data.forEach(function(item, index, array) {
			array[index] = item.trim().replace(/^\|/, '').replace('||', '|');
		});
		console.log(chord_data);

		var finger_font_size = 18;
		var padding_w = 60;
		var padding_h = 40;
		var key_w = 60;
		var key_h = 30;
		var canvas_w = key_w * 17 + padding_w * 1;
		var canvas_h = key_h * 5 + padding_h * 2;
		var neck_w = key_w * 17;
		var neck_h = key_h * 5;

		var canvas = document.createElement('canvas');
		// canvas.style.border="1px solid gray";
		canvas.width=canvas_w;
		canvas.height=canvas_h;
		// $(scale_view).html('');
		scale_view.appendChild(canvas);

		var ctx = canvas.getContext("2d");

		function draw_guitar_neck(head_fret) {

			// draw background
			ctx.fillStyle = "#EEEEEE";
			ctx.strokeStyle="#888888";
			ctx.lineWidth="1";
			ctx.fillRect(0, 0, canvas_w, canvas_h);
			ctx.strokeRect(0.5, 0.5, canvas_w-1, canvas_h-1);
			
			// draw guitar neck
			ctx.fillStyle = "#EEEEAA";
			ctx.fillRect(padding_w, padding_h, neck_w, neck_h);
			ctx.strokeRect(padding_w+0.5, padding_h+0.5, neck_w-1, neck_h-1);
			
			// draw frets
			ctx.strokeStyle="#222222";
			ctx.lineWidth="2";
			ctx.lineCap="round";
			var x = padding_w;
			for(var i=0; i<17; ++i) {
				ctx.beginPath();
				ctx.moveTo(x, padding_h);
				ctx.lineTo(x, padding_h + neck_h);
				ctx.stroke();
				x += neck_w/17;
			}

			// draw strings
			ctx.strokeStyle="#888888";
			ctx.lineWidth="2";
			var y = padding_h;
			for(var i=0; i<6; ++i) {
				ctx.beginPath();
				ctx.moveTo(padding_w, y);
				ctx.lineTo(canvas_w, y);
				ctx.stroke();
				y += neck_h/5;
			}

			// draw nut
			ctx.strokeStyle="#222222";
			ctx.lineWidth="8";
			ctx.lineCap="round";
			ctx.beginPath();
			ctx.moveTo(padding_w, padding_h);
			ctx.lineTo(padding_w, padding_h + neck_h);
			ctx.stroke();
		}

		ctx.font = finger_font_size + "px Arial";
		var metrics = ctx.measureText('X');

		function draw_round_number(N, x, y, circle_fill, circle_stroke, text_fill) {
			ctx.font = finger_font_size + "px Arial";
			// circle
			ctx.strokeStyle=circle_stroke;
			ctx.fillStyle=circle_fill;
			ctx.lineWidth="2";
			ctx.beginPath();
			ctx.arc(x, y, metrics.width, 0, 2*Math.PI);
			ctx.fill();
			ctx.stroke();
			// text
			ctx.fillStyle=text_fill;
			ctx.fillText(N, x, y);
		}

		function draw_scale_data(chord_data) {

			// draw the guitar neck
			draw_guitar_neck();

			// Draw actual chord
			ctx.strokeStyle="#000000";
			ctx.fillStyle="#000000";
			ctx.textAlign="center"
			ctx.textBaseline="middle"
			chord_data.forEach(function(string, string_index) {
				var frets = string.replace(/-/g, '').split('|');
				console.log(frets);
				frets.forEach(function(fret_value, fret_number) {
					if (fret_value.match(/\dr/)) {
						draw_round_number(fret_value[0], padding_w + fret_number*key_w + key_w/2, padding_h + key_h*string_index, "#FF0000", "#888888", "#222222");
					} else
					if (fret_value.match(/\db/)) {
						draw_round_number(fret_value[0], padding_w + fret_number*key_w + key_w/2, padding_h + key_h*string_index, "#0000FF", "#888888", "#222222");
					} else
					if (fret_value.match(/\d/)) {
						draw_round_number(fret_value[0], padding_w + fret_number*key_w + key_w/2, padding_h + key_h*string_index, "#DDDDDD", "#AAAAAA", "#AAAAAA");
					} else
					if (fret_value.match(/or/)) {
						draw_round_number('', padding_w + fret_number*key_w + key_w/2, padding_h + key_h*string_index, "#000000", "#888888", "#222222");
					} else
					if (fret_value.match(/o/)) {
						draw_round_number('', padding_w + fret_number*key_w + key_w/2, padding_h + key_h*string_index, "#DDDDDD", "#888888", "#222222");
					}
				});
				// // finger string
				// var fret_number = -1;
				// for(var i=0; i<string.length; ++i) {
				// 	if (string[i] == '|') {
				// 		++fret_number;
				// 	} else 
				// 	if (string[i].match(/\dr/)) {
				// 		draw_round_number(string[i], padding_w + fret_number*key_w + key_w/2, padding_h + key_h*index, "#FF0000");
				// 	} else
				// 	if (string[i].match(/\db/)) {
				// 		draw_round_number(string[i], padding_w + fret_number*key_w + key_w/2, padding_h + key_h*index, "#0000FF");
				// 	} else
				// 	if (string[i].match(/\d/)) {
				// 		draw_round_number(string[i], padding_w + fret_number*key_w + key_w/2, padding_h + key_h*index, "#DDDDDD");
				// 	} else
				// 	if (string[i].match(/or/)) {
				// 		draw_round_number('', padding_w + fret_number*key_w + key_w/2, padding_h + key_h*index, "#DDDDDD");
				// 	} else
				// 	if (string[i].match(/o/)) {
				// 		draw_round_number('', padding_w + fret_number*key_w + key_w/2, padding_h + key_h*index, "#DDDDDD");
				// 	} else {
						
				// 	}
				// }
			});
		}

		// draw the actual chord
		draw_scale_data(chord_data);
	}

	return {
		draw_chord,
		draw_scale
	}
})();

$(".chord-view").each(function() {
	TGW.draw_chord(this);
});

$(".scale-view").each(function() {
	TGW.draw_scale(this);
});
