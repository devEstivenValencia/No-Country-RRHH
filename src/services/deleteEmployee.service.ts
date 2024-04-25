'use client'

import { APIROUTES } from '#/config/API.routes'
import axios from 'axios'
import Cookies from 'js-cookie'

export async function deleteEmployeeService<T>(employee_id: string): Promise<T | undefined> {
	try {
		const session = Cookies.get('session')
		let config = {
			headers: {
				'Content-Type': 'application/json',
				'Accept': 'application/json',
				'Authorization': 'Bearer ' + session
			}
		}
		const { data } = await axios.delete(APIROUTES.EMPLOYEE.DELETE + '?employee_id=' + employee_id, config)

		return data
	} catch (reason: any) {
		const { message } = reason.response?.data || reason
		console.log(message)
		throw new Error(message)
	}
}
