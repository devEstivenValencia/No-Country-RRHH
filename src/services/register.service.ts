import { APIROUTES } from '#/config/API.routes'
import { Contact, contactSchema } from '#/entities/Contact.entity'
import { Credentials, credentialsSchema } from '#/entities/Credentials.entity'
import { schemaIsValid } from '#/utils/schemaValidator.util'
import axios from 'axios'
import Cookies from 'js-cookie'

import { encryptService } from './encrypt.service'

export async function registerService(credentials: Credentials, contact: Contact) {
	if (!schemaIsValid(credentialsSchema, credentials) && !schemaIsValid(contactSchema, contact)) {
		throw new Error('Infomación invalida')
	}

	try {
		const encryptCredentials = await encryptService(credentials)
		const encryptContact = await encryptService(contact)
		const { data } = await axios.post(APIROUTES.REGISTER, encryptCredentials && encryptContact)
		const { session, user } = data

		Cookies.set('session', session)
		localStorage.setItem('user', JSON.stringify(user))
		console.log(session)
		return data
	} catch (reason: any) {
		const { message } = reason.response.data || reason
		throw new Error({ message, ...reason })
	}
}
