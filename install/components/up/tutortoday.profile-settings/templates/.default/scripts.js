
function getUserID() {
    // let result =  BX.ajax({
    //     url: '/profile/getID/',
    //     data: {
    //         sessid: BX.bitrix_sessid(),
    //     },
    //     method: 'POST',
    //     dataType: 'json',
    //     timeout: 10,
    //     onsuccess: function (res) {
    //         console.log(res)
    //     },
    //     onfailure: e => {
    //         console.error(e)
    //     }
    // })

    return BX.ajax.post(
        '/profile/getID/',
        {
            sessid: BX.bitrix_sessid(),
        },
        function (res) {
            return res
        });

    // return result
}
function getTime(userID, dayID) {
    BX.ajax({
        url: '/profile/weekday/',
        data: {
            userID: userID,
            weekdayID: dayID,
            sessid: BX.bitrix_sessid(),
        },
        method: 'POST',
        dataType: 'json',
        timeout: 10,
        onsuccess: function (res) {
            displayTime(res, dayID, userID)
        },
        onfailure: e => {
            console.error(e)
        }
    })
}

function displayTime(res, weekdayID, userID) {
    if (res == null) {
        return
    }
    let area = document.getElementById('free-time-area')
    while (area.lastElementChild) {
        area.removeChild(area.lastElementChild);
    }

    if (res.length === 0) {
        let divElem = document.createElement('div');
        divElem.innerText = 'No time selected';
        area.appendChild(divElem);
    } else {
        res.forEach((interval) => {
            let time = document.createElement('div');
            time.innerText = interval['start'] + ' - ' + interval['end'];
            time.classList.add('box-dark-element-custom', 'width-100', 'is-justified-center')
            let button = document.createElement('button');
            button.type = 'button'
            button.innerText = '-';
            button.classList.add('button-plus-minus', 'button-large-custom')
            button.onclick = () => {
                deleteTime(interval['ID'])
                getTime(userID, weekdayID)
            }
            area.appendChild(time);
            area.appendChild(button);
        });
    }
    for(let i = 1; i < 8; i++)
    {
        document.getElementById('weekday-' + i).classList.remove('weekday-selected')
    }
    document.getElementById('weekday-' + weekdayID).classList.add('weekday-selected')
}

function showTimepicker() {
    document.getElementById('timepicker-form').style.display = 'flex';
}

function closeTimepicker() {
    document.getElementById('timepicker-form').style.display = 'none';
}

// function submitForms() {
//     document.getElementById('full-name-form').submit();
//     document.getElementById('description-form').submit();
//     document.getElementById('ed-format-form').submit();
// }

function closeSubjectForm() {
    document.getElementById('add-subject-area').lastChild.remove()
}

function deleteSubject(subjID, userID) {
    BX.ajax({
        url: '/profile/settings/deleteSubject/',
        data: {
            subjectID: subjID,
            userID: userID,
            sessid: BX.bitrix_sessid(),
        },
        method: 'POST',
        dataType: 'json',
        timeout: 10,
    })

    document.getElementById('subject-container-' + subjID).remove()
}

function AddSubjectForm() {
    BX.ajax({
        url: '/profile/settings/allSubjects/',
        method: 'GET',
        dataType: 'json',
        timeout: 10,
        onsuccess: (res) => {
            openSubjectForm(res)
        },
        onfailure: (e) => {
          console.log(e)
        }
    })
    return null
}

function openSubjectForm(weekdays) {
    let addArea = document.getElementById('add-subject-area')
    let form = document.createElement('div')

    let options = '';
    weekdays.forEach((weekday) => {
        options += `<option value="` + weekday['ID'] + `">` + weekday['name'] + `</option>`
    })

    form.innerHTML = `<div class="container-subjects box max-width-90 is-justified-center" id="subject-form">
                        <div class="container-subjects">
                            <div class="control">
                                <div class="select-custom">
                                    <select name="newSubjectsID[]">`
                                    +
                                    options
                                    +
                                    `</select>
                                </div>
                            </div>
                            <div class="container-row-custom is-aligned-center max-width-90">
                                <div class="box-dark-element-custom">
                                    <input type="number" class="input-custom" placeholder="Price" name="newSubjectsPrices[]" value="1000">
                                    <div class="price">rub/hour</div>
                                </div>
                            </div>
                        </div>
                    </div>`
    addArea.appendChild(form)
}

