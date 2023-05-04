import {Type} from 'main.core';

export class AdminPanel
{
	#itemsPerPage = 3
	#usersPage = 0
	#subjectsPage = 0
	#edFormatsPage = 0
	#citiesPage = 0
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
			throw new Error('Feedbacks: "buttonsContainerID" is required')
		}

		this.dataArea = document.getElementById(options.dataAreaID)

		if (!this.dataArea) {
			throw new Error(`Feedbacks: element with ID "${options.dataAreaID}" not found`)
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
		this.#displayIDNameElements(res, 'subject-', () => {

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
		this.#displayIDNameElements(res, 'ed-format-', () => {

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
		this.#displayIDNameElements(res, 'city-', () => {

		})
	}

	#displayIDNameElements(res, IDPrefix, callback)
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

		this.dataArea.appendChild(this.#createAddButton(callback))
	}

	#createAddButton(callback)
	{
		let addButton = document.createElement('button')
		addButton.innerText = 'Add'
		addButton.style.margin = '0 5px'
		addButton.style.minHeight = '3rem'
		addButton.style.width = '5rem'
		addButton.style.backgroundColor = 'hsl(46,100%,56%)';
		addButton.addEventListener('mouseenter', () => {
			addButton.style.backgroundColor = 'hsl(46,100%,65%)';
		})

		addButton.addEventListener('mouseleave', () => {
			addButton.style.backgroundColor = 'hsl(46,100%,56%)';
		})
		addButton.style.border = 'none'
		addButton.style.borderRadius = '10px'
		addButton.onclick = () => {callback()}
		return addButton
	}

	#createTableElement(ID, name, elemIDPrefix, role = null)
	{
		let elem = document.createElement('div')
		elem.classList.add('box')
		elem.style.display = 'flex'
		elem.style.justifyContent = 'space-between'
		elem.style.alignItems = 'center'
		elem.style.width = '100%'
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

		let editButton = document.createElement('button')
		editButton.innerText = 'Edit'
		editButton.style.margin = '0 5px'
		editButton.style.minHeight = '3rem'
		editButton.style.minWidth = '5rem'
		editButton.style.backgroundColor = 'hsl(187,100%,41%)';
		editButton.style.border = 'none'
		editButton.style.borderRadius = '10px'

		editButton.addEventListener('mouseenter', () => {
			editButton.style.backgroundColor = 'hsl(187,100%,60%)';
		})

		editButton.addEventListener('mouseleave', () => {
			editButton.style.backgroundColor = 'hsl(187,100%,41%)';
		})

		editDelContainer.appendChild(editButton)

		let deleteButton = document.createElement('button')
		deleteButton.innerText = 'Delete'
		deleteButton.style.margin = '0 5px'
		deleteButton.style.minHeight = '3rem'
		deleteButton.style.minWidth = '5rem'
		deleteButton.style.backgroundColor = 'hsl(348, 100%, 61%)';
		deleteButton.style.border = 'none'
		deleteButton.style.borderRadius = '10px'

		deleteButton.addEventListener('mouseenter', () => {
			deleteButton.style.backgroundColor = 'hsl(348, 100%, 70%)';
		})

		deleteButton.addEventListener('mouseleave', () => {
			deleteButton.style.backgroundColor = 'hsl(348, 100%, 61%)';
		})

		editDelContainer.appendChild(deleteButton)

		if (role === null) return elem

		let elemRole = document.createElement('div')
		elemRole.innerText = name

		elem.appendChild(elemRole)

		return elem
	}
}