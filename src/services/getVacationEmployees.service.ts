'use client'

import { APIROUTES } from '#/config/API.routes'
import axios from 'axios'
import Cookies from 'js-cookie'
import { pki } from 'node-forge'

import { encryptkeyService } from './encryptkey.service'

export async function getVacationEmployeesService(keypair: pki.rsa.KeyPair | undefined, publicPemKey: string) {
	try {
		const { keyId } = await encryptkeyService(keypair, publicPemKey)
		console.log('key id ', keyId)

		const session = Cookies.get('session')
		const config = {
			headers: {
				'Content-Type': 'application/json',
				'Accept': 'application/json',
				'Authorization': 'Bearer ' + session
			}
		}

		const { data } = await axios.get(APIROUTES.VACATION_LIST + '?key_id=' + keyId, config)
		console.log(data)
		/* 
		data?.employees.forEach((e: any, i: any) => {
			e.dni = decryptData(decrypter, e.dni)
			Object.keys(e.contact).forEach(function (key) {
				e.contact[key] = decryptData(decrypter, e.contact[key])
			})
			Object.keys(e.location).forEach(function (key) {
				e.location[key] = decryptData(decrypter, e.location[key])
			})
		}) */

		/* data?.employees.forEach((e: any, i: any) => {
			e.contact.email = decryptData(decrypter, e.contact.email)
		}) */

		return data
	} catch (reason: any) {
		const { message } = reason.response?.data || reason
		console.log(message)
		throw new Error(message)
	}
}