function addTime(userID) {

    let weekdayID = null
    for(let i = 1; i < 8; i++)
    {
        let weekday = document.getElementById('weekday-' + i)
        if (weekday.classList.contains('weekday-selected'))
        {
            weekdayID = i
        }
    }

    let from = document.getElementById('time-from').value
    let to = document.getElementById('time-to').value
    if (weekdayID === null) { return }

    BX.ajax({
        url: '/profile/settings/addTime/',
        data: {
            timeFrom: from,
            timeTo: to,
            userID: userID,
            weekdayID: weekdayID,
            sessid: BX.bitrix_sessid(),
        },
        method: 'POST',
        dataType: 'json',
        timeout: 10,
        onsuccess: (res) => {
            console.log(res)
        },
        onfailure: (e) => {
            console.log(e)
        },
    })
    getTime(userID, weekdayID)
}

function deleteTime(timeID) {
    BX.ajax({
        url: '/profile/settings/deleteTime/',
        data: {
            timeID: timeID,
            sessid: BX.bitrix_sessid(),
        },
        method: 'POST',
        dataType: 'json',
        timeout: 10,
        onsuccess: (res) => {
            console.log(res)
        },
        onfailure: (e) => {
            console.log(e)
        },
    })
}

function openDeleteProfileForm(userID) {
    let formElem = document.createElement('form')
    formElem.id = 'delete-profile-form'
    formElem.action = '/profile/'+ userID + '/delete/'
    formElem.method = 'post'
    formElem.style.zIndex = '36'
    formElem.classList.add('box', 'delete-form-container')
    formElem.style.display = 'flex'
    formElem.style.flexDirection = 'column'
    formElem.style.justifyContent = 'space-between'
    formElem.style.alignItems = 'center'

    formElem.innerHTML = `<div class="bold">Delete profile</div>
                              <div class="text-delete-form-container">
                                  <div>Are you sure that you want to delete your profile?</div>
                                  <div>This action cannot be canceled.</div>
                              </div>
                              <div class="button-delete-form-container">
                                  <button class="button-plus-minus button-small-custom container-button-custom" type="submit">Delete</button>
                                  <button class="link-button container-button-custom" type="button" onclick="closeDeleteProfileForm()">Cancel</button>
                              </div>
                              <input type="hidden" name="sessid" id="sessid" value="` + BX.bitrix_sessid() + `">
    `
    formElem.style.position = 'absolute'
    formElem.style.margin = '0 auto'
    document.getElementById('delete-form-area').appendChild(formElem)
    turnOnOverlay()
}

function closeDeleteProfileForm() {
    let form = document.getElementById('delete-profile-form')
    form.remove()
    turnOffOverlay()
}

function turnOnOverlay() {
    document.getElementById("overlay").style.background = 'rgba(0, 0, 0, 0.5)';
    document.getElementById("overlay").style.zIndex = '35';
}

function turnOffOverlay() {
    document.getElementById("overlay").style.background = 'rgba(0, 0, 0, 0)';
    document.getElementById("overlay").style.zIndex = '0';

}

function updatePassword(userID) {
    let oldPassword = document.getElementById('oldPassword').value
    let password = document.getElementById('newPassword').value
    let confirmPassword =  document.getElementById('passwordConfirm').value
    BX.ajax({
        url: '/profile/' + userID + '/settings/changePassword/',
        data: {
            oldPassword: oldPassword,
            newPassword: password,
            passwordConfirm: confirmPassword,
            sessid: BX.bitrix_sessid(),
        },
        method: 'POST',
        dataType: 'json',
        timeout: 10,
        onsuccess: (res) => {
            console.log(res)
            displayResult(res)
        },
        onfailure: (e) => {
            console.log(e)
        },
    })
}

