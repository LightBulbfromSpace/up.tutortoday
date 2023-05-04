function hideWarning() {
    let elems = document.getElementsByClassName('tablebodytext')
    if (elems[0]) {
        elems[0].remove()
    }
}