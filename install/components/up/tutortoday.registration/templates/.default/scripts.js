function showPopupForm() {
    let forms = document.getElementsByClassName('popup-form-custom');
    for(let i = 0; i < forms.length; i++) {
        forms[i].style['display'] = 'flex';
    }
    document.getElementById("overlay").style.background = 'rgba(0, 0, 0, 0.5)';
    document.getElementById("overlay").style.zIndex = '35';
}

function closePopupForm() {
    let forms = document.getElementsByClassName('popup-form-custom');
    for(let i = 0; i < forms.length; i++) {
        forms[i].style['display'] = 'none';
    }
    document.getElementById("overlay").style.background = 'rgba(0, 0, 0, 0)';
    document.getElementById("overlay").style.zIndex = '0';
}