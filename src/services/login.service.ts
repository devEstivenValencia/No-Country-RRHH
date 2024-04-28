'use client'

import { APIROUTES } from '#/config/API.routes'
import { Credentials, credentialsSchema } from '#/entities/Credentials.entity'
import { schemaIsValid } from '#/utils/schemaValidator.util'
import { decryptData, encryptData } from '#/utils/securityFunctions.util'
import axios from 'axios'
import Cookies from 'js-cookie'
import { pki } from 'node-forge'

import { encryptkeyService } from './encryptkey.service'

export async function loginService(
	credentials: Credentials,
	keypair: pki.rsa.KeyPair | undefined,
	publicPemKey: string
) {
	if (!schemaIsValid(credentialsSchema, credentials)) {
		throw new Error('Usuario o contraseña invalido')
	}

	try {
		const { encrypter, decrypter, keyId } = await encryptkeyService(keypair, publicPemKey)

		const encryptCredentials = {
			key_id: keyId,
			email: encryptData(encrypter, credentials.email),
			password: encryptData(encrypter, credentials.password)
		}
		const { data } = await axios.post(APIROUTES.SESSION.LOGIN, encryptCredentials)
		const { session, user } = data

		if (user?.contact) {
			user.contact.email = decryptData(decrypter, user.contact.email)
			user.contact.phone = decryptData(decrypter, user.contact.phone)
		}
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
