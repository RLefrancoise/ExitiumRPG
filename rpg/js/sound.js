function play_sound(sound_id, sound_path) {
	
	soundManager.createSound({
		id: sound_id,
		url: sound_path
	});
	
	soundManager.play(sound_id);
}

function play_bgm(bgm_id, bgm_path) {
	var s = soundManager.createSound({
		id: bgm_id,
		url: bgm_path
	});
	
	loop_sound(s);
	
	return s;
}

function loop_sound(sound) {
	sound.play({
    onfinish: function() {
      loop_sound(sound);
    }
  });
}

function stop_sound(sound_id) {
	soundManager.stop(sound_id);
}