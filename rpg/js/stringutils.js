function string_starts_with(string, start) {
	var reg = new RegExp(eval("/^" + start + "/"));
	return reg.test(string);
}