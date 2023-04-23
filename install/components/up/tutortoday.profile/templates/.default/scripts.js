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
            displayTime(res)
        },
        onfailure: e => {
            console.error(e)
        }
    })
}

function displayTime(res) {
    if (res != null) {
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
                let divElem = document.createElement('div');
                divElem.innerText = interval['start'] + ' - ' + interval['end'];
                area.appendChild(divElem);
            });
        }
    }
}