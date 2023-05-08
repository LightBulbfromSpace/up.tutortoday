import {Type} from 'main.core';

export class AdminPanel
{
	#itemsPerPage = 3
	#usersPage
	#subjectsPage
	#edFormatsPage
	#citiesPage

	#reloadFunc
	#addButtonCallback

	constructor(options = {})
	{
		this.#usersPage = new Page()
		this.#subjectsPage = new Page()
		this.#edFormatsPage = new Page()
		this.#citiesPage = new Page()

		if (!Type.isStringFilled(options.errorMsgAreaID)) {
			throw new Error('Feedbacks: "buttonsContainerID" is required')
		}

		this.errorMsgArea = document.getElementById(options.errorMsgAreaID)

		if (!this.errorMsgArea) {
			throw new Error(`Feedbacks: element with ID "${options.errorMsgAreaID}" not found`)
		}

		window.addEventListener('click', () => {
			while (this.errorMsgArea.lastChild) {
				this.errorMsgArea.lastChild.remove()
			}
		})

		if (!Type.isStringFilled(options.buttonsContainerID)) {
			throw new Error('Feedbacks: "buttonsContainerID" is required')
		}

		this.buttonsContainer = document.getElementById(options.buttonsContainerID)

		if (!this.buttonsContainer) {
			throw new Error(`Feedbacks: element with ID "${options.buttonsContainerID}" not found`)
		}


		if (!Type.isStringFilled(options.dataAreaID)) {
			throw new Error('Feedbacks: "dataAreaID" is required')
		}

		this.dataArea = document.getElementById(options.dataAreaID)

		if (!this.dataArea) {
			throw new Error(`Feedbacks: element with ID "${options.dataAreaID}" not found`)
		}


		if (!Type.isStringFilled(options.addButtonAreaID)) {
			throw new Error('Feedbacks: "addButtonAreaID" is required')
		}

		this.addButtonArea = document.getElementById(options.addButtonAreaID)

		if (!this.addButtonArea) {
			throw new Error(`Feedbacks: element with ID "${options.addButtonAreaID}" not found`)
		}


		if (!Type.isStringFilled(options.userButtonID)) {
			throw new Error('Feedbacks: "userButtonID" is required')
		}

		this.userButton = document.getElementById(options.userButtonID)

		if (!this.userButton) {
			throw new Error(`Feedbacks: element with ID "${options.userButtonID}" not found`)
		}

		this.userButton.onclick = () => {this.#loadUsers()}

		if (!Type.isStringFilled(options.subjectsButtonID)) {
			throw new Error('Feedbacks: "subjectsButtonID" is required')
		}

		this.subjectsButton = document.getElementById(options.subjectsButtonID)

		if (!this.subjectsButton) {
			throw new Error(`Feedbacks: element with ID "${options.subjectsButtonID}" not found`)
		}

		this.subjectsButton.onclick = () => {this.#loadSubjects()}


		if (!Type.isStringFilled(options.edFormatsButtonID)) {
			throw new Error('Feedbacks: "edFormatsButtonID" is required')
		}

		this.edFormatsButton = document.getElementById(options.edFormatsButtonID)

		if (!this.edFormatsButton) {
			throw new Error(`Feedbacks: element with ID "${options.edFormatsButtonID}" not found`)
		}

		this.edFormatsButton.onclick = () => {this.#loadEdFormats()}


		if (!Type.isStringFilled(options.citiesButtonID)) {
			throw new Error('Feedbacks: "citiesButtonID" is required')
		}

		this.citiesButton = document.getElementById(options.citiesButtonID)

		if (!this.citiesButton) {
			throw new Error(`Feedbacks: element with ID "${options.citiesButtonID}" not found`)
		}

		this.citiesButton.onclick = () => {this.#loadCities()}


		if (!Type.isStringFilled(options.previousButtonID)) {
			throw new Error('Feedbacks: "previousButtonID" is required')
		}

		this.previousButton = document.getElementById(options.previousButtonID)

		if (!this.previousButton) {
			throw new Error(`Feedbacks: element with ID "${options.previousButtonID}" not found`)
		}

		if (!Type.isStringFilled(options.nextButtonID)) {
			throw new Error('Feedbacks: "nextButtonID" is required')
		}

		this.nextButton = document.getElementById(options.nextButtonID)

		if (!this.nextButton) {
			throw new Error(`Feedbacks: element with ID "${options.nextButtonID}" not found`)
		}

		this.addButton = this.#createAddButton(() => {})

		this.#loadUsers()
	}

	#loadUsers()
	{
		while (this.addButtonArea.lastChild) {
			this.addButtonArea.lastChild.remove()
		}
		for (let i = 0; i < this.buttonsContainer.children.length; i++) {
			this.buttonsContainer.children[i].classList.remove('menu-button-active')
		}
		this.userButton.classList.add('menu-button-active')
		BX.ajax.get(
			'/admin/users/',
			{
				page: this.#usersPage.value,
				itemsPerPage: this.#itemsPerPage,
			},
			(res) => {
				console.log(res)
				this.#displayUserElements(JSON.parse(res), '/admin/user/block/')
			}
		)
	}

	#loadSubjects()
	{
		for (let i = 0; i < this.buttonsContainer.children.length; i++) {
			this.buttonsContainer.children[i].classList.remove('menu-button-active')
		}
		this.subjectsButton.classList.add('menu-button-active')

		BX.ajax.get(
			'/admin/subjects/',
			{
				page: this.#subjectsPage.value,
				itemsPerPage: this.#itemsPerPage,
			},
			(res) => {
				this.#displaySubjects(JSON.parse(res))
				console.log(res)
			}
		)
	}

	#displaySubjects(res)
	{
		this.#reloadFunc = this.#loadSubjects

		this.#displayIDNameElements(
			res, 'subject-',
				'/admin/delete/subject/',
				'/admin/edit/subject/',
			)

		this.#addButtonCallback = () => {
			this.dataArea.appendChild(this.#addItemTableElement(
				'/admin/add/subjects/'
			))
		}

		let maxPage = Math.ceil(res['total'] / this.#itemsPerPage) - 1

		this.#bindPaginationButtons(this.#subjectsPage, maxPage)

		this.#displayAddButton()
	}

	#loadEdFormats()
	{
		for (let i = 0; i < this.buttonsContainer.children.length; i++) {
			this.buttonsContainer.children[i].classList.remove('menu-button-active')
		}
		this.edFormatsButton.classList.add('menu-button-active')

		BX.ajax.get(
			'/admin/edFormats/',
			{
				page: this.#edFormatsPage.value,
				itemsPerPage: this.#itemsPerPage,
			},
			(res) => {
				console.log(res)
				this.#displayEdFormats(JSON.parse(res))
			}
		)
	}

	#displayEdFormats(res)
	{
		this.#reloadFunc = this.#loadEdFormats

		this.#displayIDNameElements(
			res, 'ed-format-',
				'/admin/delete/edFormat/',
				'/admin/edit/edFormat/',
			)

		this.#addButtonCallback = () => {
			this.dataArea.appendChild(this.#addItemTableElement(
				'/admin/add/edFormat/'
			))
		}

