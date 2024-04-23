import { APIROUTES } from '#/config/API.routes'
import { NewEnterprise, newEnterpriseSchema } from '#/entities/NewEnterprise.entity'
import { schemaIsValid } from '#/utils/schemaValidator.util'
import axios from 'axios'
import Cookies from 'js-cookie'
import { encryptData, decryptData } from '#/utils/securityFunctions.util'

export async function registerService(newEnterprise: NewEnterprise, getEncryptionKey: Function) {
	if (!schemaIsValid(newEnterpriseSchema, newEnterprise)) {
		throw new Error('No se puede crear esta empresa')
	}

	const { credentials, contact } = newEnterprise

	try {
		const {encrypter, decrypter, idkey} = getEncryptionKey();
		const encryptCredentials = {
			'email' : encryptData(encrypter, credentials.email),
			'password' : encryptData(encrypter, credentials.password)
		}
		const encryptContact = {
			'email' : encryptData(encrypter, contact.email),
			'phone' : encryptData(encrypter, contact.phone)
		}
		const { data } = await axios.post(APIROUTES.REGISTER, {
			company_name: newEnterprise.companyName,
			credentials: encryptCredentials,
			contact: encryptContact
		})
		let { session, user } = data

		user.contact.email = decryptData(decrypter, user?.contact?.email)
		user.contact.phone = decryptData(decrypter, user?.contact?.phone)

		Cookies.set('session', session)
		localStorage.setItem('user', JSON.stringify(user))
		return data
	} catch (reason: any) {
		const { message } = reason.response.data || reason
		throw new Error({ message, ...reason })
	}
}
