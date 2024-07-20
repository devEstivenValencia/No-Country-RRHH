import { KeyWithValue } from '#/types/KeyWithValue.type'

const APIURL = process.env.NEXT_PUBLIC_APIURL

export const APIROUTES: KeyWithValue<any> = {
	SESSION: {
		LOGIN: `${APIURL}/session/login`,
		VALIDATE: `${APIURL}/session/validate`
	},
	REGISTER: `${APIURL}/account/enterprise/register`,
	COMPLETE_REGISTER: `${APIURL}/account/enterprise/modify`,
	ENCRYPT: `${APIURL}/encrypt`,
	ENCRYPT_KEY: `${APIURL}/encryptkey`,
	VACATION_LIST: `${APIURL}/enterprise/vacations`,
	SEND_VACATION: `${APIURL}/enterprise/employee/vacation/create`,
	EMPLOYEE: {
		CREATE: `${APIURL}/enterprise/employee/create`,
		GET_ALL: `${APIURL}/enterprise/employee`,
		DELETE: `${APIURL}/enterprise/employee/delete`
	}
} as const