		let maxPage = Math.ceil(res['total'] / this.#itemsPerPage) - 1

		this.#bindPaginationButtons(this.#edFormatsPage, maxPage)

		this.#displayAddButton()
	}

	#loadCities()
	{
		for (let i = 0; i < this.buttonsContainer.children.length; i++) {
			this.buttonsContainer.children[i].classList.remove('menu-button-active')
		}
		this.citiesButton.classList.add('menu-button-active')

		BX.ajax.get(
			'/admin/cities/',
			{
				page: this.#citiesPage.value,
				itemsPerPage: this.#itemsPerPage,
			},
			(res) => {
				console.log(res)
				this.#displayCities(JSON.parse(res))
			}
		)
	}

	#displayCities(res)
	{
		this.#reloadFunc = this.#loadCities

		this.#displayIDNameElements(
			res, 'city-',
				'/admin/delete/city/',
				'/admin/edit/city/',
			)

		this.#addButtonCallback = () => {
			this.dataArea.appendChild(this.#addItemTableElement(
				'/admin/add/city/'
			))
		}

		let maxPage = Math.ceil(res['total'] / this.#itemsPerPage) - 1

		this.#bindPaginationButtons(this.#citiesPage, maxPage)

		this.#displayAddButton()
	}

	#displayIDNameElements(res, IDPrefix, deleteAddress, editAddress)
	{
		while (this.dataArea.lastChild) {
			this.dataArea.lastChild.remove()
		}

		let elements = []

		for (let i = 0; i <  res['items'].length; i++) {
			elements.push(this.#createTableElement(
				res['items'][i]['ID'], res['items'][i]['NAME'], IDPrefix, deleteAddress, editAddress
			))
		}

		elements.reverse()

		elements.forEach((elem) => {
			this.dataArea.appendChild(elem)
		})
	}

	#displayUserElements(res, blockAddress)
	{
		while (this.dataArea.lastChild) {
			this.dataArea.lastChild.remove()
		}

		let elements = []

		for (let i = 0; i <  res['items'].length; i++) {
			elements.push(this.#createUserElement(
				res['items'][i]['ID'],
				[
					res['items'][i]['LAST_NAME'],
					res['items'][i]['NAME'],
					res['items'][i]['SECOND_NAME']
				].join(' '),
				'user-',
				blockAddress,
				res['items'][i]['ROLE']['NAME'],
				res['items'][i]['BLOCKED'],
			))
		}

		elements.reverse()

		elements.forEach((elem) => {
			this.dataArea.appendChild(elem)
		})

		this.#reloadFunc = this.#loadUsers

		let maxPage = Math.ceil(res['total'] / this.#itemsPerPage) - 1

		this.#bindPaginationButtons(this.#usersPage, maxPage)
	}

	#createAddButton(callback)
	{
		return this.#createButton(
			'hsl(46,100%,56%)', 'hsl(46,100%,65%)',
			'Add', callback
		)
	}

	#displayAddButton()
	{
		if (!this.addButton) {
			this.addButton = this.#createAddButton(this.#addButtonCallback)
		}
		if (this.addButtonArea.children.length === 0) {
			this.addButtonArea.appendChild(this.addButton)
		}

		this.addButton.onclick = () => { this.#addButtonCallback(); this.addButton.remove() }
	}

	#createTableElement(ID, name, elemIDPrefix, deleteAddress, editAddress, role = null)
	{
		let elem = this.#createTableElementContainer()
		elem.id = elemIDPrefix + ID

		let elemID = document.createElement('div')
		elemID.innerText = ID

		elem.appendChild(elemID)

		let elemName = document.createElement('div')
		elemName.innerText = name

		elem.appendChild(elemName)


		let editDelContainer = document.createElement('div')
		editDelContainer.id = 'edit-del-container'
		editDelContainer.style.display = 'flex'

		elem.appendChild(editDelContainer)

		let editButton = this.#createButton(
			'hsl(187,100%,41%)', 'hsl(187,100%,60%)',
			'Edit', () => {
				let elemNameInput = document.createElement('input')
				elemNameInput.id = elem.id + '-input'
				elemNameInput.classList.add('input-custom', 'edit-input-max-width')
				elemNameInput.value = elemName.innerText
				elemName.replaceWith(elemNameInput)

				editButton.removeEventListener('mouseenter', () => {
					editButton.style.backgroundColor = 'hsl(187,100%,60%)'
				})

				editButton.removeEventListener('mouseleave', () => {
					editButton.style.backgroundColor = 'hsl(187,100%,41%)'
				})

				let cancelButton = this.#createButton(
					'hsl(348, 100%, 61%)', 'hsl(348, 100%, 70%)',
					'Cancel', () => {
						elemNameInput.replaceWith(elemName)
					})

				deleteButton.replaceWith(cancelButton)

				let confirmButton = this.#createButton(
					'hsl(86,100%,45%)', 'hsl(86,100%,65%)',
					'Confirm', () => {
						BX.ajax.post(
							editAddress,
							{
								ID: ID,
								name: elemNameInput.value,
								sessid: BX.bitrix_sessid(),
							},
							(res) => {
								console.log(res)
								this.#reloadFunc()
								res = JSON.parse(res)
								if (res['TYPE'] !== 'OK') {
									this.errorMsgArea.innerHTML = this.#createErrorHTML(res['MESSAGE'])
								}
							}
						)
					})

				editButton.replaceWith(confirmButton)
			})

		editDelContainer.appendChild(editButton)

		let deleteButton = this.#createButton(
			'hsl(348, 100%, 61%)', 'hsl(348, 100%, 70%)',
			'Delete', () => {
				BX.ajax.post(
					deleteAddress,
					{
						ID: ID,
						sessid: BX.bitrix_sessid(),
					},
					(res) => {
						console.log(res)
						this.#reloadFunc()
					}
				)
			})

		editDelContainer.appendChild(deleteButton)

		if (role === null) return elem

		let elemRole = document.createElement('div')
		elemRole.innerText = role

		elem.appendChild(elemRole)

		return elem
	}

	#createUserElement(ID, fullName, elemIDPrefix, blockAddress, role, blocked)
	{
		let elem = this.#createTableElementContainer()
		elem.id = elemIDPrefix + ID
		elem.style.justifyContent = 'flex-start'

		let elemID = document.createElement('div')
		elemID.innerText = ID
		elemID.style.minWidth ='40px'

		elem.appendChild(elemID)
		
		let elemName = document.createElement('a')
		elemName.href = `/profile/${ID}/`
		elemName.target = '_black'
		elemName.rel = 'noopener noreferrer'
		elemName.innerText = fullName
		elemName.style.minWidth ='230px'
		elemName.style.color = '#4a4a4a'

		elem.appendChild(elemName)

		let elemRole = document.createElement('div')
		elemRole.innerText = role
		elemRole.style.minWidth = '70px'
		elemRole.style.display = 'flex'
		elemRole.style.justifyContent = 'center'
		elemRole.classList.add('box-dark-element-custom')

		elem.appendChild(elemRole)

		let generalCallback = (action) => {
			BX.ajax({
				url: blockAddress,
				data: {
					userID: ID,
					blocked: action,
					sessid: BX.bitrix_sessid(),
				},
				method: 'POST',
				dataType: 'json',
				timeout: 30,
				onsuccess: (res) => {
					console.log(res)
				},
				onfailure: (e) => {
					console.log(e)
				}
			})
		}

		let blockCallback = () => { generalCallback('Y') }

		let unblockCallback = () => { generalCallback('N')  }

		console.log(blocked)

		let blockButton = new SwitchButton({
			firstCallback: !blocked ? blockCallback : unblockCallback,
			secondCallback: !blocked ? unblockCallback : blockCallback,
			firstColor: !blocked ? 'hsl(348, 100%, 61%)' : 'hsl(0,0%,85%)',
			firstHoverColor: !blocked ? 'hsl(348, 100%, 70%)' : 'hsl(0,0%,70%)',
			secondColor: !blocked ? 'hsl(0,0%,85%)' : 'hsl(348, 100%, 61%)',
			secondHoverColor: !blocked ? 'hsl(0,0%,70%)' : 'hsl(348, 100%, 61%)',
			firstText: !blocked ? 'Block' : 'Unblock',
			secondText: !blocked ? 'Unblock' : 'Block',
		})

		// let blockButton = this.#createButton(
		// 	'hsl(348, 100%, 61%)', 'hsl(348, 100%, 70%)',
		// 	'Block', () => {
		// 		BX.ajax({
		// 			url: blockAddress,
		// 			data: {
		// 				userID: ID,
		// 				blocked: 'Y',
		// 				sessid: BX.bitrix_sessid(),
		// 			},
		// 			method: 'POST',
		// 			dataType: 'json',
		// 			timeout: 30,
		// 			onsuccess: (res) => {
		// 				console.log(res)
		// 				if (JSON.parse(res) === true) {
		// 					blockButton.replaceWith(
		// 						this.#createUnblockButton(blockAddress, ID, blockButton)
		// 					)
		// 				}
		// 			},
		// 			onfailure: (e) => {
		// 				console.log(e)
		// 			}
		// 		})
		// 	})

		blockButton.buttonEntity.style.marginLeft = '20px'

		elem.appendChild(blockButton.buttonEntity)

		return elem
	}

	#createTableElementContainer()
	{
		let elem = document.createElement('div')
		elem.classList.add('box-custom')
		elem.style.display = 'flex'
		elem.style.justifyContent = 'space-between'
		elem.style.alignItems = 'center'
		elem.style.width = '100%'
		return elem
	}

	#addItemTableElement(addItemAddress)
	{
		let form = this.#createTableElementContainer()
		form.id = 'add-form'

		let input = document.createElement('input')
		input.id = 'add-input'
		input.classList.add('input-custom')
		input.required = true
		form.appendChild(input)

		let confirmCancelContainer = document.createElement('div')

		form.appendChild(confirmCancelContainer)

		let confirmButton = this.#createButton(
			'hsl(86,100%,45%)', 'hsl(86,100%,65%)',
			'Confirm', () => {
				BX.ajax.post(
					addItemAddress,
					{
						name: input.value,
						sessid: BX.bitrix_sessid(),
					},
					(res) => {
						console.log(res)
						this.#reloadFunc()
						res = JSON.parse(res)
						if (res['TYPE'] !== 'OK') {
							this.errorMsgArea.innerHTML = this.#createErrorHTML(res['MESSAGE'])
						}
					}
				)
			})

		confirmCancelContainer.appendChild(confirmButton)

		let cancelButton = this.#createButton(
			'hsl(348, 100%, 61%)', 'hsl(348, 100%, 70%)',
			'Cancel', () => {
				form.remove()
				this.#displayAddButton()
			})

		confirmCancelContainer.appendChild(cancelButton)

		return form
	}

	#createButton(color, colorHover, text, callback)
	{
		let button = document.createElement('button')
		button.innerText = text
		button.style.margin = '0 5px'
		button.style.minHeight = '3rem'
		button.style.minWidth = '5rem'
		button.style.backgroundColor = color
		button.style.border = 'none'
		button.style.borderRadius = '10px'

		button.addEventListener('mouseenter', () => {
			button.style.backgroundColor = colorHover;
		})

		button.addEventListener('mouseleave', () => {
			button.style.backgroundColor = color;
		})

		button.onclick = callback

		return button
	}

	#createErrorHTML(text)
	{
		return `<article class="message is-danger">
            		<div class="message-body">${text}</div>
        		</article>`
	}

	// #createUnblockButton(blockAddress, userID, blockButton)
	// {
	// 	let unblockButton = this.#createButton(
	// 		'hsl(0,0%,85%)', 'hsl(0,0%,70%)', 'Unblock',
	// 		() => {
	// 			BX.ajax({
	// 				url: blockAddress,
	// 				data: {
	// 					userID: userID,
	// 					blocked: 'N',
	// 					sessid: BX.bitrix_sessid(),
	// 				},
	// 				method: 'POST',
	// 				dataType: 'json',
	// 				timeout: 30,
	// 				onsuccess: (res) => {
	// 					console.log(res)
	// 					if (JSON.parse(res) === true) {
	// 						unblockButton.replaceWith(blockButton)
	// 					}
	// 				},
	// 				onfailure: (e) => {
	// 					console.log(e)
	// 				}
	// 			})
	// 		})
	//
	// 	unblockButton.style.marginLeft = '20px'
	//
	// 	return unblockButton
	// }

	#bindPaginationButtons(page, maxPage)
	{
		console.log(page, maxPage)
		this.previousButton.classList.add('hidden')
		this.nextButton.classList.add('hidden')
		if (maxPage === 0) {
			return
		}
		if (page.value < maxPage ) {
			this.nextButton.classList.remove('hidden')
		}
		if (page.value > 0) {
			this.previousButton.classList.remove('hidden')
		}

		this.previousButton.onclick = () => {
			if (page.value <= 0 ) {
				return
			}
			page.value--
			this.#reloadFunc()
		}

		this.nextButton.onclick = () => {
			if (page.value >= maxPage) {
				return
			}
			page.value++
			this.#reloadFunc()
		}
	}
}

