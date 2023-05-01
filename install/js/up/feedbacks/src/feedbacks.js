import {Type} from 'main.core';

export class Feedbacks
{

	sendButton
	#feedbacksPerLoad = 5
	#page = 0

	constructor(options = {})
	{
		if (!Type.isStringFilled(options.rootNodeID)) {
			throw new Error('Feedbacks: options.rootNodeID is required')
		}


		this.rootNode = document.getElementById(options.rootNodeID)


		if (!this.rootNode) {
			throw new Error(`Feedbacks: element with ID "${options.rootNodeID}" not found`)
		}

		if (!Type.isStringFilled(options.formID)) {
			throw new Error('Feedbacks: options.formID is required')
		}


		this.form = document.getElementById(options.formID)


		this.isActive = !!this.form;


		if (!this.isActive) {
			return
		}

		if (!Type.isInteger(options.feedbackReceiverID))
		{
			throw new Error(`Feedbacks: options.feedbackReceiver must be an integer`)
		}


		this.feedbackReceiverID = options.feedbackReceiverID


		if (!Type.isStringFilled(options.toggleButtonID)) {
			throw new Error('Feedbacks: options.toggleButton is required')
		}


		this.toggleButton = document.getElementById(options.toggleButtonID)


		if (!this.toggleButton) {
			throw new Error(`Feedbacks: element with ID "${options.toggleButtonID}" not found`)
		}

		this.toggleButton.onclick = () => {this.openForm()}

		if (!Type.isStringFilled(options.feedbacksRootID)) {
			throw new Error('Feedbacks: options.toggleButton is required')
		}


		this.feedbacksRootID = document.getElementById(options.feedbacksRootID)


		if (!this.feedbacksRootID) {
			throw new Error(`Feedbacks: element with ID "${options.feedbacksRootID}" not found`)
		}
	}

	openForm()
	{
		if (!this.isActive) {
			return
		}
		this.form.style.margin = '0.5rem 0 1rem 0'
		this.form.innerHTML = `<div class="container-feedback-custom">
										  <input type="text" class="input-custom" id="feedback-title" placeholder="Title" maxlength="100">
										  <textarea class="textarea-custom" id="feedback-description" placeholder="Description"></textarea>
										  <div class="container-row-custom">
											  <div class="stars-container">
												  <span class="fa fa-star"></span>
												  <span class="fa fa-star"></span>
												  <span class="fa fa-star"></span>
												  <span class="fa fa-star"></span>
												  <span class="fa fa-star"></span>
											  </div>
											  <button class="button-plus-minus button-small-custom" id="send-button">Send</button>
										  </div>
									  </div>`
		this.toggleButton.innerText = 'Close'
		this.toggleButton.onclick = () => {this.closeForm()}

		this.sendButton = document.getElementById('send-button')
		this.sendButton.onclick = () => {this.#sendForm(); this.closeForm()}
	}

	closeForm()
	{
		if (!this.isActive) {
			return
		}
		while (this.form.lastChild) {
			this.form.lastChild.remove()
		}

		this.toggleButton.innerText = 'Add feedback'
		this.toggleButton.onclick = () => {this.openForm()}
	}

	#sendForm()
	{
		let title = document.getElementById('feedback-title')
		let description = document.getElementById('feedback-description')
		//TODO: remove hardcode
		let stars = 0
		BX.ajax({
			url: '/profile/feedbacks/add/',
			data: {
				receiverID: this.feedbackReceiverID,
				title: title.value,
				description: description.value,
				stars: stars,
				sessid: BX.bitrix_sessid(),
			},
			method: 'POST',
			dataType: 'json',
			timeout: 10,
			onsuccess: (res) => {
				//console.log(res)
				this.loadFeedbacksPerPage()
			},
			onfailure: function reject (e) {
				console.log(e)
			},
		})
	}

	loadFeedbacksPerPage()
	{
		BX.ajax({
			url: '/profile/feedbacks/',
			data: {
				tutorID: this.feedbackReceiverID,
				page: this.#page,
				tutorsPerPage: this.#feedbacksPerLoad,
				sessid: BX.bitrix_sessid(),
			},
			method: 'POST',
			dataType: 'json',
			timeout: 10,
			onsuccess: (res) => {
				console.log(res)
				if (res === null) {
					return
				}
				while (this.feedbacksRootID.lastChild) {
					this.feedbacksRootID.lastChild.remove()
				}
				this.displayFeedbacksPerPage(res)
			},
			onfailure: function reject (e) {
				console.log(e)
			},
		})
	}

	displayFeedbacksPerPage(feedbacks) {
		for (let i= 0; i < feedbacks.length; i++) {
			console.log(i)
			let elem = document.createElement('div')
			elem.classList.add('feedback-card-container')
			elem.innerHTML = `<a class="feedback-card-user-info-container" href="/profile/${feedbacks[i]['student']['ID']}/">
									<img src="${this.#sanitize(feedbacks[i]['student']['photo'])}" class="photo-small img-rounded" alt="avatar">
									<div class="help">${this.#sanitize(feedbacks[i]['student']['surname'])}</div>
									<div class="help">${this.#sanitize(feedbacks[i]['student']['name'])}</div>
								</a>
								<div class="box feedback-card-custom">
									<div class="title-custom">${this.#sanitize(feedbacks[i]['title'])}</div>
									<div class="br"></div>
									<div>${this.#sanitize(feedbacks[i]['description'])}</div>
								</div>`
			this.feedbacksRootID.appendChild(elem)
		}
	}

	#sanitize(string) {
		const map = {
			'&': '&amp;',
			'<': '&lt;',
			'>': '&gt;',
			'"': '&quot;',
			"'": '&#x27;',
			"/": '&#x2F;',
		};
		const reg = /[&<>"'/]/ig;
		return string.replace(reg, (match)=>(map[match]));
	}
}