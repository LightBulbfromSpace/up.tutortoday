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

// function openFeedbackForm() {
//     let area= document.getElementById('feedback-form-area')
//     area.innerHTML = `<div class="container-feedback-custom">
//                           <input type="text" class="input-custom" name="feedback-title" placeholder="Title">
//                           <textarea class="textarea-custom" name="feedback-description" placeholder="Description"></textarea>
//                           <div class="container-row-custom">
//                               <div class="stars-container">
//                                   <span class="fa fa-star"></span>
//                                   <span class="fa fa-star"></span>
//                                   <span class="fa fa-star"></span>
//                                   <span class="fa fa-star"></span>
//                                   <span class="fa fa-star"></span>
//                               </div>
//                               <button class="button-plus-minus button-small-custom">Send</button>
//                           </div>
//                       </div>`
//     let addButton = document.getElementById('add-close-feedback-button')
//     addButton.innerText = 'Close'
//     addButton.onclick = () => {closeFeedbackForm()}
//     // BX.ajax.post(
//     //     '/profile'
//     // )
// }
// function closeFeedbackForm() {
//     let area= document.getElementById('feedback-form-area')
//     while (area.lastChild) {
//         area.lastChild.remove()
//     }
//     let addButton = document.getElementById('add-close-feedback-button')
//     addButton.innerText = 'Add feedback'
//     addButton.onclick = () => {openFeedbackForm()}
//     console.log(sessionStorage.getItem('feedback-for-user'))
//
// }