class Page
{
	value = 0
	constructor(value = 0) {
		this.value = value
	}
}

class SwitchButton
{
	currentCallback
	currentHoverColor
	currentColor
	constructor(params = {}) {
		if (typeof params.firstCallback != 'function') {
			throw new Error('SwitchButton: firstCallback is required')
		}
		this.firstCallback = params.firstCallback
		if (typeof params.secondCallback != 'function') {
			throw new Error('SwitchButton: secondCallback is required')
		}
		this.secondCallback = params.secondCallback
		this.currentCallback = this.firstCallback

		if (!Type.isStringFilled(params.firstColor)) {
			throw new Error('SwitchButton: firstColor is required')
		}
		this.firstColor = params.firstColor
		this.secondColor =  Type.isStringFilled(params.secondColor) ? params.secondColor : this.firstColor
		this.currentColor = this.firstColor

		this.firstHoverColor = Type.isStringFilled(params.firstHoverColor) ? params.firstHoverColor : this.firstColor
		this.secondHoverColor = Type.isStringFilled(params.secondHoverColor) ? params.secondHoverColor : this.secondColor
		this.currentHoverColor = this.firstHoverColor

		if (!Type.isStringFilled(params.firstText)) {
			throw new Error('SwitchButton: firstText is required')
		}
		this.firstText = params.firstText
		this.secondText =  Type.isStringFilled(params.secondText) ? params.secondText : this.firstText

		this.buttonEntity = document.createElement('button')
		this.buttonEntity.onclick = () => {
			console.log(this.firstCallback)
			this.firstCallback()
			this.switch()
		}

		if (Type.isStringFilled(params.styleClass)) {
			this.buttonEntity.classList.add(params.styleClass)
		}
		this.setDefaultStyle()
	}

