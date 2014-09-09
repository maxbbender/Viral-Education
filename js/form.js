function formhash(form, password) {
    //Create new element input this will be hashed password field
    var p = document.createElement("input");

    //Add the new element to our form.
    form.appendChild(p);
    p.name = "p";
    p.type = "hidden";
    p.value = hex_sha512(password.value);

    //Make password's value plain text
    password.value = "";

    //Submit form
    form.submit();
}

function regformhash(form, uid, email, password, conf) {
    //Make sure each field has a value
    if (uid.value == '' ||
        email.value == '' ||
        password.value == '' ||
        conf.value == '') {

        alert('Not all fields are filled out');
        return false;
    }
    //Check the username
    form.appendChild(p);
    p.name = "p";
    p.type = "hidden";
    p.value = hex_sha512(password.value);

    // Make sure plaintext password doesn't get sent
    password.value = "";
    conf.value = "";

    //Submit the form
    form.submit();
    return true;