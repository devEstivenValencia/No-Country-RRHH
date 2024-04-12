import { KeyWithValue } from '#/types/KeyWithValue.type'

const APIURL = process.env.APIURL || 'https://test-laravel-api1.000webhostapp.com/api'

export const APIROUTES: KeyWithValue<string> = {
	LOGIN: `${APIURL}/session/login`
} as const