	switch()
	{
		this.switchCallback()
		this.switchColor()
		this.switchText()
		this.buttonEntity.onclick = () => {
			console.log(this.currentCallback)
			this.currentCallback()
			this.switch()
		}
	}

	switchCallback()
	{
		this.currentCallback = this.currentCallback === this.firstCallback ?
			this.secondCallback : this.firstCallback
	}

	switchColor()
	{
		this.currentColor = this.currentColor === this.firstColor ?
			this.secondColor : this.firstColor
		this.currentHoverColor = this.currentHoverColor === this.firstHoverColor ?
			this.secondHoverColor : this.firstHoverColor
		this.buttonEntity.style.backgroundColor = this.currentColor
	}

	switchText()
	{
		this.buttonEntity.innerText =
			this.buttonEntity.innerText === this.firstText ?
			this.secondText : this.firstText
	}

	setDefaultStyle()
	{
		this.buttonEntity.innerText = this.firstText
		this.buttonEntity.style.margin = '0 5px'
		this.buttonEntity.style.minHeight = '3rem'
		this.buttonEntity.style.minWidth = '5rem'
		this.buttonEntity.style.backgroundColor = this.firstColor
		this.buttonEntity.style.border = 'none'
		this.buttonEntity.style.borderRadius = '10px'

		this.buttonEntity.addEventListener('mouseenter', () => {
			this.buttonEntity.style.backgroundColor = this.currentHoverColor
		})
		this.buttonEntity.addEventListener('mouseleave', () => {
			this.buttonEntity.style.backgroundColor = this.currentColor
		})
	}
}