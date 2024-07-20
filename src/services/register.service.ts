'use client'

import { APIROUTES } from '#/config/API.routes'
import { NewEnterprise, newEnterpriseSchema } from '#/entities/NewEnterprise.entity'
import { schemaIsValid } from '#/utils/schemaValidator.util'
import { decryptData, encryptData } from '#/utils/securityFunctions.util'
import axios from 'axios'
import Cookies from 'js-cookie'
import { pki } from 'node-forge'

import { encryptkeyService } from './encryptkey.service'

export async function registerService(
	newEnterprise: NewEnterprise,
	keypair: pki.rsa.KeyPair | undefined,
	publicPemKey: string
) {
	if (!schemaIsValid(newEnterpriseSchema, newEnterprise)) {
		throw new Error('No se puede crear esta empresa')
	}

	const { credentials, contact } = newEnterprise

	try {
		const { encrypter, decrypter, keyId } = await encryptkeyService(keypair, publicPemKey)

		const encryptCredentials = {
			email: encryptData(encrypter, credentials.email),
			password: encryptData(encrypter, credentials.password)
		}
		const encryptContact = {
			email: encryptData(encrypter, contact.email),
			phone: encryptData(encrypter, contact.phone)
		}
		const { data } = await axios.post(APIROUTES.REGISTER, {
			key_id: keyId,
			company_name: newEnterprise.companyName,
			credentials: encryptCredentials,
			contact: encryptContact
		})

		const { session, user } = data

		user.contact.email = decryptData(decrypter, user.contact.email)
		user.contact.phone = decryptData(decrypter, user.contact.phone)

		console.log(user)
		Cookies.set('session', session)
		localStorage.setItem('user', JSON.stringify(user))
		Cookies.set('type', user?.type)
		return data
	} catch (reason: any) {
		const { message } = reason.response?.data || reason
		throw new Error(message)
	}
}
