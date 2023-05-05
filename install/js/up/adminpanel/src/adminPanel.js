import {Type} from 'main.core';

export class AdminPanel
{
	#itemsPerPage = 3
	#usersPage = 0
	#subjectsPage = 0
	#edFormatsPage = 0
	#citiesPage = 0

	#reloadFunc

	constructor(options = {})
	{
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
			throw new Error('Feedbacks: "userButtonID" is required')
		}

		this.subjectsButton = document.getElementById(options.subjectsButtonID)

		if (!this.subjectsButton) {
			throw new Error(`Feedbacks: element with ID "${options.subjectsButtonID}" not found`)
		}

		this.subjectsButton.onclick = () => {this.#loadSubjects()}


		if (!Type.isStringFilled(options.edFormatsButtonID)) {
			throw new Error('Feedbacks: "userButtonID" is required')
		}

		this.edFormatsButton = document.getElementById(options.edFormatsButtonID)

		if (!this.edFormatsButton) {
			throw new Error(`Feedbacks: element with ID "${options.edFormatsButtonID}" not found`)
		}

		this.edFormatsButton.onclick = () => {this.#loadEdFormats()}


		if (!Type.isStringFilled(options.citiesButtonID)) {
			throw new Error('Feedbacks: "userButtonID" is required')
		}

		this.citiesButton = document.getElementById(options.citiesButtonID)

		if (!this.citiesButton) {
			throw new Error(`Feedbacks: element with ID "${options.citiesButtonID}" not found`)
		}

		this.citiesButton.onclick = () => {this.#loadCities()}

		this.addButton = this.#createAddButton(() => {})
	}

	#loadUsers()
	{
		for (let i = 0; i < this.buttonsContainer.children.length; i++) {
			this.buttonsContainer.children[i].classList.remove('menu-button-active')
		}
		this.userButton.classList.add('menu-button-active')
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
				page: this.#subjectsPage,
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
		this.#displayIDNameElements(res, 'subject-')

		this.#displayAddButton(() => {
			this.dataArea.appendChild(this.#addItemTableElement(
					'/admin/add/subjects/',
						this.#loadSubjects
				))
		})
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
				page: this.#edFormatsPage,
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
		this.#displayIDNameElements(res, 'ed-format-')

		this.#displayAddButton(() => {
			this.dataArea.appendChild(this.#addItemTableElement(
				'/admin/add/edFormat/',
				this.#loadEdFormats
			))
		})
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
				page: this.#citiesPage,
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
		this.#displayIDNameElements(res, 'city-')

		this.#displayAddButton(() => {
			this.dataArea.appendChild(this.#addItemTableElement(
					'/admin/add/city/',
					this.#loadCities
				))
		})
	}

	#displayIDNameElements(res, IDPrefix)
	{
		while (this.dataArea.lastChild) {
			this.dataArea.lastChild.remove()
		}

		let elements = []

		for (let i = 0; i <  res.length; i++) {
			elements.push(this.#createTableElement(res[i]['ID'], res[i]['NAME'], IDPrefix))
		}

		elements.reverse()

		elements.forEach((elem) => {
			this.dataArea.appendChild(elem)
		})
	}

	#createAddButton(callback)
	{
		return this.#createButton(
			'hsl(46,100%,56%)', 'hsl(46,100%,65%)',
			'Add', callback
		)
	}

	#displayAddButton(addButtonCallback)
	{
		if (!this.addButton) {
			this.addButton = this.#createAddButton(addButtonCallback)
		}
		if (this.addButtonArea.children.length === 0) {
			this.addButtonArea.appendChild(this.addButton)
		}

		this.addButton.onclick = addButtonCallback
	}

	#createTableElement(ID, name, elemIDPrefix, role = null)
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

			})

		editDelContainer.appendChild(editButton)

		let deleteButton = this.#createButton(
			'hsl(348, 100%, 61%)', 'hsl(348, 100%, 70%)',
			'Delete', () => {

			})

		editDelContainer.appendChild(deleteButton)

		if (role === null) return elem

		let elemRole = document.createElement('div')
		elemRole.innerText = name

		elem.appendChild(elemRole)

		return elem
	}

	#createTableElementContainer()
	{
		let elem = document.createElement('div')
		elem.classList.add('box')
		elem.style.display = 'flex'
		elem.style.justifyContent = 'space-between'
		elem.style.alignItems = 'center'
		elem.style.width = '100%'
		return elem
	}

	#addItemTableElement(addItemAddress, reloadFunc)
	{
		this.#reloadFunc = reloadFunc

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
					},
					(res) => {
						console.log(res)
						this.#reloadFunc()
					}
				)
			})

		confirmCancelContainer.appendChild(confirmButton)

		let cancelButton = this.#createButton(
			'hsl(348, 100%, 61%)', 'hsl(348, 100%, 70%)',
			'Cancel', () => {
				form.remove()
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
}