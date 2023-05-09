function getTime(dayID, userID) {
    BX.ajax({
        url: '/profile/weekday/',
        data: {
            weekdayID: dayID,
            userID: userID,
            sessid: BX.bitrix_sessid(),
        },
        method: 'POST',
        dataType: 'json',
        timeout: 10,
        onsuccess: function (res) {
            console.error(res)
            displayTime(res, dayID)
        },
        onfailure: e => {
            console.error(e)
        }
    })
}

function displayTime(res, weekdayID) {
    if (res == null) {
        return
    }

    let area = document.getElementById('free-time-area')
    while (area.lastElementChild) {
        area.removeChild(area.lastElementChild);
    }

    if (res['time'].length === 0) {
        let divElem = document.createElement('div');
        divElem.classList.add('card-text-custom', 'mr-2', 'ml-2', 'width-100', 'is-justified-center', 'text-align-center')
        divElem.innerText = 'No time selected';
        area.appendChild(divElem);
    } else {
        res['time'].forEach((interval) => {
            let divElem = document.createElement('div');
            divElem.classList.add('card-text-custom', 'mr-2', 'ml-2', 'width-100', 'is-justified-center')
            divElem.innerText = interval['start'] + ' - ' + interval['end'];
            area.appendChild(divElem);
        });
    }
    console.log(weekdayID)
    for(let i = 1; i < 8; i++)
    {
        document.getElementById('weekday-' + i).classList.remove('weekday-selected')
    }
    document.getElementById('weekday-' + weekdayID).classList.add('weekday-selected')
}

function hideWarning() {
    let elems = document.getElementsByClassName('tablebodytext')
    if (elems[0]) {
        elems[0].remove()
    }
}