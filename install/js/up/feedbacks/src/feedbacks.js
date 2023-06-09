import {Type} from 'main.core';

export class Feedbacks
{

	sendButton
	#feedbacksPerLoad = 3
	#page = 0
	#stars

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
		this.form.innerHTML = `<div class="container-feedback-custom cards-background round-corners">
										  <input type="text" class="input-custom" id="feedback-title" placeholder="Title" maxlength="100">
										  <textarea class="textarea-custom" id="feedback-description" placeholder="Description"></textarea>
										  <div class="stars-button-container">
											  <div class="stars-container">
												  <button id="s5" class="fa fa-star"></button>
												  <button id="s4" class="fa fa-star"></button>
												  <button id="s3" class="fa fa-star"></button>
												  <button id="s2" class="fa fa-star"></button>
												  <button id="s1" class="fa fa-star"></button>
											  </div>
											  <button class="button-plus-minus button-small-custom" id="send-button">Send</button>
										  </div>
									  </div>`
		this.toggleButton.innerText = 'Close'
		this.toggleButton.onclick = () => {this.closeForm()}

		for (let i = 1; i < 6; i++) {
			document.getElementById('s'+ i).onclick = () => {
				this.#stars = i
				for (let j = 1; j < 6; j++) {
						document.getElementById('s'+ j).classList.remove('star-selected')
				}
				for (let j = 1; j <= this.#stars; j++) {
					document.getElementById('s'+ j).classList.add('star-selected')
				}
			}
		}

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
		BX.ajax({
			url: '/profile/feedbacks/add/',
			data: {
				receiverID: this.feedbackReceiverID,
				title: title.value,
				description: description.value,
				stars: this.#stars,
				sessid: BX.bitrix_sessid(),
			},
			method: 'POST',
			dataType: 'json',
			timeout: 10,
			onsuccess: (res) => {
				console.log(res)
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
				//console.log(res)
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

		while (this.feedbacksRootID.lastChild) {
			this.feedbacksRootID.lastChild.remove()
		}

		let noFbMsg = document.getElementById('no-feedbacks-message')
		this.feedbacksRootID.style.justifyContent = 'space-between'
		if (feedbacks['total']=== 0) {
			if (noFbMsg) {
				return
			}
			let noFbMsg = document.createElement('div')
			noFbMsg.id = 'no-feedbacks-message'
			noFbMsg.classList.add('box')
			noFbMsg.innerText = 'No feedbacks yet'
			this.feedbacksRootID.appendChild(noFbMsg)
			this.feedbacksRootID.style.justifyContent = 'center'
			return
		}

		if (noFbMsg) {
			noFbMsg.remove()
		}

		this.feedbacksRootID.innerHTML = `<button class="feedback-button" id="feedbacks-button-previous">&lt;</button>
										  <div class="feedback-cards-container" id="feedback-cards-container"></div>
										  <button class="feedback-button" id="feedbacks-button-next">&gt;</button>`

		let previousButton = document.getElementById('feedbacks-button-previous')
		let feedbacksContainer = document.getElementById('feedback-cards-container')
		let nextButton = document.getElementById('feedbacks-button-next')

		previousButton.onclick = () => {
			previousButton.style.backgroundColor = this.#page > 0 ? '#FFFFFF' : '#D9D9D9'
			if (this.#page <= 0) {
				return
			}
			this.#page--
			this.loadFeedbacksPerPage()
			console.log('previous',this.#page)
		}

		nextButton.onclick = () => {
			let maxPage = Math.ceil(feedbacks['total'] / this.#feedbacksPerLoad - 1)
			nextButton.style.backgroundColor = this.#page < maxPage ? '#FFFFFF' : '#D9D9D9'
			if (this.#page >= maxPage) {
				return
			}
			this.#page++
			this.loadFeedbacksPerPage()
			console.log('next',this.#page, feedbacks['total'] / this.#feedbacksPerLoad)

		}

		for (let i= 0; i < feedbacks['feedbacks'].length; i++) {
			let elem = document.createElement('div')
			elem.classList.add('feedback-card-container', 'round-corners')
			elem.innerHTML =   `<a class="feedback-card-user-info-container" href="/profile/${feedbacks['feedbacks'][i]['student']['ID']}/">
									<img src="${this.#sanitize(feedbacks['feedbacks'][i]['student']['photo'])}" class="photo-small img-rounded" alt="avatar">
									<div class="help">${this.#sanitize(feedbacks['feedbacks'][i]['student']['surname'])}</div>
									<div class="help">${this.#sanitize(feedbacks['feedbacks'][i]['student']['name'])}</div>
								</a>
								<div class="feedback-card-custom">
									<div class="title-feedback-custom ml-2">
										<div class="title-custom">${this.#sanitize(feedbacks['feedbacks'][i]['title'])}</div>
										<div class="stars-container">
											<div id="s5-${i}-disabled" class="fa fa-star"></div>
											<div id="s4-${i}-disabled" class="fa fa-star"></div>
											<div id="s3-${i}-disabled" class="fa fa-star"></div>
											<div id="s2-${i}-disabled" class="fa fa-star"></div>
											<div id="s1-${i}-disabled" class="fa fa-star"></div>
										</div>
									</div>
<!--									<div class="br"></div>-->
									<div class="feedback-body-custom desc-background">
										${this.#sanitize(feedbacks['feedbacks'][i]['description']) === '' ? 'No description' : this.#sanitize(feedbacks['feedbacks'][i]['description'])}
									</div>
								</div>`
			feedbacksContainer.appendChild(elem)
			for (let j = 1; j <= feedbacks['feedbacks'][i]['stars']; j++) {
				document.getElementById(`s${j}-${i}-disabled`).classList.add('star-selected')
			}
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