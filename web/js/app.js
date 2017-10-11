/**
 * Created by notte on 22/05/2016.
 */
function convertToSlug(str) {
	str      = str.replace(/^\s+|\s+$/g, ''); // trim
	str      = str.toLowerCase();
	var from = "äàáạảãâầấậẩẫăằắặẳẵëèéẹẻẽêềếệểễïîìíịỉĩöòóọỏõôồốộổỗơờớợởỡüûùúụủũưừứựửữỳýỵỷỹđñç·/_,:;";
	var to   = "aaaaaaaaaaaaaaaaaaeeeeeeeeeeeeiiiiiiioooooooooooooooooouuuuuuuuuuuuuyyyyydnc------";
	for(var i = 0, l = from.length; i < l; i++) {
		str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
	}
	str = str.replace(/[^a-z0-9 -]/g, '')
		.replace(/\s+/g, '-')
		.replace(/-+/g, '-');
	return str;
}
function capitalise(sentences) {
	var tokens = sentences.split(" ").filter(function(t) {
		return t != "";
	});
	var res    = [],
		i,
		len,
		component;

	for(i = 0, len = tokens.length; i < len; i++) {
		component = tokens[i];
		res.push(component.substring(0, 1).toUpperCase());
		res.push(component.substring(1));
		res.push(" ");
	}
	return res.join("");
}
