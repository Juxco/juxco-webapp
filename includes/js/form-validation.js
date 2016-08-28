function validateRegisterForm(registerForm) {
	var isValid = 0;

	isValid += validateRegisterField(registerForm, "registerEmail", "registerEmailFormGroup");
	isValid += validateRegisterField(registerForm, "registerUsername", "registerUsernameFormGroup");
	isValid += validateRegisterField(registerForm, "registerPassword", "registerPasswordFormGroup");

	if (isValid === 0) {
		return true;
	} else {
		return false;
	}
}

// Returns a 0 if validated field is successful, 1 if otherwise
function validateRegisterField(registerForm, registerFieldId, registerFormGroupId) {
	var registerFieldValue = registerForm[registerFieldId].value;
	var isValid = true;

	switch (registerFieldId) {
		case "registerEmail":
			isValid = validateRegisterEmail(registerFieldValue);
			break;
		case "registerUsername":
			isValid = validateRegisterUsername(registerFieldValue);
			break;
		case "registerPassword":
			isValid = validateRegisterPassword(registerFieldValue);
			break;
		default:
			isValid = false;
			break;
	}

	if (!isValid) {
		displayRegistrationError(registerFormGroupId);
		return 1;
	} else {
		clearRegistrationError(registerFormGroupId);
		return 0;
	}
}

// http://www.w3schools.com/js/tryit.asp?filename=tryjs_form_validate_email
// Validates by common email standards of @ symbol position and length
function validateRegisterEmail(registerEmailValue) {
	var atpos = registerEmailValue.indexOf("@");
	var dotpos = registerEmailValue.lastIndexOf(".");

	if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= registerEmailValue.length) {
		return false;
	}

	if (registerEmailValue.length < 3 || registerEmailValue.length > 255) {
		return false;
	}

	if (isEmptyValue(registerEmailValue)) {
		return false;
	}

	return true;
}

// Validates username being only 4-24 characters and containing only a-z
function validateRegisterUsername(registerUsernameValue) {
	if (registerUsernameValue.length < 4 || registerUsernameValue.length > 24) {
		return false;
	}

	if (!/^([a-z]{4,24})$/.test(registerUsernameValue)) {
		return false;
	}

	if (isEmptyValue(registerUsernameValue)) {
		return false;
	}

	return true;
}

// Validates password is greater than 6 characters
function validateRegisterPassword(registerPasswordValue) {
	if (registerPasswordValue.length < 6) {
		return false;
	}

	if (isEmptyValue(registerPasswordValue)) {
		return false;
	}

	return true;
}

function displayRegistrationError(formGroupId) {
	var errorId = formGroupId + "Error";
	var errorIdElement = document.getElementById(errorId);

	if (errorIdElement == null) {
		var formGroupElement = document.getElementById(formGroupId);
		formGroupElement.classList.add("has-error");

		var errorMessage = document.createElement("p");
		errorMessage.setAttribute("class", "bg-danger text-danger");
		errorMessage.setAttribute("id", errorId);

		var errorString = "";
		switch (formGroupId) {
			case "registerEmailFormGroup":
				errorString = "Invalid email address. Please enter a valid email.";
				break;
			case "registerUsernameFormGroup":
				errorString = "Username must be 4-24 characters long, containing only lowercase letters.";
				break;
			case "registerPasswordFormGroup":
				errorString = "Password must be at least 6 characters long.";
				break;
			default:
				errorString = "Invalid input";
				break;
		}

		var textNode = document.createTextNode(errorString);
		errorMessage.appendChild(textNode);

		formGroupElement.insertBefore(errorMessage, null);
	}
}

// http://stackoverflow.com/q/3387427
function clearRegistrationError(formGroupId) {
	var errorId = formGroupId + "Error";
	var errorIdElement = document.getElementById(errorId);

	if (errorIdElement != null) {
		var formGroupElement = document.getElementById(formGroupId);
		formGroupElement.classList.remove("has-error");

		errorIdElement.parentNode.removeChild(errorIdElement);
	}
}

function isEmptyValue(formValue) {
	if (formValue != "") {
		return false;
	}

	return true;
}
