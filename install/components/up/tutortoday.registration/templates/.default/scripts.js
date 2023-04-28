function showPopupForm(form) {
    form.style.display = 'flex';
    document.getElementById("overlay").style.background = 'rgba(0, 0, 0, 0.5)';
    document.getElementById("overlay").style.zIndex = '35';
}

function showSubjects() {
    showPopupForm(document.getElementById('popup-form-subjects-custom'))
}

function showEdFormats() {
    showPopupForm(document.getElementById('popup-form-ed-formats-custom'))
}

function closePopupForm(form) {
    form.style.display = 'none';
    document.getElementById("overlay").style.background = 'rgba(0, 0, 0, 0)';
    document.getElementById("overlay").style.zIndex = '0';
}

function closeSubjects() {
    closePopupForm(document.getElementById('popup-form-subjects-custom'))
}

function closeEdFormats() {
    closePopupForm(document.getElementById('popup-form-ed-formats-custom'))
}