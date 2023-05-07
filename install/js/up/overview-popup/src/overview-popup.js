import {Type} from 'main.core';

export class OverviewPopup
{

	filters = []

	constructor(options = {})
	{
		if (!Type.isStringFilled(options.nodeID)) {
			throw new Error('Feedbacks: options.nodeID is required')
		}

		this.node = document.getElementById(options.nodeID)

		if (!this.node) {
			throw new Error(`Feedbacks: element with ID "${options.nodeID}" not found`)
		}


		if (Type.isBoolean(options.subjects) && options.subjects) {
			this.filters.push('subjects')
		}

		if (Type.isBoolean(options.edFormats) && options.edFormats) {
			this.filters.push('education formats')
		}

		if (Type.isBoolean(options.city) && options.city) {
			this.filters.push('cities')
		}

		console.log(options.price, Type.isBoolean(options.price))
		if (Type.isBoolean(options.price) && options.price) {
			this.filters.push('price')
		}

		if (Type.isBoolean(options.preferences) && options.preferences) {
			this.filters.push('preferences')
		}
	}

	displayMessage()
	{
		if (this.filters.length === 0)
		{
			return
		}

		let messageText = this.filters[0]
		if (this.filters.length > 1) {
			messageText = this.filters.join(', ')
		}


		let message = document.createElement('article')
		message.classList.add('message', 'is-success')

		message.innerHTML = `<div class="message-header">
								<p>Filters applied</p>
								<button class="delete" id="popup-delete-button"></button>
							  </div>
							  <div id="filter-used" class="message-body">
								  Filters used: ${messageText}.
							  </div>`


		message.style.position = 'fixed'
		message.style.margin = '35% 5% 5% 75%'
		message.style.zIndex = '100'

		this.node.appendChild(message)

		let button = document.getElementById('popup-delete-button')
		button.onclick = () => {message.remove()}
	}

}