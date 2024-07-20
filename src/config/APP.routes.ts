import { KeyWithValue } from '#/types/KeyWithValue.type'

export const APPROUTES: KeyWithValue<string> = {
	HOME: '/',
	DASHBOARD: '/app',
	LOGIN: '/login',
	REGISTER: '/register',
	EMPLOYEES: '/app/employees',
	ENTERPRISE: '/enterprise',
	WORKER: '/worker'
} as const
