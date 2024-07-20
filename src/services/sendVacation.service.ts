'use client'

import { APIROUTES } from '#/config/API.routes'
import axios from 'axios'
import Cookies from 'js-cookie'

export async function SendVacationService<T>(initialDate: string, finalDate: string): Promise<T | undefined> {
	try {
		const session = Cookies.get('session')
		const config = {
			headers: {
				'Content-Type': 'application/json',
				'Accept': 'application/json',
				'Authorization': 'Bearer ' + session
			}
		}
		const { data } = await axios.post(
			APIROUTES.SEND_VACATION,
			{
				initial_date: initialDate,
				final_date: finalDate
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
