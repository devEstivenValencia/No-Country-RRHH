import { KeyWithValue } from '#/types/KeyWithValue.type'

const APIURL = process.env.NEXT_PUBLIC_APIURL

export const APIROUTES: KeyWithValue<string> = {
	LOGIN: `${APIURL}/session/login`,
	REGISTER: `${APIURL}/enterprise/register`,
	ENCRYPT: `${APIURL}/encrypt`
} as const
