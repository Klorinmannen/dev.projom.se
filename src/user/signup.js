function set_password(elem) {
    var pass_input = document.getElementById('password-input');
    if (pass_input.type === 'password') {
	pass_input.type = 'text';
	elem.value = 'visibility';
    } else {
	pass_input.type = 'password';
	elem.value = 'visibility_off';
    }
}
