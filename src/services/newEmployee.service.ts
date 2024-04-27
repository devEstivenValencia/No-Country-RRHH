import { APIROUTES } from '#/config/API.routes'
import { NewEmployee, newEmployeeSchema } from '#/entities/NewEmployee.entity'
import { schemaIsValid } from '#/utils/schemaValidator.util'
import { encryptData } from '#/utils/securityFunctions.util'
import axios from 'axios'
import Cookies from 'js-cookie'
import { pki } from 'node-forge'

import { encryptkeyService } from './encryptkey.service'

export async function employeeService(
	newEmployee: NewEmployee,
	keypair: pki.rsa.KeyPair | undefined,
	publicPemKey: string
) {
	if (!schemaIsValid(newEmployeeSchema, newEmployee)) {
		throw new Error('No se pudo crear este empleado')
	}

	const { dni, location, contact, credentials } = newEmployee.employee

	try {
		const { encrypter, keyId } = await encryptkeyService(keypair, publicPemKey)
		const encryptDni = encryptData(encrypter, dni)
		const encryptLocation = {
			country: encryptData(encrypter, location.country),
			province: encryptData(encrypter, location.province),
			city: encryptData(encrypter, location.city),
			address: encryptData(encrypter, location.address)
		}
		const encryptContact = {
			email: encryptData(encrypter, contact.email),
			phone: encryptData(encrypter, contact.phone)
		}
		const encryptCredentials = {
			email: encryptData(encrypter, credentials.email),
			password: encryptData(encrypter, credentials.password)
		}

		const session = Cookies.get('session')
		const config = {
			headers: {
				'Content-Type': 'application/json',
				'Accept': 'application/json',
				'Authorization': 'Bearer ' + session
			}
		}
		const user = JSON.parse(localStorage.getItem('user') || '{}')
		const companyIdEnctypted = encryptData(encrypter, user?.id || '')
		const { data } = await axios.post(
			APIROUTES.EMPLOYEE.CREATE,
			{
				key_id: keyId,
				company_id: companyIdEnctypted,
				employee: {
					name: newEmployee.employee.name,
					dni: encryptDni,
					address: encryptLocation,
					contact: encryptContact,
					credentials: encryptCredentials,
					admission_date: newEmployee.employee.admissionDate,
					role: newEmployee.employee.role
				}
			},
			config
		)

		return data
	} catch (reason: any) {
		const { message } = reason.response?.data || reason
		console.log(message)
		throw new Error(message)
	}
}
