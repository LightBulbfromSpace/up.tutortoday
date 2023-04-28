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

function submitForms() {
    document.getElementById('full-name-form').submit();
    document.getElementById('description-form').submit();
    document.getElementById('ed-format-form').submit();
}

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
                              <div>Are you sure that you want to delete your profile? This action cannot be canceled.</div>
                              <div class="button-delete-form-container">
                                  <button class="button-plus-minus button-small-custom container-button-custom" type="submit">Delete</button>
                                  <button class="link-button container-button-custom" type="button" onclick="closeDeleteProfileForm()">Cancel</button>
                              </div>
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