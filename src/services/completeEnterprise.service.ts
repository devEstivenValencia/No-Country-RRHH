import { APIROUTES } from '#/config/API.routes'
import { schemaIsValid } from '#/utils/schemaValidator.util'
import axios from 'axios'
import Cookies from 'js-cookie'
import { encryptService } from './encrypt.service'
import { CompleteEnterprise, completeEnterpriseSchema } from '#/entities/CompleteEnterprise.entity'

export async function completeEnterpriseService(completeEnterprise: CompleteEnterprise) {
	if (!schemaIsValid(completeEnterpriseSchema, completeEnterprise)) {
		throw new Error('No se pudo crear este empleado')
	}

	const { location, contact } = completeEnterprise

	try {
        const encryptLocation = await encryptService ({ location })
        const encryptContact = await encryptService({ contact })
		
		const { data } = await axios.post(APIROUTES.EMPLOYEE.CREATE, {
            location: encryptLocation?.location,
			contact: encryptContact?.contact,
            role: completeEnterprise.role,
            sector: completeEnterprise.sector
		})
		const { session, user } = data

		Cookies.set('session', session)
		localStorage.setItem('user', JSON.stringify(user))
		return data
	} catch (reason: any) {
		const { message } = reason.response?.data || reason
        console.log(message)
		throw new Error(message)
	}
}