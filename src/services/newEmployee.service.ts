import { APIROUTES } from '#/config/API.routes'
import { schemaIsValid } from '#/utils/schemaValidator.util'
import axios from 'axios'
import Cookies from 'js-cookie'
import { encryptService } from './encrypt.service'
import { NewEmployee, newEmployeeSchema } from '#/entities/NewEmployee.entity'

export async function employeeService(newEmployee: NewEmployee) {
	if (!schemaIsValid(newEmployeeSchema, newEmployee)) {
		throw new Error('No se pudo crear este empleado')
	}

	const { employee:dni, employee:address, employee:contact, employee:credentials } = newEmployee

	try {
        const encryptDni = await encryptService ({ dni })
        const encryptAddress = await encryptService ({ address })
        const encryptContact = await encryptService({ contact })
		const encryptCredentials = await encryptService({ credentials })
		
		const { data } = await axios.post(APIROUTES.EMPLOYEE.CREATE, {
			name: newEmployee.employee.name,
			dni: encryptDni?.dni,
            address: encryptAddress?.address,
			contact: encryptContact?.contact,
            credentials: encryptCredentials?.credentials,
            admissionDate: newEmployee.employee.admissionDate,
            role: newEmployee.employee.role
		})
		const { session, user } = data

		Cookies.set('session', session)
		localStorage.setItem('user', JSON.stringify(user))
		return data
	} catch (reason: any) {
		const { message } = reason.response.data || reason
        console.log(message)
		throw new Error({ message, ...reason })
	}
}