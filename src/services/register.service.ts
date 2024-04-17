import { APIROUTES } from '#/config/API.routes'
import { NewEnterprise, newEnterpriseSchema } from '#/entities/NewEnterprise.entity'
import { schemaIsValid } from '#/utils/schemaValidator.util'
import axios from 'axios'
import Cookies from 'js-cookie'

import { encryptService } from './encrypt.service'

export async function registerService(newEnterprise: NewEnterprise) {
	if (!schemaIsValid(newEnterpriseSchema, newEnterprise)) {
		throw new Error('No se puede crear esta empresa')
	}

	const { credentials, contact } = newEnterprise

	try {
		const encryptCredentials = await encryptService(credentials)
		const encryptContact = await encryptService(contact)
		const { data } = await axios.post(APIROUTES.REGISTER, {
			company_name: newEnterprise.companyName,
			credentials: encryptCredentials,
			contact: encryptContact
		})
		const { session, user } = data

		Cookies.set('session', session)
		localStorage.setItem('user', JSON.stringify(user))
		return data
	} catch (reason: any) {
		const { message } = reason.response.data || reason
		throw new Error({ message, ...reason })
	}
}
