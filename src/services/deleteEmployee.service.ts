'use client'

import { APIROUTES } from '#/config/API.routes'
import axios from 'axios'
import Cookies from 'js-cookie'

export async function deleteEmployeeService<T>(employeeId: string): Promise<T | undefined> {
	try {
		const session = Cookies.get('session')
		const config = {
			headers: {
				'Content-Type': 'application/json',
				'Accept': 'application/json',
				'Authorization': 'Bearer ' + session
			}
		}
		const { data } = await axios.delete(APIROUTES.EMPLOYEE.DELETE + '?employee_id=' + employeeId, config)

		return data
	} catch (reason: any) {
		const { message } = reason.response?.data || reason
		console.log(message)
		throw new Error(message)
	}
}
