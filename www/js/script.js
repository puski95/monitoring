$(function () {
	$.nette.init();
});

function redirect(url) {
    window.location.href = url;
}

function hideElement(element) {
    element.style.display = "none";
}

function hideElementById(id) {
    document.getElementById(id).style.display = "none";
}

function jumpNext(currentField) {
    if (currentField.value.length === 1) {
        const nextField = currentField.nextElementSibling;
        if (nextField && nextField.tagName === "INPUT") {
            nextField.focus();
        }
    }
}



/*CMS*/
function hideFlash(element) {
    element.style.display = "none";
}
function submitBtn(button) {
    const innerHTML = button.innerHTML;
    setTimeout(() => {
        button.disabled = true;
        button.innerHTML = "<i class='fa-solid fa-circle-notch fa-spin'></i>";
    }, 1);
    setTimeout(() => {
        button.disabled = false;
        button.innerHTML = innerHTML;
    }, 5000);
}
function enableUpload(input, btnId, resultId, nameId) {
    let btn = document.getElementById(btnId);
    let result = document.getElementById(resultId);
    let name = document.getElementById(nameId);
    if (input.files.length > 0) {
        btn.style.display = "flex";
        name.style.display = "flex";
        result.innerHTML = input.files[0].name;
    } else {
        btn.style.display = "none";
        name.style.display = "none";
        result.innerHTML = '';
    }
}
function editProfileAvatar(input) {
    let btn = document.getElementById('uploadBtn');
    let result = document.getElementById('srcFile');
    if (input.files.length > 0) {
        btn.style.display = "flex";
        result.innerHTML = input.files[0].name;
    } else {
        btn.style.display = "none";
        result.innerHTML = '';
    }
}

const ajaxLinks = document.querySelectorAll('a.ajaxlink');

ajaxLinks.forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        history.pushState(null, '', link.href);
    });
});

