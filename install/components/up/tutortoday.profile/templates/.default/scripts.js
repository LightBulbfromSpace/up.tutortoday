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

    if (res.length === 0) {
        let divElem = document.createElement('div');
        divElem.classList.add('box-dark-element-custom', 'width-100', 'is-justified-center')
        divElem.innerText = 'No time selected';
        area.appendChild(divElem);
    } else {
        res.forEach((interval) => {
            let divElem = document.createElement('div');
            divElem.classList.add('box-dark-element-custom', 'width-100', 'is-justified-center')
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