function displayResult(result) {
    let msgContainer = document.getElementById('msg-container')
    while (msgContainer.lastChild) {
        msgContainer.lastChild.remove()
    }
    let msg = document.createElement('article');
    msg.style.margin = '0.75rem 0'
    msg.classList.add('message')
    if (result['TYPE'] === 'OK') {
        msg.classList.add('is-success')
    } else {
        msg.classList.add('is-danger')
    }
    msg.innerHTML = `<div class="message-body">`
        +
        result['MESSAGE']
        +
        `</div>`
    msgContainer.appendChild(msg)
}

function openAddPhotoForm(userID) {
    BX.ajax({
        url: '/profile/settings/getProfilePhoto/',
        method: 'POST',
        data: {
            userID: userID,
            sessid: BX.bitrix_sessid(),
        },
        dataType: 'json',
        timeout: 10,
        onsuccess: (res) => {
            console.log(res)
            displayAddPhotoForm(res)
        },
        onfailure: (e) => {
            console.log(e)
        },
    })
}

function displayAddPhotoForm(imgSrc) {
    let formContainer = document.createElement('div')
    formContainer.classList.add('add-form-photo-dark-frame')
    formContainer.innerHTML = `<form class="add-photo-container" id="add-photo-form" enctype="multipart/form-data" action="/profile/settings/updatePhotoPreview/" method="post">
                                   <input type="hidden" name="sessid" id="sessid" value="` + BX.bitrix_sessid() + `" onclick="ToPreviewButton()">
                                   <img src="` + imgSrc + `" class="img-rounded img-fixed-size add-photo-img" alt="profile photo" id="photo-add-photo-form">
                                   <div>Click on photo to choose new one</div>
                                   <button type="button" class="photo-button">Open</button>
                                   <input type="file" name="photo" id="file-input">
                                   <div class="add-form-button-container">
                                       <button type="button" class="button-plus-minus add-form-button" onclick="closeAddPhotoForm()">Cancel</button>
                                       <button type="button" class="button-plus-minus add-form-button" id="preview-confirm-button" onclick="updatePhoto()">Preview</button>
                                   </div>
                               </form>`
    document.getElementById('add-photo-form-area').appendChild(formContainer)
    turnOnOverlay()
}

function closeAddPhotoForm() {
    let area = document.getElementById('add-photo-form-area')
    while (area.lastChild) {
        document.getElementById('add-photo-form-area').lastChild.remove()
    }
    turnOffOverlay()
}

function updatePhoto() {
    let fileUpload = document.getElementById('file-input')
    if (fileUpload.files[0] == null)
    {
        return
    }
    let bxFormData = new BX.ajax.FormData()
    bxFormData.append("sessid", BX.bitrix_sessid())
    bxFormData.append("photo", fileUpload.files[0])
    bxFormData.send(
        '/profile/settings/updatePhotoPreview/',
        (res) => {
            console.log(res)
            loadPhoto(res)
            BX.setCookie('avatarSrc', res)
            ToConfirmButton()
        },
        null,
        (e) => {
            console.log(e)
        })
}

function updatePhotoConfirm() {
    BX.ajax({
        url: '/profile/settings/updatePhotoConfirm/',
        method: 'POST',
        data: {
            imgSrc: BX.getCookie('avatarSrc'),
            sessid: BX.bitrix_sessid(),
        },
        dataType: 'json',
        timeout: 10,
        onsuccess: (res) => {
            console.log(res)
            if (res === true)
            {
                document.getElementById('profilePhoto').src = BX.getCookie('avatarSrc')
            }
            closeAddPhotoForm()
        },
        onfailure: (e) => {
            console.log(e)
        },
    })
}

function loadPhoto(path) {
    document.getElementById('photo-add-photo-form').src = path
}

function ToConfirmButton() {
    let button = document.getElementById('preview-confirm-button')
    button.onclick = updatePhotoConfirm
    button.innerText = 'Confirm'
}

function ToPreviewButton() {
    let button = document.getElementById('preview-confirm-button')
    button.onclick = updatePhoto
    button.innerText = 'Preview'
}