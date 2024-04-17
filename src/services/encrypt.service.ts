'use server'

import { APIROUTES } from '#/config/API.routes'
import axios from 'axios'

export async function encryptService<T>(values: T): Promise<T | undefined> {
	try {
		const { data } = await axios.post(APIROUTES.ENCRYPT, values)
		return data
	} catch (reason: any) {
		const { message } = reason.response.data || reason
		throw new Error({ message, ...reason })
	}
}
