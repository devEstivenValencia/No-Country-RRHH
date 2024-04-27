import { APIROUTES } from '#/config/API.routes'
import { CompleteEnterprise, completeEnterpriseSchema } from '#/entities/CompleteEnterprise.entity'
import { schemaIsValid } from '#/utils/schemaValidator.util'
import { decryptData, encryptData } from '#/utils/securityFunctions.util'
import axios from 'axios'
import Cookies from 'js-cookie'
import { pki } from 'node-forge'

import { encryptkeyService } from './encryptkey.service'

export async function completeEnterpriseService(
	completeEnterprise: CompleteEnterprise,
	workingWeek: string[],
	keypair: pki.rsa.KeyPair | undefined,
	publicPemKey: string
) {
	if (!schemaIsValid(completeEnterpriseSchema, completeEnterprise)) {
		throw new Error('No se pudo crear este empleado')
	}

	const { location, contact } = completeEnterprise

	try {
		const { encrypter, decrypter, keyId } = await encryptkeyService(keypair, publicPemKey)

		const encryptLocation = {
			country: encryptData(encrypter, location.country),
			province: encryptData(encrypter, location.province),
			city: encryptData(encrypter, location.city),
			address: encryptData(encrypter, location.address),
			zipcode: encryptData(encrypter, location.zipcode)
		}
		const encryptContact = {
			email: encryptData(encrypter, contact.email),
			phone: encryptData(encrypter, contact.phone)
		}

		const session = Cookies.get('session')
		const config = {
			headers: {
				'Content-Type': 'application/json',
				'Accept': 'application/json',
				'Authorization': 'Bearer ' + session
			}
		}

		const { data } = await axios.put(
			APIROUTES.COMPLETE_REGISTER,
			{
				key_id: keyId,
				location: encryptLocation,
				contact: encryptContact,
				role: completeEnterprise.role,
				sector: completeEnterprise.sector,
				working_week: JSON.stringify(workingWeek)
			},
			config
		)
		const { user } = data

		user.location.country = decryptData(decrypter, user.location.country)
		user.location.province = decryptData(decrypter, user.location.province)
		user.location.city = decryptData(decrypter, user.location.city)
		user.location.address = decryptData(decrypter, user.location.address)

		user.contact.email = decryptData(decrypter, user.contact.email)
		user.contact.phone = decryptData(decrypter, user.contact.phone)
		console.log(user)

		localStorage.setItem('user', JSON.stringify(user))
		return data
	} catch (reason: any) {
		const { message } = reason.response?.data || reason
		console.log(message)
		throw new Error(message)
	}
}
