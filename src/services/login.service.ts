import { APIROUTES } from '#/config/API.routes'
import { Credentials, credentialsSchema } from '#/entities/Credentials.entity'
import { schemaIsValid } from '#/utils/schemaValidator.util'
import axios from 'axios'
import Cookies from 'js-cookie'

import { encryptService } from './encrypt.service'

export async function loginService(credentials: Credentials) {
	if (!schemaIsValid(credentialsSchema, credentials)) {
		throw new Error('Usuario o contraseña invalido')
	}

	try {
		const encryptCredentials = await encryptService(credentials)
		const { data } = await axios.post(APIROUTES.LOGIN, encryptCredentials)
		const { session, user } = data

		Cookies.set('session', session)
		localStorage.setItem('user', JSON.stringify(user))
		return data
	} catch (reason: any) {
		const { message } = reason.response.data || reason
		throw new Error({ message, ...reason })
	}
}
